<?php
class Validator_Length{
	public function validate($value, $options = []){
		$error = false;
		$length = strlen($value);
		
		if(isset($options['min']) === true
		&& $length < $options['min']){
			$error = 'errorLengthMin';
		}
		elseif(isset($options['max']) === true
		&& strlen($value) > $options['max']){
			$error = 'errorLengthMax';
		}
		
		return $error;
	}
}
?>