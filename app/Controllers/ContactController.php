<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use Laminas\Diactoros\ServerRequest;

class contactController extends BaseController{

    public function getIndex(){
        return $this->renderHTML('contacts/index.twig');
    }

    public function sendMessage(ServerRequest $request){
        var_dump($request->getParsedBody());
    }
}