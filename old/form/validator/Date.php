<?php
class Validator_Date{
	public function validate($value, $options = []){
		$error = false;
		$dateArray = explode('-', $value);
		$value = Data::toDate($value);
		$dateArrayCount = count($dateArray);

		if($dateArrayCount < 3
		|| $dateArrayCount > 3
		|| checkdate($dateArray[1], $dateArray[2], $dateArray[0]) === false){
			$error = 'errorDate';
		}
		elseif(isset($options['min']) === true
		&& $value < $options['min']){
			$error = 'errorDateMin';
		}
		elseif(isset($options['max']) === true
		&& $value > $options['max']){
			$error = 'errorDateMax';
		}

		return $error;
	}
}
?>