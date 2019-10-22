<?php
use PeazyPhp\Request;
use PeazyPhp\Response;
use PeazyPhp\Router;
use PeazyPhp\Content;
use PeazyPhp\Localizer;

use helper\loader\ContactLoader;
use helper\loader\ImageLoader;
use helper\loader\NavLoader;

// TODO make response object

class RequestHandler {
    public static $request;
    public static $router;

    public static function execute(): void {
        self::init();
        self::buildRequest();
    }

    private static function init(): void {
        session_start();

        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
        define('APP_PATH', ROOT . '/app/');
        define('LIB_PATH', ROOT . '/lib/');

        require LIB_PATH . 'autoload.php';
    }

    public static function buildRequest(): void {
        $response = new Response();

        global $router;
        $router = new Router(APP_PATH . 'config/routes.xml');
        $route = $router->findRoute($_SERVER['REQUEST_URI']);

        if(!$route) {
            $firstPath = explode('/', $_SERVER['REQUEST_URI'])[0];
            $route = [
                'module' => 'index',
                'controller' => '',
                'action' => '',
                'locale' => ($firstPath == 'en') ? 'en' : 'fr'];
            $response->setCode(404);
        }

        $route['altLocale'] = $route['locale'] == 'fr' ? 'en' : 'fr';
        $request = new Request($route);
        define('MODULE_PATH', APP_PATH . 'modules/' . $request->module . '/');
        define('CONTROLLER_PATH', MODULE_PATH . 'controllers/' . $request->controller . '/');

        self::loadLocales($request);

        if(isset($route['code'])) {
            $response->setCode((int) $route['code']);

            if($route['code'] == '301') {
                $response->setRedirection(u($request->id));
            }
        }

        $controller = self::createController($request);
        $response->setResponse(self::createContent($request, $controller)->render());

        $response->send();
    }

    private static function createController(Request $request) {
        require CONTROLLER_PATH . 'controller.php';
        $controller = str_replace('-', '_', $request->controller . 'Controller');
        $controller = new $controller();
        $controller->request = $request;
        $controller->view =  new stdClass();
        $controller->view->request = $request;
        if(method_exists($controller, 'init')) {
            $controller->init();
        }

        $actionMethod = $request->action . 'Action';
        $controller->$actionMethod();

        return $controller;
    }

    private static function createContent(Request $request, $controller): Content {
        $view = new Content(CONTROLLER_PATH . '/views/' . $request->action . '.phtml');
        $view->applyModel($controller->view);

        $layout = new Content(
            APP_PATH . 'html/layout.phtml',
            [
                'view' => $view,
                'request' => $request,
                'contact' => ContactLoader::load(APP_PATH . 'data/contacts.xml', $request->locale)->fortOuest,
                'images' => ImageLoader::load(APP_PATH . 'data/images.xml', $request->locale),
                'nav' => NavLoader::load(APP_PATH . 'data/nav.xml', $request)
            ]
        );

        Content::setBasePath(APP_PATH . 'html/');

        return $layout;
    }

    private static function loadLocales(Request $request): void {
        global $localizer;
        $l = $request->locale;
        $localizer = new Localizer($l);

        $localizer->load([
            APP_PATH . 'locale/' . $l . '.json',
            MODULE_PATH . 'locale/' . $l . '.json',
            CONTROLLER_PATH . 'locale/' . $l . '.json',
            CONTROLLER_PATH . 'locale/' . $l . '-' . $request->action . '.json']);
    }
}

$localizer;
$router;

RequestHandler::execute();

function t(string $k, array $replace = []) {
    global $localizer;
    return $localizer->get($k, $replace);
}

function u(string $id) {
    global $router;
    global $localizer;
    return $router->findPath($id, $localizer->getLocale());
}