<?php
$path_root_tipoModeloClass = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_tipoModeloClass = "{$path_root_tipoModeloClass}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_tipoModeloClass}admin{$DS}class{$DS}default.class.php";
class tipoModelo extends DefaultClass{
	protected $dbConn;
	public function __construct() {
		$this->dbConn = new DataBaseClass();
	}
	public function getSql(){
		$sql = array();
		$sql[] = "
			SELECT	*
			FROM	tb_tipo_modelo
			WHERE	1 = 1
		";
		return implode("\n",$sql);
	}
	public function getLista(){
		$sql = array();
		$sql[] = $this->getSql();
		if(isset($this->values['tipo_modelo_titulo'])&&trim($this->values['tipo_modelo_titulo'])!=''){
			$sql[] = "AND tipo_modelo_titulo LIKE '%{$this->values['tipo_modelo_titulo']}%'";
		}
		$sql[] = "ORDER BY tipo_modelo_titulo ASC";
		$arr = array();
		$result = $this->dbConn->db_query(implode("\n",$sql));
		if($result['success']){
			if($result['total'] > 0){
				while($rs = $this->dbConn->db_fetch_assoc($result['result'])){
					array_push($arr,$rs);
				}
			}
		}
		return $this->utf8_array_encode($arr);
	}
	public function getOne(){
		$sql = array();
		$sql[] = $this->getSql();
		$sql[] = "AND tipo_modelo_id = '{$this->values['tipo_modelo_id']}'";
		$result = $this->dbConn->db_query(implode("\n",$sql));
		$rs = array();
		if($result['success']){
			if($result['total'] > 0){
				$rs = $this->dbConn->db_fetch_assoc($result['result']);
			}
		}
		return $this->utf8_array_encode($rs);
	}
	public function edit(){
		if(isset($this->values['tipo_modelo_id'])&&trim($this->values['tipo_modelo_id'])!=''){
			$result = $this->update();
		}else{
			$result = $this->insert();
		}
		return $result;
	}
	private function update(){
		$this->dbConn->db_start_transaction();
		$sql = array();
		$sql[] = "
			UPDATE	tb_tipo_modelo SET
				tipo_modelo_titulo = '{$this->values['tipo_modelo_titulo']}'
		";
		$sql[] = "
			WHERE	tipo_modelo_id = '{$this->values['tipo_modelo_id']}'
		";
		$result = $this->dbConn->db_execute(implode("\n",$sql));
		if($result['success']===false){
			$this->dbConn->db_rollback();
		}else{
			$this->dbConn->db_commit();
		}
		return $result;
	}

	private function insert(){
		$this->dbConn->db_start_transaction();
		$sql = array();
		$sql[] = "
			INSERT INTO	tb_tipo_modelo SET
				tipo_modelo_titulo = '{$this->values['tipo_modelo_titulo']}'
		";
		$result = $this->dbConn->db_execute(implode("\n",$sql));
		if($result['success']===false){
			$this->dbConn->db_rollback();
		}else{
			$this->dbConn->db_commit();
		}
		return $result;
	}
	public function deleteItem(){
		$this->dbConn->db_start_transaction();
		$sql = array();
		$sql[] = "
			DELETE FROM tb_tipo_modelo
			WHERE tipo_modelo_id = '{$this->values['tipo_modelo_id']}'
		";
		$result = $this->dbConn->db_execute(implode("\n",$sql));
		if($result['success']===false){
			$this->dbConn->db_rollback();
		}else{
			$this->dbConn->db_commit();
		}
		return $result;
	}
}
?>
