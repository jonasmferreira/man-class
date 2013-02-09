<?php
$path_root_loginClass = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_loginClass = "{$path_root_loginClass}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_loginClass}admin{$DS}class{$DS}default.class.php";
class login extends DefaultClass{
	protected $dbConn;
	
	public function __construct() {
		$this->dbConn = new DataBaseClass();
	}
	public function logon(){
		$this->post['user'] = $this->antiInjection($this->post['user']);
		$this->post['pass'] = md5($this->antiInjection($this->post['pass']));
		$sql = "
			SELECT	u.usuario_id
					,u.usuario_nome
					,u.usuario_login
					,u.usuario_senha
					,u.usuario_status_id
					,u.usuario_nivel_id
			FROM	tb_usuario u
			
			WHERE	1 = 1
			AND		u.usuario_login = '{$this->post['user']}'
			AND		u.usuario_senha = '{$this->post['pass']}'
			AND		u.usuario_status_id = '1'
		";
		$result = $this->dbConn->db_query($sql);
		if($result['success']){
			if($result['total'] == 1){
				$rs = $this->dbConn->db_fetch_assoc($result['result']);
				$this->registerSession($this->utf8_array_encode($rs));
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
?>
