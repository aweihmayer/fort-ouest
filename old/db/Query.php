<?php
class Db_Query{
	protected $_sql = '';
	protected $_params = [];
	protected $_options = [];

	public function __construct($sql = '', $params = [], $options = []){
		$this->setSql($sql);
		$this->setParams($params);
		$this->setOptions($options);
	}
	
/**********************************************************************************************
									Query
**********************************************************************************************/

	public function setSql($sql){ $this->_sql = $sql; }

	public function setParams($params){ $this->_params = $params; }
				
	public function setOptions($options){ $this->_options = $options; }
	
	public function getSql(){ return $this->_sql; }

	public function getParams(){ return $this->_params; }
			
	public function getOptions(){ return $this->_options; }
	
/**********************************************************************************************
									Execute
**********************************************************************************************/

	public function execute(){
		$type = current(explode(' ', $this->getSql()));
		$options = $this->getOptions();
		$stmt = $this->prepare();
		$stmt->execute();
	
		switch($type){
			case 'select':
				if(isset($options['map']) === true
				&& is_string($options['map']) === true){
					$stmt->setFetchMode(PDO::FETCH_CLASS, $options['map'], []);
				}

				$result = (isset($options['limit']) === true && $options['limit'] == 1) ? $stmt->fetch() : $stmt->fetchAll();
				break;
			case 'insert':
				$result = Db_Core::getConnection()->lastInsertId();
				break;
			case 'update':
			case 'delete':
				$result = true;
				break;
			default:
				$result = $stmt;
		}
		
		return $result;
	}
	
/**********************************************************************************************
									Build
**********************************************************************************************/
	
	public static function buildWhere($params, $operator = 'AND'){
		$conditions = [];

		foreach($this->getParams() as $col => $val){
			if(is_array($val) === true){
				$in = [];

				for($i = 0; $i < count($params); $i++){
					$in[] = ':' . $col . $i;
				}

				$conditions[] = $col . ' IN(' . implode(', ', $in) . ')';
			}
			else{
				$conditions[] = $col . ' = :' . $col;
			}
		}

		return implode(' ' . $operator . ' ', $conditions);
	}

	public function prepare(){
		$stmt = Db_Core::getConnection()->prepare($this->getSql());

		foreach($this->getParams() as $field => &$val){
			if(is_array($val) === true){
				for($i = 0; $i < count($val); $i++){
					$stmt->bindParam(':' . $field . $i, $val[$i]);
				}
			}
			else{
				$stmt->bindParam(':' . $field, $val);
			}
		}

		return $stmt;
	}
}
?>