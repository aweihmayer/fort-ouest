<?php
class Cache{
/**********************************************************************************************
								Set
**********************************************************************************************/

	public static function set($name, $content){ 
		self::_set(
			PATH_CACHE_DATA . $name,
			serialize($content)
		);
	}

	public static function setView($file, $locale, $content){
		self::_set(
			PATH_CACHE_VIEW . $locale . '-' . str_replace('/', '-', $file),
			$content
		);
	}
	
	private static function _set($file, $content){
		file_put_contents(
			$file . '.txt',
			$content
		);
	}

/**********************************************************************************************
								Get
**********************************************************************************************/
	
	public static function get($name){ 
		return unserialize(
			self::_get(
				PATH_CACHE_DATA . $name,
				Data::config('main', 'timeCacheData')
			)
		);
	}
	
	public static function getView($file, $locale){
		return self::_get(
			PATH_CACHE_VIEW . $locale . '-' . str_replace('/', '-', $file),
			Data::config('main', 'timeCacheView')
		);
	}
	
	private static function _get($file, $expiration){
		$file .= '.txt';
		return (self::isFresh($file, $expiration) === true) ?  file_get_contents($file) : false;
	}
	
/**********************************************************************************************
								Delete
**********************************************************************************************/
	
	public static function delete($name){
		self::_delete(PATH_CACHE_DATA . $name);
	}
	
	public static function deleteView($route){
		self::_delete(PATH_CACHE_VIEW . implode('-', $route));
	}
	
	private static function _delete($file){
		unlink($file . '.txt');
	}
	
/**********************************************************************************************
								Flag
**********************************************************************************************/

	public static function isFresh($file, $expiration){
		return (
			file_exists($file) === true 
			&& filemtime($file) > (time() - $expiration)
		);
	}
}
?>