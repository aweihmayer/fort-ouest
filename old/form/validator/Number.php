<?php
class Validator_Number{
	public function validate($value, $options = []){
		$error = false;

		if(is_numeric($value) === false){
			$error = 'errorNumber';
		}
		elseif(isset($options['min']) === true
		&& $value < $options['min']){
			$error = 'errorNumberMin';
		}
		elseif(isset($options['max']) === true
		&& $value > $options['max']){
			$error = 'errorNumberMax';
		}
		
		return $error;
	}
}
?>