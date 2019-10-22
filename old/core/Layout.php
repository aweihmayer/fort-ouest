<?php
class Layout extends Partial{
/**********************************************************************************************
								File
**********************************************************************************************/

	public function setFile($file){ $this->_setFile($file, PATH_APP_LAYOUT); }

/**********************************************************************************************
								Render
**********************************************************************************************/

	public function partial($file, $params = []){
		if(empty($params) === true){
			$result = $this->capture(PATH_APP_PARTIAL . $file);
		}
		else{
			$partial = new Partial($file, $params);
			$result = $partial->render();
		}
				
		return $result;
	}

	public function layout($file, $params = []){
		if(empty($params) === true){
			$result = $this->capture(PATH_APP_LAYOUT . $file);
		}
		else{
			$layout = new Layout($file, $params);
			$result = $layout->render();
		}
				
		return $result;
	}
}
?>