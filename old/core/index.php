<?php
include $_SERVER['DOCUMENT_ROOT'] . '/core/Initializer.php';
Initializer::core();
Initializer::app();

if(Data::config('main', 'dev') === false){
	ini_set("display_errors", 0);
}

$request = Request::setRoute($_SERVER['REQUEST_URI']);

try{
	$request->dispatch();
	
	if($request->module->controller->view->isEmpty() === false){
		echo $request->module->controller->view->getContent();
	}
}
catch(Exception $ex){
	echo $ex;
}
?>