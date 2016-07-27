<?php
namespace IchHabRecht\GitFlow;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;

class Plugin implements EventSubscriberInterface, PluginInterface
{

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Returns an array of event names this subscriber wants to listen to
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'pre-install-cmd' => 'adjustGitFlowDependencies',
            'pre-update-cmd' => 'adjustGitFlowDependencies',
        ];
    }

    public static function adjustGitFlowDependencies(Event $event)
    {

    }
}
