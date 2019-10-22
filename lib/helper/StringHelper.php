<?php
namespace helper;

class StringHelper {
	protected static $_salt = 'G8jQ01MiiZz4';
	
	public static function hashWithSalt($string, $salt = ''){ return sha1($string . $salt . self::getSalt()); }
	
	public static function random($length = 10){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		
		return $randomString;
	}
	
	public static function getSalt(){ return self::$_salt; }
	
	public static function toDate($date){
		$time = strtotime($date);
		$date = date('Y-m-d', $time);
		
		return $date;
	}
	
	public static function toCurrency($price, $noDecimals = false){
		if(is_string($price) === true){
			$price = t($price);
		}
		else if(is_numeric($price) === true){
			if($noDecimals === false){
				$price = number_format($price, 2, '.', ',');
			}
			
			$price = '$' . $price;
		}
		
		return $price;
	}
}
?>