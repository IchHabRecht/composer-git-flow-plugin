<?php
namespace IchHabRecht\GitFlow;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use IchHabRecht\GitFlow\Command\CommandProvider;

class Plugin implements PluginInterface, Capable
{
    /**
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Returns the class name for plugins command provider
     *
     * @return array
     */
    public function getCapabilities()
    {
        return [
            'Composer\Plugin\Capability\CommandProvider' => CommandProvider::class,
        ];
    }
}
