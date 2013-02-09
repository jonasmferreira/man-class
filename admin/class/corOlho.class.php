<?php
$path_root_corOlhoClass = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_corOlhoClass = "{$path_root_corOlhoClass}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_corOlhoClass}admin{$DS}class{$DS}default.class.php";
class corOlho extends DefaultClass{
	protected $dbConn;
	public function __construct() {
		$this->dbConn = new DataBaseClass();
	}
	public function getSql(){
		$sql = array();
		$sql[] = "
			SELECT	*
			FROM	tb_cor_olho
			WHERE	1 = 1
		";
		return implode("\n",$sql);
	}
	public function getLista(){
		$sql = array();
		$sql[] = $this->getSql();
		if(isset($this->values['cor_olho_titulo'])&&trim($this->values['cor_olho_titulo'])!=''){
			$sql[] = "AND cor_olho_titulo LIKE '%{$this->values['cor_olho_titulo']}%'";
		}
		$sql[] = "ORDER BY cor_olho_titulo ASC";
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
		$sql[] = "AND cor_olho_id = '{$this->values['cor_olho_id']}'";
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
		if(isset($this->values['cor_olho_id'])&&trim($this->values['cor_olho_id'])!=''){
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
			UPDATE	tb_cor_olho SET
				cor_olho_titulo = '{$this->values['cor_olho_titulo']}'
		";
		$sql[] = "
			WHERE	cor_olho_id = '{$this->values['cor_olho_id']}'
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
			INSERT INTO	tb_cor_olho SET
				cor_olho_titulo = '{$this->values['cor_olho_titulo']}'
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
			DELETE FROM tb_cor_olho
			WHERE cor_olho_id = '{$this->values['cor_olho_id']}'
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
