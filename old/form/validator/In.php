<?php
class Validator_In{
	public function validate($value, $options = []){
		$error = false;

		if(in_array($value, $options['values']) === false){
			$error = 'errorInvalid';
		}

		return $error;
	}
}
?>