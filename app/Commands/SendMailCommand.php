<?php
namespace App\Commands;

use App\Models\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class SendMailCommand extends Command 
{

    protected static $defaultName = 'app:send-mail';

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $pending_message = Message::where('sent',false)->first();
        $transport = (new Swift_SmtpTransport($_ENV['SMTP_HOST'], $_ENV['SMTP_PORT']))
        ->setUsername($_ENV['SMTP_USER'])
        ->setPassword($_ENV['SMTP_PASS']);
 
        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();
        $message->setSubject('Demo message using the SwiftMailer library.')
                ->setFrom(['sender@gmail.com' => 'sender name'])
                ->addTo('recipient@gmail.com','recipient name')
                ->setBody("you have a message from {$pending_message->name} <{$pending_message->email}>. Message:{$pending_message->message} \nThanks,\nAdmin");
        $result = $mailer->send($message);
        $pending_message->sent = true;
        $pending_message->save();
        
        return Command::SUCCESS;
    }

    
}
