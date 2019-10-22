<?php
class Form_Core{
	protected $_elements = [];
	protected $_saveKey = null;
	
/**********************************************************************************************
								Save
**********************************************************************************************/
	
	public function setSaveKey($key){ $this->_saveKey = $key; }
	
	public function getSaveKey(){ return (isset($this->_saveKey) === false) ? get_class($this) : $this->_saveKey; }
	
	public function load($key){
		$key = $this->getSaveKey();

		if(isset($_SESSION['_forms'][$key]) === true){
			$this->setValues($_SESSION['_forms'][$key]);
		}
	}
	
/**********************************************************************************************
								Actions
**********************************************************************************************/

	public function setValues($values){
		foreach($values as $name => $v){
			if($this->has($name) === true){
				$this->get($name)->setValue($v);
			}
		}
	}

	public function save(){ $_SESSION['_forms'][$this->getSaveKey()] = $this->getValues(); }
	
/**********************************************************************************************
								Setters
**********************************************************************************************/
	
	public function add($type, $name, $isMulti = false){
		$type = ucfirst($type);
		include_once PATH_CORE_ELEMENT . $type . '.php';
		$element = 'Element_' . $type;
		$this->_elements[$name] = new $element($name, $isMulti); 
		
		return $this->_elements[$name];
	}
	
	public function remove($name){ unset($this->_elements[$name]); }
		
/**********************************************************************************************
								Getters
**********************************************************************************************/
	
	public function get($element){ return $this->_elements[$element]; }
	
	public function getValues(){
		$values = [];
	
		foreach($this->_elements as $name => $element){
			if($element->isVoid() === false){
				$values[$name] = $element->getValue();
			}
		}
		
		return $values;
	}
	
/**********************************************************************************************
								Flags
**********************************************************************************************/

	public function isValid($values = null){
		$valid = true;

		if(isset($values) === true){
			$this->setValues($values);
		}
		
		foreach($this->_elements as $element){
			if($element->isValid() === false){
				$valid = false;
			}
		}

		return $valid;
	}

	public function has($name){ return isset($this->_elements[$name]); }
}
?>