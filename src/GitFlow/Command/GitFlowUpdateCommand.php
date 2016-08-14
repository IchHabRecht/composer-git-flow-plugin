<?php
namespace IchHabRecht\GitFlow\Command;

use Composer\Command\UpdateCommand;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\ComposerRepository;
use Composer\Repository\RepositoryInterface;
use Composer\Semver\VersionParser;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GitFlowUpdateCommand extends UpdateCommand
{
    /**
     * @var PackageInterface[]
     */
    private $packages;

    /**
     * @var string
     */
    private $stability;

    /**
     * Sets the name of this command
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('git-flow-update');
        $this->addOption('stability', '', InputOption::VALUE_OPTIONAL, 'Define the branch prefix which should be used to checkout your repositories');
    }

    /**
     * Execute command, adjust constraints and start update
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = $this->getIO();
        $io->writeError('> ichhabrecht/composer-git-flow-plugin');

        $this->stability = $input->getOption('stability');
        $stability = trim((string)getenv('STABILITY'));
        if (!empty($stability)) {
            $io->writeError('Warning: You are using the deprecated environment variable `STABILITY`. Please use cli option --stability ' . $stability);
            $this->stability = $stability;
        }

        $io->writeError('  - using STABILITY=' . $this->stability);
        $io->writeError('');

        $composer = $this->getComposer(true, $input->getOption('no-plugins'));

        $requires = $composer->getPackage()->getRequires();
        $newRequires = $this->adjustGitFlowPackages($requires);
        $packages = array_keys($newRequires);
        $composer->getPackage()->setRequires(array_merge($requires, $newRequires));

        if (!$input->getOption('no-dev')) {
            $requires = $this->adjustGitFlowPackages($composer->getPackage()->getDevRequires());
            $newRequires = $this->adjustGitFlowPackages($requires);
            $packages += array_keys($newRequires);
            $composer->getPackage()->setDevRequires(array_merge($requires, $newRequires));
        }

        $input->setArgument('packages', $packages);
        $io->writeError('');

        return parent::execute($input, $output);
    }

    /**
     * Loops over packages and adjusts the dependency constraints
     *
     * @param array $packages
     * @return array
     */
    protected function adjustGitFlowPackages(array $packages)
    {
        $newRequires = [];
        $versionParser = new VersionParser();
        foreach ($packages as $packageName => $package) {
            if ('dev-master' === $package->getPrettyConstraint()) {
                $branch = $this->findStabilityBranch($packageName);
                $this->getIO()->writeError('  - Adjusting ' . $packageName . ' to ' . $branch);
                $link = new Link(
                    $package->getSource(),
                    $package->getTarget(),
                    $versionParser->parseConstraints($branch),
                    $package->getDescription(),
                    $branch
                );
                $newRequires[$packageName] = $link;
            }
        }

        return $newRequires;
    }

    /**
     * Returns package branch to use according to the desired stability
     *
     * @param string $packageName
     * @return string
     */
    protected function findStabilityBranch($packageName)
    {
        if ($this->packages === null) {
            $this->initializePackages();
        }

        if (!isset($this->packages[$packageName]) || empty($this->stability) || 'master' === $this->stability) {
            return 'dev-master';
        }

        /** @var Package $package */
        foreach ($this->packages[$packageName] as $package) {
            if (0 === strpos($package->getPrettyVersion(), 'dev-' . $this->stability)) {
                return $package->getPrettyVersion();
            }
        }

        return 'dev-master';
    }

    /**
     * Initializes the known composer packages
     */
    protected function initializePackages()
    {
        $repositoryManager = $this->getComposer()->getRepositoryManager();
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
