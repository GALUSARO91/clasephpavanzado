<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use Laminas\Diactoros\ServerRequest;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Zend\Diactoros\Response\RedirectResponse;

class contactController extends BaseController{

    public function getIndex(){
        return $this->renderHTML('contacts/index.twig');
    }

    public function sendMessage(ServerRequest $request){
        $requestData = $request->getParsedBody();
        $transport = (new Swift_SmtpTransport($_ENV['SMTP_HOST'], $_ENV['SMTP_PORT']))
        ->setUsername($_ENV['SMTP_USER'])
        ->setPassword($_ENV['SMTP_PASS']);
 
        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();
        $message->setSubject('Demo message using the SwiftMailer library.')
                ->setFrom(['sender@gmail.com' => 'sender name'])
                ->addTo('recipient@gmail.com','recipient name')
                ->setBody("you have a message from {$requestData['name']} <{$requestData['email']}>. Message:{$requestData['message']} \nThanks,\nAdmin");
        $result = $mailer->send($message);
        return new RedirectResponse('/personal/');
    }
}