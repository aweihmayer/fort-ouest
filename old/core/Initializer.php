<?php
/**********************************************************************************************
								Paths
**********************************************************************************************/

session_start();

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('PATH_CORE', ROOT . '/core/');
define('PATH_APP', ROOT . '/app/');
	define('PATH_APP_LOCALE', PATH_APP . 'locale/');
	define('PATH_APP_MODULE', PATH_APP . 'modules/');
	define('PATH_APP_CONTROLLER', PATH_APP . 'controllers/');
	define('PATH_APP_VIEW', PATH_APP . 'views/');
	define('PATH_APP_LAYOUT', PATH_APP . 'layouts/');
	define('PATH_APP_PARTIAL', PATH_APP . 'partials/');
	define('PATH_APP_LIBRARY', PATH_APP . 'library/');
define('PATH_CACHE', ROOT . '/cache/');
	define('PATH_CACHE_DATA', PATH_CACHE . 'data/');
	define('PATH_CACHE_VIEW', PATH_CACHE . 'views/');
define('PATH_CONFIG', ROOT . '/config/');
define('PATH_PUBLIC', ROOT . '/public/');

/**********************************************************************************************
								Initializer
**********************************************************************************************/

class Initializer{
	public static function core(){
		$filesToRequire = [
			'functions', 'Data', 'Cache', 'Translator',
			'Partial', 'Layout', 'View',
			'Module', 'Controller',
			'Request'
		];

		foreach($filesToRequire as $file){
			require PATH_CORE . $file . '.php';
		}
	}

	public static function app(){
		require PATH_APP . 'custom.php';
		require PATH_APP . '404.php';
	}
}

/**********************************************************************************************
								Autoload
**********************************************************************************************/

function _autoload($className){
	$parts = explode('_', $className);
	$file = ucfirst(array_pop($parts)) . '.php';
	$parts = array_map('strtolower', $parts);
	$type = array_shift($parts);
	$path = (empty($parts) === false) ? implode('/', $parts) . '/' : '';
	
	switch($type){
		case 'module':
			$base = PATH_APP_MODULE;
			break;
		case 'controller':
			$base = PATH_APP_CONTROLLER;
			break;
		default:
			if(empty($type) === false){
				$path = $type . '/' . $path;
			}
			
			$base = PATH_APP_LIBRARY;
	}

	include $base . $path . $file;
}

spl_autoload_register('_autoload');
?>