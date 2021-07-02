<?php
namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class AuthController extends BaseController {
    public function getLogin() {
        return $this->renderHTML('login.twig');
    }

    public function postLogin(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $responseMessage = null;

        $user = User::where('email', $postData['email'])->first();
        if($user) {
            if (password_verify($postData['password'], $user->password)) {
                $_SESSION['userId'] = $user->id;
                return new RedirectResponse('/personal/admin');
            } else {
                $responseMessage = 'Bad credentials';
            }
        } else {
            $responseMessage = 'Bad credentials';
        }

        return $this->renderHTML('login.twig', [
            'responseMessage' => $responseMessage
        ]);
    }

    public function getLogout() {
        unset($_SESSION['userId']);
        return new RedirectResponse('/personal/login');
    }

    public function getChangePass() {
        return $this->renderHTML('changePass.twig');
    }

    public function updatePass(ServerRequest $request){
        $postData = $request->getParsedBody();
        $responseMessage = null;
        $user = User::where('email', $postData['email'])->first();
        if($user){
            if($postData['password']===$postData['confirmPassword']){
                $user->update(['password' => password_hash($postData['password'], PASSWORD_DEFAULT)]);  
                $responseMessage = 'Password updated';
                return new RedirectResponse('/personal/login');

            } else{
                $responseMessage = 'Password & confirm password not match';
                return $this->renderHTML('changePass.twig', [
                    'responseMessage' => $responseMessage
                ]);
    
            }

        } else {
            $responseMessage = 'Password change unsuccessful';
            return $this->renderHTML('changePass.twig', [
                'responseMessage' => $responseMessage
            ]);

        }
        

    }
}