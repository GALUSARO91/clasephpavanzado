#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


use App\Commands\SendMailCommand;
use App\Commands\HelloWorldCommand;
use Symfony\Component\Console\Application;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => $_ENV['DB_DRIVER'],
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_NAME'],
    'username'  => $_ENV['DB_USER'],
    'password'  => $_ENV['DB_PASS'],
    'charset'   => 'utf8',    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'port'      => $_ENV['DB_PORT']
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$application = new Application();

// ... register commands
$application->add(new HelloWorldCommand());
$application->add(new SendMailCommand());

$application->run();