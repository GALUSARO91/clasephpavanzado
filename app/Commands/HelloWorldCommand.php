<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command {

    protected static $defaultName = 'app:hello-world';

    protected function execute(InputInterface $input, OutputInterface $output): int {

        $output->writeln('Hello '.$input->getArgument('name'));
        
        return Command::SUCCESS;
    }

    protected function configure(): void {

        $this->addArgument('name', InputArgument::REQUIRED, 'The username of the user.');
      
    }
    
}

