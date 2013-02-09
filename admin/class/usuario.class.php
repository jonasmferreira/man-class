<?php
	$path_root_UsuarioClass = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_UsuarioClass = "{$path_root_UsuarioClass}{$DS}..{$DS}..{$DS}";
	require_once "{$path_root_UsuarioClass}admin{$DS}class{$DS}default.class.php";
	class usuario extends DefaultClass{
		protected $dbConn;

		public function __construct() {
			$this->dbConn = new DataBaseClass();
		}
		
		public function getLista(){
			$sql = array();
			$sql[] = "
				SELECT	u.usuario_id
					,u.usuario_nome
					,u.usuario_login
					,u.usuario_senha
					,u.usuario_status_id
					,u.usuario_nivel_id
					,un.usuario_nivel_titulo
					,us.usuario_status_titulo
				FROM	tb_usuario u
				
				INNER JOIN	tb_usuario_nivel un
				ON	un.usuario_nivel_id = u.usuario_nivel_id
				
				INNER JOIN	tb_usuario_status us
				ON	us.usuario_status_id = u.usuario_status_id

				WHERE	1 = 1
			";
			if(isset($this->values['usuario_nome'])&&trim($this->values['usuario_nome'])!=''){
				$sql[] = "AND u.usuario_nome LIKE '%{$this->values['usuario_nome']}%'";
			}
			if(isset($this->values['usuario_login'])&&trim($this->values['usuario_login'])!=''){
				$sql[] = "AND u.usuario_login LIKE '%{$this->values['usuario_login']}%'";
			}
			if(isset($this->values['usuario_status_id'])&&trim($this->values['usuario_status_id'])!=''){
				$sql[] = "AND u.usuario_status_id = '{$this->values['usuario_status_id']}'";
			}
			if(isset($this->values['usuario_nivel_id'])&&trim($this->values['usuario_nivel_id'])!=''){
				$sql[] = "AND u.usuario_nivel_id = '{$this->values['usuario_nivel_id']}'";
			}
			$sql[] = "ORDER BY u.usuario_nome ASC";
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
			$sql[] = "
				SELECT	u.usuario_id
					,u.usuario_nome
					,u.usuario_login
					,u.usuario_senha
					,u.usuario_status_id
					,u.usuario_nivel_id
					,un.usuario_nivel_titulo
					,us.usuario_status_titulo
				FROM	tb_usuario u
				
				INNER JOIN	tb_usuario_nivel un
				ON	un.usuario_nivel_id = u.usuario_nivel_id
				
				INNER JOIN	tb_usuario_status us
				ON	us.usuario_status_id = u.usuario_status_id

				WHERE	1 = 1
				AND		u.usuario_id = '{$this->values['usuario_id']}'
			";
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
			if(isset($this->values['usuario_id'])&&trim($this->values['usuario_id'])!=''){
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
				UPDATE	tb_usuario SET
					usuario_nome = '{$this->values['usuario_nome']}'
					,usuario_login = '{$this->values['usuario_login']}'
			";
			if(isset($this->values['usuario_senha'])&&trim($this->values['usuario_senha'])!=''){
				$sql[] = ",usuario_senha = MD5('{$this->values['usuario_senha']}')";
			}
			$sql[] = "
					,usuario_status_id = '{$this->values['usuario_status_id']}'
					,usuario_nivel_id = '{$this->values['usuario_nivel_id']}'
				WHERE	usuario_id = '{$this->values['usuario_id']}'
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
				INSERT INTO	tb_usuario SET
					usuario_nome = '{$this->values['usuario_nome']}'
					,usuario_login = '{$this->values['usuario_login']}'
					,usuario_senha = MD5('{$this->values['usuario_senha']}')
					,usuario_status_id = '{$this->values['usuario_status_id']}'
					,usuario_nivel_id = '{$this->values['usuario_nivel_id']}'
			";
			$result = $this->dbConn->db_execute(implode("\n",$sql));
			if($result['success']===false){
				$this->dbConn->db_rollback();
			}else{
				$this->dbConn->db_commit();
			}
			return $result;
		}
		
		public function getNivel(){
			$sql = array();
			$sql[] = "
				SELECT	*
				FROM	tb_usuario_nivel un

				WHERE	1 = 1
			";
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
		
		public function getStatus(){
			$sql = array();
			$sql[] = "
				SELECT	*
				FROM	tb_usuario_status us

				WHERE	1 = 1
			";
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
		public function deleteItem(){
			$this->dbConn->db_start_transaction();
			$sql = array();
			$sql[] = "
				DELETE FROM tb_usuario
				WHERE usuario_id = '{$this->values['usuario_id']}'
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
