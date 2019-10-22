<?php
class Validator_Required{
	public function validate($value, $options = []){
		$error = false;

		if(empty_allow_zero($value) === true){
			$error = 'errorRequired';
		}

		return $error;
	}
}
?>