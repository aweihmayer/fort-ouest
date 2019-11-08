<?php
namespace helper;

class StringHelper {
	CONST SALT = 'G8jQ01MiiZz4';
	CONST DATE_FORMAT = 'Y-m-d';
	
	public static function hashWithSalt(string $string, string $salt = ''): string {
	    return sha1($string . $salt . self::SALT);
	}
	
	public static function random(int $length = 10): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		
		return $randomString;
	}

	public static function toDate(string $date): string {
		$time = strtotime($date);
		$date = date(self::DATE_FORMAT, $time);
		
		return $date;
	}
	
	public static function toCurrency($price, bool $noDecimals = false): string {
		if(is_string($price)){
			$price = t($price);
		} else if(is_numeric($price)){
			if($noDecimals === false){
				$price = number_format($price, 2, '.', ',');
			}
			
			$price = '$' . $price;
		}
		
		return $price;
	}
}