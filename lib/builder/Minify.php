<?php 
// https://github.com/matthiasmullie/minify
use MatthiasMullie\Minify;

class Builder_Minify{
	public function __construct(){		
		$filesToRequire = [
			'src/Minify', 'src/CSS', 'src/JS', 'src/Exception',
			'src/Exceptions/BasicException', 'src/Exceptions/FileImportException', 'src/Exceptions/IOException',
			'path-converter/src/ConverterInterface', 'path-converter/src/Converter'
		];

		foreach($filesToRequire as $file){
			include __DIR__ . '/minifier/' . $file . '.php';
		}
	}
	
/**********************************************************************************************
								JS/CSS
**********************************************************************************************/

	public function css(){
		$files = $this->completeFileNames(Data::config('build/css'), PATH_PUBLIC_CSS, '.css');
		$this->minify($files, 'css');
	}

	public function js(){
		$files = $this->completeFileNames(Data::config('build/js'), PATH_PUBLIC_JS, '.js');
		$this->minify($files, 'js');
	}

/**********************************************************************************************
								Base
**********************************************************************************************/
	
	public function completeFileNames($files, $path, $extension){
		foreach($files as $new => $toCombine){
			$new2 = $path . $new . '.min' . $extension;
			$toCombine2 = [];
			
			foreach($toCombine as $old){
				$toCombine2[] = $path . $old . $extension;
			}
			
			$files[$new2] = $toCombine2;
			unset($files[$new]);
		}
		
		return $files;
	}
	
	public function minify($files, $type){
		foreach($files as $new => $oldFiles){
			$minifier = ($type === 'js') ? new Minify\JS() : new Minify\CSS;
			
			foreach($oldFiles as $old){
				$minifier->add($old);
			}

			$minifier->minify($new);
		}
	}
}
?>