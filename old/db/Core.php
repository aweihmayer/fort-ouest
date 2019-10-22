<?php
class Db_Core{
	protected static $_con = null;
	protected static $_tables = [];
	
/**********************************************************************************************
								Connection
**********************************************************************************************/
	
	public static function connect(){
		$config = Data::config('main', 'db');
		
		if(empty($config) === false){
			self::$_con = new PDO(
				'mysql:host=' . $config['server'] .
				';dbname=' . $config['db'],
				$config['user'],
				$config['password']
			);

			self::$_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$_con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}
	}
	
	public static function disconnect(){ self::$_con = null; }
	
	public static function getConnection(){ return self::$_con; }
	
/**********************************************************************************************
								Table
**********************************************************************************************/
	
	public static function table($name){
		if(isset(self::$_tables[$name]) === false){
			include_once Data::config('db', '');
			$class = 'Table_' . $name;
			self::$_tables[$name] = new $class();
		}
		
		return self::$_tables[$name];
	}
}
?>