<?php
$path_root_galeriaModeloClass = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_galeriaModeloClass = "{$path_root_galeriaModeloClass}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_galeriaModeloClass}admin{$DS}class{$DS}default.class.php";
class galeriaModelo extends DefaultClass{
	protected $dbConn;
	protected $pathImg;
	protected $status = array(
		array(
			'status_id'=>"A"
			,'status_titulo'=>"Ativo"
		)
		,array(
			'status_id'=>"I"
			,'status_titulo'=>"Inativo"
		)
	);
	public function __construct() {
		$this->dbConn = new DataBaseClass();
		$path_root_galeriaModeloClass = dirname(__FILE__);
		$DS = DIRECTORY_SEPARATOR;
		$path_root_galeriaModeloClass = "{$path_root_galeriaModeloClass}{$DS}..{$DS}..{$DS}";
		$this->pathImg = "{$path_root_galeriaModeloClass}imgModelo{$DS}";
		if(!is_dir($this->pathImg)){
			mkdir($this->pathImg,0777,true);
		}
		chmod($this->pathImg,0777);
	}
	
	public function getStatus() {
		return $this->status;
	}

		
	public function getSql(){
		$sql = array();
		$sql[] = "
			SELECT	gi.galeria_imagem_id
					,IFNULL(gi.modelo_id,m.modelo_id) AS modelo_id
					,gi.galeria_imagem_titulo
					,gi.galeria_imagem_descricao
					,gi.galeria_imagem_thumb
					,gi.galeria_imagem_img
					,m.modelo_nome
			FROM	tb_modelo m
			LEFT JOIN	tb_galeria_imagem gi
			ON		m.modelo_id = gi.modelo_id
			WHERE	1 = 1
		";
		return implode("\n",$sql);
	}
	public function getLista(){
		$sql = array();
		$sql[] = "
			SELECT	m.modelo_nome
					,m.modelo_id
					,IFNULL(COUNT(m.galeria_imagem_id),0) AS 'qtde_img'
			FROM	(
		";
		$sql[] = $this->getSql();
		if(isset($this->values['galeria_imagem_titulo'])&&trim($this->values['galeria_imagem_titulo'])!=''){
			$sql[] = "AND gi.galeria_imagem_titulo LIKE '%{$this->values['galeria_imagem_titulo']}%'";
		}
		if(isset($this->values['modelo_id'])&&trim($this->values['modelo_id'])!=''){
			$sql[] = "AND m.modelo_id = '{$this->values['modelo_id']}'";
		}
		$sql[] = "ORDER BY gi.galeria_imagem_titulo ASC";
		$sql[] = ") AS m";
		$sql[] = "GROUP BY m.modelo_id ASC";
		$sql[] = "ORDER BY m.modelo_nome ASC";
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
	public function getOne($modelo=true){
		$sql = array();
		$sql[] = $this->getSql();
		if($modelo!==false){
			$sql[] = "AND m.modelo_id = '{$this->values['modelo_id']}'";
		}else{
			$sql[] = "AND gi.galeria_imagem_id = '{$this->values['galeria_imagem_id']}'";
		}
		$result = $this->dbConn->db_query(implode("\n",$sql));
		$arr = array();
		if($result['success']){
			if($result['total'] > 0){
				if($modelo!==false){
					while($rs = $this->dbConn->db_fetch_assoc($result['result'])){
						array_push($arr,$rs);
					}
				}else{
					$rs = $this->dbConn->db_fetch_assoc($result['result']);
					$arr = $rs;
				}
			}
		}
		return $this->utf8_array_encode($arr);
	}
	protected function uploadImg($files,$idx){
		$DS = DIRECTORY_SEPARATOR;
		$pasta = "{$this->pathImg}modelo_{$this->values['modelo_id']}{$DS}";
		if(!is_dir($pasta)){
			@mkdir($pasta,0777,true);
		}
		@chmod($pasta,0777);
		if(isset($files)&&trim($files['name'][$idx])){
			$fn = $this->getSeqAleatoria()."_{$files['name'][$idx]}";
			$file_name = "{$pasta}{$fn}";
			if(move_uploaded_file($files['tmp_name'][$idx], $file_name)){
				return $fn;
			}else{
				return "";
			}
		}
		return "";
	}
	public function edit(){
		$result = $this->insert();
		return $result;
	}
	private function deleteAllImagem($excluir_img=false){
		$DS = DIRECTORY_SEPARATOR;
		$pasta = "{$this->pathImg}modelo_{$this->values['modelo_id']}{$DS}";
		if(!is_dir($pasta)){
			@mkdir($pasta,0777,true);
		}
		@chmod($pasta,0777);
		if($excluir_img){
			$imgs = $this->getOne();
			foreach($imgs AS $v){
				@unlink($pasta.$v[$this->values['galeria_imagem_img']]);
				@unlink($pasta.$v[$this->values['galeria_imagem_thumb']]);
			}
		}
		$sql = array();
		$sql[] = "DELETE FROM tb_galeria_imagem WHERE modelo_id = '{$this->values['modelo_id']}'";
		return $this->dbConn->db_execute(implode("\n",$sql));
	}
	private function insert(){
		$result = array('success'=>true);
		if(is_array($this->values['galeria_imagem_titulo'])&&count($this->values['galeria_imagem_titulo'])>0){
			$this->dbConn->db_start_transaction();
			$result = $this->deleteAllImagem();
			if($result['success']===false){
				$this->dbConn->db_rollback();
				return $result;
			}
			foreach($this->values['galeria_imagem_titulo'] AS $k=>$v){
				$galeria_imagem_titulo = $v;
				$galeria_imagem_descricao = $this->values['galeria_imagem_descricao'][$k];
				$galeria_imagem_thumb = $this->uploadImg($this->files['galeria_imagem_thumb'],$k);
				$galeria_imagem_img = $this->uploadImg($this->files['galeria_imagem_img'],$k);
				$sql = array();
				$sql[] = "
					INSERT INTO	tb_galeria_imagem SET
						galeria_imagem_titulo = '{$galeria_imagem_titulo}'
						,galeria_imagem_descricao = '{$galeria_imagem_descricao}'
						,modelo_id = '{$this->values['modelo_id']}'
				";
				if(isset($galeria_imagem_thumb)&&trim($galeria_imagem_thumb)!=''){
					$sql[] = ",galeria_imagem_thumb = '{$galeria_imagem_thumb}'";
				}
				if(isset($galeria_imagem_img)&&trim($galeria_imagem_img)!=''){
					$sql[] = ",galeria_imagem_img = '{$galeria_imagem_img}'";
				}
				$result = $this->dbConn->db_execute(implode("\n",$sql));
				if($result['success']===false){
					$this->dbConn->db_rollback();
					return $result;
				}
			}
			$this->dbConn->db_commit();
		}
		return $result;
	}
	public function deleteImg(){
		$img = $this->getOne(false);
		$DS = DIRECTORY_SEPARATOR;
		$pasta = "{$this->pathImg}modelo_{$img['modelo_id']}{$DS}";
		if(!is_dir($pasta)){
			@mkdir($pasta,0777,true);
		}
		@chmod($pasta,0777);
		
		$this->dbConn->db_start_transaction();
		$sql = array();
		$sql[] = "
			DELETE FROM tb_galeria_imagem
			WHERE galeria_imagem_id = '{$this->values['galeria_imagem_id']}'
		";
		$result = $this->dbConn->db_execute(implode("\n",$sql));
		if($result['success']===false){
			$this->dbConn->db_rollback();
		}else{
			$this->dbConn->db_commit();
			@unlink($pasta.$img['galeria_imagem_img']);
			@unlink($pasta.$img['galeria_imagem_thumb']);
		}
		return $result;
	}
	public function getModelo(){
		$path_root_modeloLista = dirname(__FILE__);
		$DS = DIRECTORY_SEPARATOR;
		$path_root_modeloLista = "{$path_root_modeloLista}{$DS}..{$DS}..{$DS}";
		require_once "{$path_root_modeloLista}admin{$DS}class{$DS}modelo.class.php";
		$obj = new modelo();
		return $obj->getLista();
	}
}
?>
