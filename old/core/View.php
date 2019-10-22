<?php
class View extends Layout{
	protected $_viewContent = false;
	protected $_viewFile = false;
	protected $_cached = false;
	
	protected $_scripts = [];
	protected $_styles = [];

	public function __construct($file = false){
		$this->setView($file);
		$this->setLayout('main');
	}

/**********************************************************************************************
								Render
**********************************************************************************************/
	
	public function render(){
		$content = false;

		if($this->isCached() === true){
			$content = $this->getCached();
			$this->setContent($content);
		}

		$file = PATH_APP_VIEW . $this->getView();

		if($content === false && file_exists($file . '.phtml') === true){
			$this->_viewContent = $this->capture($file);
			$content = parent::render();

			if($content !== false && $this->isCached() === true){
				$this->buildCache();
			}
		}

		return $content;
	}

	public function view(){ return $this->_viewContent; }

/**********************************************************************************************
								Files
**********************************************************************************************/

	public function setView($file){ $this->_viewFile = $file; }
	
	public function setViewFromRoute($route){
		unset($route['locale']);
		$this->setView(implode('/', $route));
	}
	
	public function getView(){ return $this->_viewFile; }

	public function setLayout($layout){ $this->setFile($layout); }
	
	public function getLayout(){ return $this->getFile(); }
	
/**********************************************************************************************
								Cache
**********************************************************************************************/
	
	public function setCaching($value = true){ $this->_cached = $value; }
	
	public function isCached(){ return $this->_cached; }

	public function getCached(){
		return Cache::getView(
			$this->getView(),
			T::getLocale()
		);
	}
	
	public function buildCache(){
		Cache::setView(
			$this->getView(),
			T::getLocale(),
			$this->getContent()
		);
	}
	
/**********************************************************************************************
								Styles
**********************************************************************************************/
	
	public function renderStyles(){
		$styles = '';
	
		foreach(self::getStyles() as $style => $attribute){
			$styles .= '<link type="text/css" href="' . PATH_PUBLIC_JS_SHORT . $style . '" rel="stylesheet" ' . $attribute .' />';
		}

		return $styles;
	}
	
	public function setStyle($style, $attribute = null){ $this->_styles[$style] = $attribute; }
	
	public function setStyles($styles){ $this->_styles = $styles; }
	
	public function addStyles($styles){ $this->_styles = array_merge($this->_styles, $styles); }
	
	public function removeStyle($style){ unset($this->_styles[$style]); }
	
	public function getStyles(){ return $this->_styles; }
	
/**********************************************************************************************
								Scripts
**********************************************************************************************/
	
	public function renderScripts(){
		$scripts = '';
	
		foreach(self::getScripts() as $script => $attribute){
			$scripts .= '<script src="' . PATH_PUBLIC_JS_SHORT . $script . '" ' . $attribute . '></script>';
		}

		return $scripts;
	}
	
	public function setScript($script, $attribute = null){ $this->_scripts[$script] = $attribute; }
	
	public function setScripts($scripts){ $this->_scripts = $scripts; }
	
	public function addScripts($scripts){ $this->_scripts = array_merge($this->_scripts, $scripts); }
	
	public function removeScript($style){ unset($this->_scripts[$style]); }
	
	public function getScripts(){ return $this->_scripts; }
}
?>