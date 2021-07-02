<?php


require_once '../vendor/autoload.php';
// use Symfony\Component\Dotenv\Dotenv;

session_start();

$env_addr = dirname(__DIR__,1);



$dotenv = Dotenv\Dotenv::createImmutable($env_addr);
$dotenv->load();

if( $_ENV['DEBUG'] === 'true'){

    ini_set('display_errors', 1);
    ini_set('display_starup_error', 1);
    error_reporting(E_ALL);

}

use App\Middlewares\AuthenticationMiddleware;
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use Laminas\Diactoros\Response;
//use HttpSoft\Emitter\SapiEmitter;
// use Psr\Http\Message\ResponseInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\Diactoros\ServerRequestFactory;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\LaminasEmitterMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__.'/../logs/app.log', Logger::WARNING));

$container = new DI\Container();

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

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index', '/personal/', [
    'App\Controllers\IndexController',
    'indexAction'
]);
$map->get('indexJobs', '/personal/jobs', [
    'App\Controllers\JobsController',
    'indexAction'
]);
$map->get('deleteJobs', '/personal/jobs/{id}/delete', [
    'App\Controllers\JobsController',
    'deleteAction'
]);
$map->get('addJobs', '/personal/jobs/add', [
    'App\Controllers\JobsController',
    'getAddJobAction'
]);
$map->post('saveJobs', '/personal/jobs/add', [
    \App\Controllers\JobsController::class,
    'getAddJobAction'
]);
$map->get('addUser', '/personal/users/add', [
    'App\Controllers\UsersController',
    'getAddUser'
]);
$map->post('saveUser', '/personal/users/save', [
    'App\Controllers\UsersController',
    'postSaveUser'
]);
$map->get('loginForm', '/personal/login', [
    'App\Controllers\AuthController',
    'getLogin'
]);
$map->get('changePass', '/personal/changepass', [
    'App\Controllers\AuthController',
    'getChangePass'
]);

$map->post('updatePass', '/personal/changepass', [
    'App\Controllers\AuthController',
    'updatePass'
]);
$map->get('logout', '/personal/logout', [
    'App\Controllers\AuthController',
    'getLogout'
]);
$map->post('auth', '/personal/auth', [
    'App\Controllers\AuthController',
    'postLogin'
]);
$map->get('admin', '/personal/admin', [
    'App\Controllers\AdminController',
    'getIndex'
]);
$map->get('conntactForm', '/personal/contact', [
    'App\Controllers\ContactController',
    'getIndex'
]);

$map->post('conntactSend', '/personal/contact/send', [
    'App\Controllers\ContactController',
    'sendMessage'
]);


$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

try {
    $harmony = new Harmony(ServerRequestFactory::fromGlobals(), new Response());
$harmony
//LaminasEmitterMiddleware
    ->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()));
    if($_ENV['DEBUG'] === 'true'){
        $harmony->addMiddleware(new \Franzl\Middleware\Whoops\WhoopsMiddleware);
    }
    // 
$harmony
    ->addMiddleware(new Middlewares\AuraRouter($routerContainer))
    ->addMiddleware(new AuthenticationMiddleware())
    ->addMiddleware(new DispatcherMiddleware($container, 'request-handler'))
    ->run();
 } catch (\Exception $e) {
   $log->warning($e->getMessage());
   $emitter = new SapiEmitter();
   $emitter->emit(new Response\EmptyResponse(400));
} catch(\Error $e){
    $log->error($e->getMessage());
    $emitter = new SapiEmitter();
    $emitter->emit(new Response\EmptyResponse(500));
} 

