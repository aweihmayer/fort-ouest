<?php
/**********************************************************************************************
								File
**********************************************************************************************/

function scandir_clean($path){
	$files = scandir($path);	
	return array_diff($files, ['.', '..']);
}

function scandir_names($path){
	$files = scandir_clean($path);
	
	foreach($files as $i => $v){
		$files[$i] = remove_file_ext($v);
	}
	
	return $files;
}

function remove_file_ext($file){
	$file = explode('.', $file);
	array_pop($file);
	return implode('.', $file);
}

function json_decode_file($file, $assoc = true){
	return json_decode(file_get_contents($file . '.json'), $assoc);
}

/**********************************************************************************************
								Flag
**********************************************************************************************/

function empty_allow_zero($value){
	return (empty($value) === true
		&& $value !== 0
		&& $value !== '0'
	);
}

/**********************************************************************************************
								Class
**********************************************************************************************/

function get_public_vars($obj){ return get_object_vars($obj); }

/**********************************************************************************************
								String
**********************************************************************************************/

$_htmlChars = array_diff_key(
	get_html_translation_table(HTML_ENTITIES),
	array_flip(['>', '<', '"'])
);
$_htmlChars = [
	'unsafe' => array_keys($_htmlChars),
	'safe' => array_values($_htmlChars)
];
function toHtmlSafe($value){
	global $_htmlChars;
	
	if(is_array($value) === true){
		foreach($value as $k => $v){
			$value[$k] = toHtmlSafe($v);
		}
	}
	else{
		$value = str_replace(
			$_htmlChars['unsafe'],
			$_htmlChars['safe'],
			$value
		);
	}
	
	return $value;
}
	
/**********************************************************************************************
								Array
**********************************************************************************************/
	
function array_merge_duplicate(){
	$new = [];
	$arrays = func_get_args();
	
	foreach($arrays as $a){
		foreach($a as $k => $v){
			if(isset($new[$k]) === false){
				$new[$k] = [];
			}
			
			$new[$k][] = $v;
		}
	}
	
	return $new;
}

function get_by_index($value, $i){
	return (is_null($i) === false && is_array($value) === true) ? $value[$i] : $value;
}

function array_intersect_key2($ar, $keys){
	return array_diff_key($array, array_flip($keys));
}

function array_remove(&$ar, $k) {
	$val = null;
	
    if(isset($ar[$k]) === true){
        $val = $ar[$k];
        unset($ar[$k]);

        return $val;
    }
	
	return $val;
}

function is_assoc($ar){
    $keys = array_keys($ar);
    return array_keys($keys) !== $keys;
}

/**********************************************************************************************
								Testing
**********************************************************************************************/

function print_test($var){
	echo '<pre>';
	var_dump($var);
	echo "</pre>\n";
}

function print_die($var){
	print_test($var);
	die;
}

$_print_track_count = 0;
function print_track($var){
	global $_print_track_count;
	echo $_print_track_count . "\n";
	print_test($var);
	$_print_track_count++;
}
?>