<?php
use PeazyPhp\Request;
use PeazyPhp\Response;
use PeazyPhp\Router;
use PeazyPhp\Content;
use PeazyPhp\Localizer;

use helper\loader\ContactLoader;
use helper\loader\ImageLoader;
use helper\loader\NavLoader;

class RequestHandler {
    public static function execute(): void {
        self::init();
        self::buildRequest();
    }

    // Start session, declare constants, import essential files
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

        try {
            $route = $router->findRoute($_SERVER['REQUEST_URI']);
        } catch(Exception $ex) {
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
        $view = self::createContent($request, $controller->view);
        $response->setResponse($view->render());

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

    private static function createContent(Request $request, stdClass $model): Content {
        $view = new Content(CONTROLLER_PATH . '/views/' . $request->action . '.phtml');
        $view->applyModel($model);

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

function u(string $id, string $locale = null, array $params = []) {
    global $router;
    global $localizer;
    return $router->findPath(
        $id,
        isset($locale) ? $locale : $localizer->getLocale(),
        $params);
}