<?php
namespace IchHabRecht\GitFlow;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Repository\ComposerRepository;
use Composer\Repository\RepositoryInterface;
use Composer\Semver\VersionParser;

class Plugin implements PluginInterface
{
    /**
     * @var PackageInterface[]
     */
    protected $packages;

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $stability = trim((string)getenv('STABILITY'));
        if (empty($stability) || 'master' === $stability) {
            return;
        }

        $io->write('> ichhabrecht/composer-git-flow-plugin');
        $io->write('  - using STABILITY=' . $stability);
        $this->adjustGitFlowDependencies($composer, $stability);
    }

    /**
     * Adjusts package requirements depending on the stability environment setting
     *
     * @param Composer $composer
     * @param string $stability
     */
    protected function adjustGitFlowDependencies(Composer $composer, $stability)
    {
        $newRequires = [];
        $versionParser = new VersionParser();
        foreach ($composer->getPackage()->getRequires() as $packageName => $package) {
            if ('dev-master' !== $package->getPrettyConstraint()) {
                $newRequires[$packageName] = $package;
                continue;
            }

            $branch = static::findStabilityBranch($packageName, $stability, $composer);
            $link = new Link(
                $package->getSource(),
                $package->getTarget(),
                $versionParser->parseConstraints($branch),
                $package->getDescription(),
                $branch
            );
            $newRequires[$packageName] = $link;
        }
        $composer->getPackage()->setRequires($newRequires);
    }

    /**
     * Returns package branch to use according to the desired stability
     *
     * @param string $packageName
     * @param string $stability
     * @param Composer $composer
     * @return string
     */
    protected function findStabilityBranch($packageName, $stability, Composer $composer)
    {
        if ($this->packages === null) {
            $this->initializePackages($composer);
        }

        if (!isset($this->packages[$packageName])) {
            return 'dev-master';
        }

        /** @var Package $package */
        foreach ($this->packages[$packageName] as $package) {
            if (0 === strpos($package->getPrettyVersion(), 'dev-' . $stability)) {
                return $package->getPrettyVersion();
            }
        }

        return 'dev-master';
    }

    /**
     * Initializes the known composer packages
     *
     * @param Composer $composer
     */
    protected function initializePackages(Composer $composer)
    {
        $repositoryManager = $composer->getRepositoryManager();
        /** @var RepositoryInterface $repository */
        foreach ($repositoryManager->getRepositories() as $repository) {
            if ($repository instanceof ComposerRepository && $repository->hasProviders()) {
                continue;
            }
            foreach ($repository->getPackages() as $package) {
                $this->packages[$package->getName()] = $package->getRepository()->getPackages();
            }
        }
    }
}
