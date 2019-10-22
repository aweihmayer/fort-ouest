<?php
class Request{
	private static $_route = [];
	public $module = null;

	public static function setRoute($route, $newRequest = true){
		self::$_route = (is_string($route) === true) ? self::_parseUrl($route) : $route;		
		return ($newRequest === true) ? new Request() : null;
	}
	
	public function __construct(){ $this->_build(); }
	
	private function _build(){
		$route = self::getRoute();

		if(in_array(false, $route) === false){
			$moduleClass = 'Module_' . $route['module'];
			$controllerClass = 'Controller_' . $route['module'] . '_' . $route['controller'];

			$module = new $moduleClass();
			$module->controller = new $controllerClass();

			if($module->controller !== false
			&& $module->controller->hasAction($route['action']) === true){
				$module->controller->view = new View();
				$module->controller->view->setViewFromRoute($route);
				
				$this->module = $module;
			}
		}
	}
	
	public function isValid(){ return empty($this->module) === false; }
	
	public function dispatch(){		
		if($this->isValid() === true){
			$executed = true;

			$route = self::getRoute();
			T::addFromRoute($route);

		// Init

			if(method_exists($this->module, 'init') === true){ 				$this->module->init(); }

			if(method_exists($this->module->controller, 'init') === true){ 	$this->module->controller->init(); }

		// Action

			$action = 'action' . ucfirst($route['action']);
			$this->module->controller->$action();

		// Render

			$this->module->controller->view->render();

		// Finalize

			if(method_exists($this->module, 'fin') === true){ 				$this->module->fin(); }

			if(method_exists($this->module->controller, 'fin') === true){ 	$this->module->controller->fin(); }
		}
		else{
			throw new Exception_RequestNotFound();
		}
	}

/**********************************************************************************************
								Route
**********************************************************************************************/
	
	public static function getRoute(){ return $route = self::$_route; }

	public static function getLocale(){ return self::$_route['locale']; }
	
	public static function getModule(){ return self::$_route['module']; }
	
	public static function getController(){ return self::$_route['controller']; }
	
	public static function getAction(){ return self::$_route['action']; } 

	private static function _parseUrl($url){
		if($url === '/'){
			$url = '';
		}
		
		$url = explode('?', $url)[0];
		$url = explode('/', $url);
		array_shift($url);

		$locales = Data::config('main', 'locales');
		$route = [
			'locale' => $locales[0],
			'module' => 'index',
			'controller' => 'index',
			'action' => 'index'
		];
		
	// Translate
		
		if(empty($url) === false
		&& in_array($url[0], $locales) === true){
			$route['locale'] = array_shift($url);
		}
		
		T::setLocale($route['locale']);
		
		$translations = array_flip(T::getRoutes());
		
		foreach($url as $i => $u){			
			if(isset($translations[$u]) === true){
				$url[$i] = $translations[$u];
			}
		}

	// Build route

		if(isset($url[0]) === true
		&& Module::exists($url[0]) === true){
			$route['module'] = array_shift($url);
		}
		
		if(isset($url[0]) === true
		&& Controller::exists($route['module'], $url[0]) === true){
			$route['controller'] = array_shift($url);
		}
		
		if(isset($url[0]) === true){
			$route['action'] = array_shift($url);
		}

		if(empty($url) === false){ 
			$route = [
				'locale' => $route['locale'],
				'module' => false,
				'controller' => false,
				'action' => false,
			];
		}
		
		return $route;
	}
	
	public static function redirect($url = [], $query = [], $continue = false){
		header('location: ' . T::url($url, $query));
		if($continue === false){ exit(); }
	}
}
?>