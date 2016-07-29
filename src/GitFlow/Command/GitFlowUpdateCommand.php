<?php
namespace IchHabRecht\GitFlow\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitFlowUpdateCommand extends BaseCommand
{
    /**
     * Sets the name of this command
     */
    protected function configure()
    {
        $this->setName('git-flow-update');
    }

    /**
     * Executes the command and updates repositories
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Executing');

        return 0;
    }
}
