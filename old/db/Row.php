<?php
class Db_Row{
/**********************************************************************************************
								Table
**********************************************************************************************/

	public function getTableName(){
		$table = get_class($this);
		$table = explode('_', $table);
		array_shift($table);
		
		return implode('_', $table);
	}
	
	public function getTable(){ return Db_Core::table($this->getTableName()); }
	
	public function getPrimary(){ return $this->getTable()->getPrimary(); }
	
/**********************************************************************************************
									Query
**********************************************************************************************/

	public function save(){		
		if($this->isNew() === true){
			$primary = $this->getPrimary();
			$primaryVal = $this->getTable()->insert(get_public_vars($this));
			$this->$primary = $primaryVal;
		}
		else{
			$primary = $this->getPrimary();
			$primaryVal = $this->$primary;
			
			$row = $this->toArray();
			unset($row[$primary]);
			
			$this->updateBy(
				$row,
				[$primary => $row[$primary]]
			);
		}
	}

	public function delete(){
		$primary = $this->getPrimary();
		$primaryVal = $this->$primary;
		
		return $this->getTable()->deleteBy([$primary => $primaryVal]);
	}
	
	public function isNew(){
		$primary = $this->getPrimary();
		return isset($this->$primary);
	}
}
?>