<?php
T::init();

class T{	
	private static $_values = [];
	private static $_routes = [];
	private static $_locale = 'index';
	
	public static function init(){
		$locales = Data::config('main', 'locales');

		foreach($locales as $l){
			self::$_values[$l] = [];
			self::$_routes[$l] = json_decode_file(PATH_APP_LOCALE . $l . '-routes');
		}
	}
	
	public static function addFromRoute($route){
		$files = [];

		$path = $route['module'] . '/';
		$files[] = $path;
		$path .= $route['controller'] . '/';
		$files[] = $path;
		$files[] = $path . $route['action'];

		self::add($files);
    }

	public static function add($files){
		$locale = self::getLocale();
		
		if(is_array($files) === false){
			$files = [$files];
		}
		
		foreach($files as $f){
			$f = explode('/', $f);
			$lastIndex = count($f) - 1;

			if(empty($f[$lastIndex]) === false){
				$f[$lastIndex] = '-' . $f[$lastIndex];
			}
			$f[$lastIndex] = $locale . $f[$lastIndex];
			$f = PATH_APP_LOCALE . implode('/', $f);

			if(file_exists($f . '.json') === true){
				self::$_values[$locale] = array_merge(
					self::$_values[$locale],
					json_decode_file($f)
				);
			}
		}
	}

/**********************************************************************************************
								Locale
**********************************************************************************************/
	
	public static function setLocale($locale){ 
		self::$_locale = $locale;
		
		if(empty(self::getAllForLocale()) === true){
			self::add('');
		}
	}
	
	public static function getLocale(){ return self::$_locale; }
	
/**********************************************************************************************
								Values
**********************************************************************************************/
	
	public static function get($key, $replace = []){		
		if(isset(self::$_values[self::getLocale()][$key]) === true){
			$value = self::$_values[self::getLocale()][$key];
			
			if(empty($replace) === false){
				$value = str_replace(
					array_keys($replace),
					array_values($replace),
					$value
				);
			}
			
			$value = toHtmlSafe($value);
		}
		else{
			$value = $key;
		}
		
		return $value;
	}
	
	public static function getAllForLocale(){ return self::$_values[self::getLocale()]; }
	
	public static function getAll(){ return self::$_values; }
	
/**********************************************************************************************
								URL
**********************************************************************************************/

	public static function url($route = [], $query = []){
		if(is_array($route) === true){
			$route = array_merge(Request::getRoute(), $route);
			$routes = self::getRoutes($route['locale']);

			foreach($route as $component => $v){
				if($component !== 'locale'
				&& $v !== 'index'
				&& isset($routes[$v]) === true){
					$route[$component] = $routes[$v];
				}
			}

			$route = implode('/', [
				'',
				$route['locale'],
				$route['module'],
				$route['controller'],
				$route['action']
			]);
		}
		
		if(empty($query) === false){
			$route .= '?' . http_build_query($query);
		}

		return str_replace('/index', '', $route);
	}
	
	public static function getRoutes($locale = null){ return (isset($locale) === false) ? self::$_routes[self::getLocale()] : self::$_routes[$locale]; }
}
?>