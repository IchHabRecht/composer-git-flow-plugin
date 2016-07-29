<?php
namespace IchHabRecht\GitFlow\Command;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;

class CommandProvider implements CommandProviderCapability
{
    /**
     * Returns new command instances
     *
     * @return array
     */
    public function getCommands()
    {
        return [
            new GitFlowUpdateCommand(),
        ];
    }
}
