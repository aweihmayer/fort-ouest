<?php
class Controller{
	public $view = null;

	public function getName(){
		$name = get_class($this);
		$name = explode('_', $name);
		return $name[2];
	}
	
	public function getActions(){
		$actions = [];

		foreach(get_class_methods($this) as $i => $func){
			if(strpos($func, 'action') === 0){
				$actions[] = substr($func, 0, 6);
			}
		}

		return $actions;
	}
	
	public function hasAction($action){ return method_exists($this, 'action' . ucfirst($action)); }
	
	public static function exists($module, $controller){ return file_exists(PATH_APP_CONTROLLER . lcfirst($module) . '/' . ucfirst($controller) . '.php'); }
}
?>