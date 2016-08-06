<?php
namespace IchHabRecht\GitFlow\Command;

use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitFlowUpdateCommand extends UpdateCommand
{
    /**
     * Sets the name of this command
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('git-flow-update');
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
        $output->writeln('Executing');
        
        return parent::execute($input, $output);
    }
}
