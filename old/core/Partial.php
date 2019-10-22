<?php
class Partial{
	protected $_file = false;
	protected $_content = false;

	public function __construct($file = false, $params = []){ 
		$this->setFile($file);

		if(is_array($params) === true){
			foreach($params as $k => $v){
				$this->$k = $v;
			}
		}
	}

/**********************************************************************************************
								File
**********************************************************************************************/

	public function setFile($file){ $this->_setFile($file, PATH_APP_PARTIAL); }

	protected function _setFile($file, $path){ $this->_file = $path . $file; }

	public function getFile(){ return $this->_file; }

/**********************************************************************************************
								Render
**********************************************************************************************/
	
	public function render(){
		$content = $this->capture($this->getFile());
		$this->setContent($content);

		return $content;
	}
	
	public function capture($file){
		ob_start();
		include $file . '.phtml';

		return ob_get_clean();
	}
	
/**********************************************************************************************
								Content
**********************************************************************************************/

	public function setContent($content){ $this->_content = $content; }
	
	public function getContent(){ return $this->_content; }
	
	public function isEmpty(){ return empty($this->getContent()); }
}
?>