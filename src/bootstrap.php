<?php declare(strict_types=1);

use App\Page\Controller;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

const ROOT_DIR = __DIR__ . '/../';

require ROOT_DIR . '/vendor/autoload.php';

$injector = include(ROOT_DIR . '/src/dependencies.php');

$config = $injector->make(\App\Configuration::class);
if (intval($config->get('debug')) === 1) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
    Tracy\Debugger::enable();
}



$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$session = $injector->make(Symfony\Component\HttpFoundation\Session\Session::class);
$session->start();
$request->setSession($session);

$dispatcher = simpleDispatcher(
    function (RouteCollector $r) {
        $routes = include(ROOT_DIR . '/src/routes.php');
        foreach ($routes as $route) {
            $r->addRoute(...$route);
        }
    }
);

$routeInfo = $dispatcher->dispatch(
    $request->getMethod(),
    $request->getPathInfo()
);


//single controller
$controller = $injector->make(Controller::class);

if($controller->isAuth($request->getSession()) || $routeInfo[1] === 'App\Page\Controller#api'){

    switch ($routeInfo[0]) {
        case Dispatcher::NOT_FOUND:
            $response = $controller->showErrorPage('404 Not found', 404);
            break;
        case Dispatcher::METHOD_NOT_ALLOWED:
            $response = $controller->showErrorPage('405 Method not allowed', 405);
            break;
        case Dispatcher::FOUND:
            [$controllerName, $method] = explode('#', $routeInfo[1]);
            $vars = $routeInfo[2];
            $controller = $injector->make($controllerName);

            try {
                $response = $controller->$method($request, $vars);
            } catch (Exception $e) {
                $response = $controller->showErrorPage($e->getMessage(), 404);
            }

            break;
    }
} else {
    $response = $controller->authentication($request, $config);
}




//
$response->prepare($request);
$response->send();
