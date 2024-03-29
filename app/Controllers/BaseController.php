<?php

namespace App\Controllers;

use \Twig_Loader_Filesystem;
use Zend\Diactoros\Response\HtmlResponse;
class BaseController {
    protected $templateEngine;

    public function __construct() {
        $loader = new \Twig\Loader\FilesystemLoader('C:\xampp\htdocs\personal\views');
        $this->templateEngine = new \Twig\Environment($loader, array(
            'debug' => true,
            'cache' => false,
        ));
    }

    public function renderHTML($fileName, $data = []) {
        return new HtmlResponse($this->templateEngine->render($fileName, $data));
    }
}