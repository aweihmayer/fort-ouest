<?php
class Module{
	public $controller = null;
	
	public function getName(){
		$name = get_class($this);
		$name = explode('_', $name);
		return $name[1];
	}

	public function getControllerPath(){ return PATH_APP_CONTROLLER . lcfirst($this->getName()) . '/'; }
	
	public function getControllersNames(){ return scandir_names($this->getControllerPath()); }

	public function hasController($controller){ return file_exists($this->getControllerPath() . ucfirst($controller)); }
	
	public static function exists($module){ return file_exists(PATH_APP_MODULE . ucfirst($module) . '.php'); }
}
?>