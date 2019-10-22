<?php
class Validator_Email{
	public function validate($value, $options = []){
		$error = false;

		if(filter_var($value, FILTER_VALIDATE_EMAIL) === false){
			$error = 'errorEmail';
		}

		return $error;
	}
}
?>