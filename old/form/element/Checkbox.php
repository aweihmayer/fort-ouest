<?php
class Element_Checkbox extends Element{
	public function option(){ return $this->_options; }

	public function isChecked(){ return ($this->getOption() === $this->getValue()) ? true : false; }
}
?>