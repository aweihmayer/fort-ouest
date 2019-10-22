<?php
class Form_Element extends Layout{
	protected $_name = null;
	protected $_value = null;
	
// Layout

	protected $_binders = [];
	protected $_labels = null;
	protected $_pointer = null;
	
// Validation

	protected $_validators = [];
	protected $_errors = [];
	protected $_messages = [];
	
// Functionality

	protected $_isVoid = false;
	protected $_isMulti = false;

	public function __construct($name, $multi = false){
		$this->_name = $name;
		$this->setLayout(Data::getConfig('form', $this->getType()));
		
		if($multi === true){
			$this->setValue([]);
			$this->setLabels([]);
			$this->_multi = true;
		}
		
		if(method_exists($this, 'init') === true){
			$this->init();
		}
	}
	
	public function getName(){ return $this->_name; }
	
	public function getType(){ return $this->_type; }
	
/**********************************************************************************************
								Render
**********************************************************************************************/

	public function __toString(){ return $this->render(); }
	
	public function render($pointer = null){
		$element = '';
		$count = 1;
		$offset = 0;
		
		if($this->isMulti() === true && isset($pointer) === false){
			$count = $this->countRenderLoops();
		}
		elseif(isset($pointer) === true){
			$offset = $pointer;
			$count = $pointer + 1;
		}

		if(is_null($this->getLabels()) === true){
			$this->setLabel($this->getName());
		}
		
		for($i = $offset; $i < $count; $i++){
			$this->setPointer($i);
			$element .= parent::render();
		}

		return $element;
	}
	
	private function setPointer($i){ $this->_pointer = $i; }
	
	public function getPointer(){ return $this->_pointer; }
	
	public function countRenderLoops(){ return ($this->isMulti() === false) ? 1 : count($this->getValue()); }
	
/**********************************************************************************************
								Render layout
**********************************************************************************************/
	
	public function name(){ return $this->getName() . (($this->isMulti() === false) ? $this->getName() : '' . '[]'); }
	
	public function value(){ return $this->getValue($this->getPointer()); }
	
	public function label(){ return $this->getLabel($this->getPointer()); }
	
	public function id(){ return 'inp-' . $this->getName() . (($this->isMulti() === true) ? $this->getPointer() : ''); }
	
	public function errors(){
		$errors = [];
		
		foreach($this->getErrors() as $error => $options){
			foreach($options as $k => $v){
				if(is_array($v)  === true){
					unset($options[$k]);
				}
			}
			
			if($this->hasMessage($error) === true){
				$error = $this->getMessage($error);
			}
		
			$errors[] = t($error, $options);
		}
		
		return $errors;
	}
		
/**********************************************************************************************
								Layout
**********************************************************************************************/

	public function setLayout($layout){
		$this->setFile($layout);
		return $this;
	}

	public function getLayout(){ return $this->getFile(); }

/**********************************************************************************************
								Label
**********************************************************************************************/
	
	public function setLabel($label){
		$this->_labels = $label;
		return $this;
	}
	
	public function getLabel($i = null){ return get_by_index($this->_labels, $i); }
	
/**********************************************************************************************
								Value
**********************************************************************************************/
	
	public function setValue($value){
		$this->_value = $value;
		return $this;
	}
	
	public function getValue($i = null){ return get_by_index($this->_value, $i); }
	
/**********************************************************************************************
								Validators
**********************************************************************************************/

	public function setValidators($validators){
		$this->_validators = $validators;
		return $this;
	}
	
	public function setValidator($name, $options){
		$this->_validators[$name] = $options;
		return $this;
	}
	
	public function removeValidator($name){
		unset($this->_validators[$name]);
		return $this;
	}

	public function getValidators(){ return $this->_validators; }
	
	public function getValidator($name){ return $this->_validators[$name]; }
	
	public function isValid($value = null){
		$valid = true;
		
		if(isset($value) === true){
			$this->setValue($value);
		}
		
		$value = $this->getValue();
		
		if($this->hasValidator('required') === true || empty_allow_zero($value) === false){
			foreach($this->getValidators() as $name => $options){
				$result = Data::validate($name, $value, $options);

				if($result !== true){
					$this->setError($result, $options);
					$valid = false;
				}
			}
		}

		return $valid;
	}
	
	public function validate($name, $value = null, $options = null, $errorLimit = 25){
		$errors = [];
		$name = ucfirst($name);
		
		if(isset(self::$_validators[$name]) === false){
			$validator = 'Validator_' . $name;
			self::$_validators[$name] = new $validator();
		}
		
		if(is_array($value) === false){
			$value = [$value];
		}
		
		$count = 0;
		foreach($value as $v){
			$count++;
		
			$result = self::$_validators[$name]->validate($v, $options);
			
			if($result !== false){
				$errors[] = $result;
			}
			
			if($count == $errorLimit){
				break;
			}			
		}
		
		return ($errorLimit == 1) ? array_shift($errors) : $errors;
	}
	
	public function hasValidator($name){ return isset($this->_validators[$name]); }
	
/**********************************************************************************************
								Errors
**********************************************************************************************/
	
	public function setErrors($errors){
		$this->_errors = $errors;
		return $this;
	}
	
	public function setError($error, $options = []){ 
		$this->_errors[$error] = $options;
		return $this;
	}
	
	public function getErrors(){ return $this->_errors; }

/**********************************************************************************************
								Messages
**********************************************************************************************/
	
	public function setMessages($messages){
		$this->_messages = $messages;
		return $this;
	}
	
	public function setMessage($name, $message){
		$this->_messages[$name] = $message;
		return $this;
	}

	public function removeMessage($key){
		unset($this->_messages[$key]);
		return $this;
	}

	public function getMessages(){ return $this->_messages; }
	
	public function getMessage($name){ return $this->_messages[$name]; }
	
	public function hasMessage($name){ return isset($this->_messages[$name]); }
	
/**********************************************************************************************
								Functionality
**********************************************************************************************/
	
	public function setVoid($bool){
		$this->_isVoid = $bool;
		return $this;
	}
	
	public function isVoid(){ return (bool)$this->_isVoid; }
	
	public function isMulti(){ return (bool)$this->_isMulti; }
}
?>