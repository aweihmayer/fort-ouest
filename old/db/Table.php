<?php
class Db_Table{
	protected $_primary = null;
	protected $_cols = null;

/**********************************************************************************************
								Table
**********************************************************************************************/

	public function getName(){
		$table = get_called_class();
		$table = explode('_', $table);
		array_shift($table);

		return implode('_', $table);
	}

/**********************************************************************************************
									Select
**********************************************************************************************/

	private function select($params, $options){
		$options = $this->_buildOptions($options);
		$sql = 'SELECT ' . $options['cols'] . ' FROM ' . $this->getName();

		if(empty($params) === false){
			$sql  .= ' WHERE ' . Db_Query::buildWhere($params, $options['operator']);
		}

		if(isset($options['limit']) === true){
			$sql .= ' LIMIT ' . $options['limit'];
		}

		if(isset($options['order']) === true){
			$sql .=  ' ORDER BY ' . Db_Query::buildOrder($options['order']);
		}

		$query = new Db_Query($sql, $params, $options);

		return $query->execute();
	}

	public function selectBy($params, $options = []){
		return $this->select($params, $options);
	}

	public function selectOneBy($params, $options = []){
		$options['limit'] = 1;
		return $this->select($params, $options);
	}
	
/**********************************************************************************************
									Insert
**********************************************************************************************/

	public function insert($params, $options = []){
		$cols = array_keys($params);
		$sqlColsToInsert = implode(', ', $cols); 
		$sqlColValues = ':' . implode(', :', $cols);
		
		if($this->hasCol('date_create') === true){ $params['date_create'] = date('Y-m-d'); }
		if($this->hasCol('date_update') === true){ $params['date_update'] = date('Y-m-d'); }
		
		$query = new Db_Query(
			'INSERT INTO ' . $this->getName() . ' (' . $sqlColsToInsert . ') VALUES(' . $sqlColValues . ')',
			$params,
			$options
		);
		
		return $query->execute();
	}
	
/**********************************************************************************************
									Update
**********************************************************************************************/

	public function updateBy($paramsSet, $paramsWhere, $options = []){
		$options = $this->_buildOptions($options);
		$sqlColsToSet = [];
		
		foreach($paramsSet as $col => $value){
			$binder = $col . '_set';
			$paramsSet[$binder] = $value;
			unset($paramsSet[$col]);
			$sqlColsToSet[] = $col . ' = :' . $binder;
		}
		
		$sqlColsToSet = implode(', ', $sqlColsToSet);
				
		$query = new Db_Query(
			'UPDATE ' . $this->getName() . ' SET ' . $sqlColsToSet . ' WHERE ' . Db_Query::buildWhere($paramsWhere, $options['operator']),
			$paramsWhere + $paramsSet,
			$options
		);
		
		return $query->execute();
	}

/**********************************************************************************************
									Delete
**********************************************************************************************/

	public function deleteBy($params, $options = []){
		$options = $this->_buildOptions($options);
		$query = new Db_Query(
			'DELETE FROM ' . $this->getName() . ' WHERE ' . Db_Query::buildWhere($params, $options['operator']),
			$params,
			$options
		);
		return $query->execute();
	}
	
/**********************************************************************************************
									Cols
**********************************************************************************************/

	public function setCols($cols){ $this->_cols = $cols; }
	
	public function setPrimary($col){ $this->_primary = $col; }
		
	public function getCols(){ return $this->_cols; }
	
	public function getPrimary(){ return $this->_primary; }
	
	public function getColNames(){ return array_keys($this->_cols); }
	
	public function getColType($name){ return $this->_cols[$name]; }
	
	public function hasCol($name){ return isset($this->_cols[$name]); }
	
/**********************************************************************************************
									Build
**********************************************************************************************/

	private function _buildOptions($options){
		return array_merge(
			[
				'operator' => 'AND',
				'cols' => $this->getCols(),
				'map' => $this->getName()
			],
			$options
		);
	}
}
?>