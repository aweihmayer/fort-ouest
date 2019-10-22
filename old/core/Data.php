<?php
Data::init();

class Data{
	protected static $_config = [];

	public static function init(){		
		Data::config('main');

		if(empty(self::$_config['main']['locales']) === true){
			self::$_config['main']['locales'] = ['index'];
		}
	}
	
	public static function config($name, $key = null){
		if(isset(self::$_config[$name]) === false){
			self::$_config[$name] = json_decode_file(PATH_CONFIG . $name);
		}

		return (isset($key) === true) ? self::$_config[$name][$key] : self::$_config[$name];
	}
}
?>