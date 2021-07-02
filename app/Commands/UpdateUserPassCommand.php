<?php
namespace App\Commands;

use App\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserPassCommand extends Command
{
    protected static $defaultName = 'app:updateUser-pass';
    protected function configure()
    {
    	// Aquí va la configuración
    $this->addArgument('email', InputArgument::REQUIRED, 'The email of the user.');
    $this->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');
    
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	// Aquí va el código del comando
    $output->writeln([
        'updating password',
       '============',
       '',
    ]);
    $user = User::where('email',$input->getArgument('email'));
    $user->update(['password' => password_hash($input->getArgument('password'), PASSWORD_DEFAULT)]);   
    $output->writeln('Done.');
    return Command::SUCCESS;   
    }
    
}
