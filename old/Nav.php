<?php
class Helper_Nav{
	protected static $_nav1 = [
		'home' => ['controller' => 'index', 'action' => 'index'],
		'activities' => [
			'activities' => ['controller' => 'index', 'action' => 'activities'],
			'paintball' => ['controller' => 'paintball', 'action' => 'index'],
			'airsoft' => ['controller' => 'airsoft', 'action' => 'index'],
			'dogSledding' => ['controller' => 'dogSledding', 'action' => 'index'],
			'snowmobile' => ['controller' => 'snowmobile', 'action' => 'index'],
			'atvShort' => ['controller' => 'atv', 'action' => 'index'],
			'survival' => ['controller' => 'survival', 'action' => 'index'],
		],
		'receptions' => ['controller' => 'index', 'action' => 'receptions'],
		'restaurant' => ['controller' => 'index', 'action' => 'restaurant'],
		'partners' => ['controller' => 'index', 'action' => 'partners'],
		'contact' => ['controller' => 'index', 'action' => 'contact']
	];
	
	protected static $_nav2 = [
		'paintball' => [
			'info' => ['action' => 'index'],
			'prices' => ['action' => 'prices'],
			'members' => ['action' => 'members'],
			'photos' => ['action' => 'photos'],
			'book' => ['action' => 'book']
		],
		'airsoft' => [
			'info' => ['action' => 'index'],
			'prices' => ['action' => 'prices'],
			'members' => ['action' => 'members'],
			'photos' => ['action' => 'photos'],
			'book' => ['action' => 'book']
		],
		'snowmobile' => [
			'info' => ['action' => 'index'],
			'prices' => ['action' => 'prices'],
			'photos' => ['action' => 'photos'],
			'book' => ['action' => 'book']
		],
		'atv' => [
			'info' => ['action' => 'index'],
			'photos' => ['action' => 'photos'],
			'book' => ['action' => 'book']
		],
		'dogSledding' => [
			'info' => ['action' => 'index'],
			'packages' => ['action' => 'packages'],
			'photos' => ['action' => 'photos'],
			'book' => ['action' => 'book']
		],
		'survival' => [
			'info' => ['action' => 'index'],
			'packages' => ['action' => 'packages'],
			'photos' => ['action' => 'photos'],
			'book' => ['action' => 'book']
		]
	];
	
	public static function getPrimary(){ return self::$_nav1; }
	
	public static function getSecondary(){
		$controller = Request::getController();
		
		return (self::hasSecondary() === true) ? self::$_nav2[$controller] : [];
	}
	
	public static function hasSecondary(){
		$controller = Request::getController();
		
		return (isset(self::$_nav2[$controller]) === true);
	}
}
?>