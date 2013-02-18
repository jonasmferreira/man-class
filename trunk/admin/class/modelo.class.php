<?php
$path_root_modeloClass = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_modeloClass = "{$path_root_modeloClass}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_modeloClass}admin{$DS}class{$DS}default.class.php";
class modelo extends DefaultClass{
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
		$path_root_modeloClass = dirname(__FILE__);
		$DS = DIRECTORY_SEPARATOR;
		$path_root_modeloClass = "{$path_root_modeloClass}{$DS}..{$DS}..{$DS}";
		$this->pathImg = "{$path_root_modeloClass}imgModelo{$DS}";
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
			SELECT	m.modelo_id
					,m.tipo_modelo_id
					,m.cor_olho_id
					,m.modelo_nome
					,m.modelo_ddd
					,m.modelo_telefone
					,m.modelo_email
					,m.modelo_site
					,m.modelo_idade
					,m.modelo_altura
					,m.modelo_peso
					,m.modelo_descricao
					,m.modelo_img_dest_principal
					,m.modelo_img_dest_semana
					,m.modelo_img_area_modelo
					,m.modelo_status
					,co.cor_olho_titulo
					,tm.tipo_modelo_titulo
			FROM	tb_modelo m
			JOIN	tb_cor_olho co
			ON		co.cor_olho_id = m.cor_olho_id
			JOIN	tb_tipo_modelo tm
			ON		tm.tipo_modelo_id = m.tipo_modelo_id
			WHERE	1 = 1
		";
		return implode("\n",$sql);
	}
	public function getLista(){
		$sql = array();
		$sql[] = $this->getSql();
		if(isset($this->values['modelo_nome'])&&trim($this->values['modelo_nome'])!=''){
			$sql[] = "AND m.modelo_nome LIKE '%{$this->values['modelo_nome']}%'";
		}
		if(isset($this->values['tipo_modelo_id'])&&trim($this->values['tipo_modelo_id'])!=''){
			$sql[] = "AND m.tipo_modelo_id = '{$this->values['tipo_modelo_id']}'";
		}
		if(isset($this->values['cor_olho_id'])&&trim($this->values['cor_olho_id'])!=''){
			$sql[] = "AND m.cor_olho_id = '{$this->values['cor_olho_id']}'";
		}
		if(isset($this->values['modelo_status'])&&trim($this->values['modelo_status'])!=''){
			$sql[] = "AND m.modelo_status = '{$this->values['modelo_status']}'";
		}
		
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
	public function getOne(){
		$sql = array();
		$sql[] = $this->getSql();
		$sql[] = "AND m.modelo_id = '{$this->values['modelo_id']}'";
		$result = $this->dbConn->db_query(implode("\n",$sql));
		$rs = array();
		if($result['success']){
			if($result['total'] > 0){
				$rs = $this->dbConn->db_fetch_assoc($result['result']);
				$rs['modelo_altura'] = $this->floatDB2BR($rs['modelo_altura']);
				$rs['modelo_peso'] = $this->floatDB2BR($rs['modelo_peso']);
			}
		}
		return $this->utf8_array_encode($rs);
	}
	protected function uploadImg($files){
		if(isset($files)&&trim($files['name'])){
			$fn = $this->getSeqAleatoria()."_{$files['name']}";
			$file_name = "{$this->pathImg}{$fn}";
			if(move_uploaded_file($files['tmp_name'], $file_name)){
				return $fn;
			}else{
				return "";
			}
		}
		return "";
	}
	public function edit(){
		if(isset($this->values['modelo_id'])&&trim($this->values['modelo_id'])!=''){
			$result = $this->update();
		}else{
			$result = $this->insert();
		}
		return $result;
	}
	private function update(){
		$this->dbConn->db_start_transaction();
		$this->values['modelo_img_dest_principal'] = $this->uploadImg($this->files['modelo_img_dest_principal']);
		$this->values['modelo_img_dest_semana'] = $this->uploadImg($this->files['modelo_img_dest_semana']);
		$this->values['modelo_img_area_modelo'] = $this->uploadImg($this->files['modelo_img_area_modelo']);
		$this->values['modelo_altura'] = $this->floatBR2DB($this->values['modelo_altura']);
		$this->values['modelo_peso'] = $this->floatBR2DB($this->values['modelo_peso']);
		$sql = array();
		$sql[] = "
			UPDATE	tb_modelo SET
				modelo_nome = '{$this->values['modelo_nome']}'
				,tipo_modelo_id = '{$this->values['tipo_modelo_id']}'
				,cor_olho_id = '{$this->values['cor_olho_id']}'
				,modelo_ddd = '{$this->values['modelo_ddd']}'
				,modelo_telefone = '{$this->values['modelo_telefone']}'
				,modelo_email = '{$this->values['modelo_email']}'
				,modelo_site = '{$this->values['modelo_site']}'
				,modelo_idade = '{$this->values['modelo_idade']}'
				,modelo_altura = '{$this->values['modelo_altura']}'
				,modelo_peso = '{$this->values['modelo_peso']}'
				,modelo_descricao = '{$this->values['modelo_descricao']}'
				,modelo_status = '{$this->values['modelo_status']}'
		";
		if(isset($this->values['modelo_img_dest_principal'])&&trim($this->values['modelo_img_dest_principal'])!=''){
			$sql[] = ",modelo_img_dest_principal = '{$this->values['modelo_img_dest_principal']}'";
		}
		if(isset($this->values['modelo_img_dest_semana'])&&trim($this->values['modelo_img_dest_semana'])!=''){
			$sql[] = ",modelo_img_dest_semana = '{$this->values['modelo_img_dest_semana']}'";
		}
		if(isset($this->values['modelo_img_area_modelo'])&&trim($this->values['modelo_img_area_modelo'])!=''){
			$sql[] = ",modelo_img_area_modelo = '{$this->values['modelo_img_area_modelo']}'";
		}
		$sql[] = "
			WHERE	modelo_id = '{$this->values['modelo_id']}'
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
		$this->values['modelo_img_dest_principal'] = $this->uploadImg($this->files['modelo_img_dest_principal']);
		$this->values['modelo_img_dest_semana'] = $this->uploadImg($this->files['modelo_img_dest_semana']);
		$this->values['modelo_img_area_modelo'] = $this->uploadImg($this->files['modelo_img_area_modelo']);
		$this->values['modelo_altura'] = $this->floatBR2DB($this->values['modelo_altura']);
		$this->values['modelo_peso'] = $this->floatBR2DB($this->values['modelo_peso']);
		$sql = array();
		$sql[] = "
			INSERT INTO	tb_modelo SET
				modelo_nome = '{$this->values['modelo_nome']}'
				,tipo_modelo_id = '{$this->values['tipo_modelo_id']}'
				,cor_olho_id = '{$this->values['cor_olho_id']}'
				,modelo_ddd = '{$this->values['modelo_ddd']}'
				,modelo_telefone = '{$this->values['modelo_telefone']}'
				,modelo_email = '{$this->values['modelo_email']}'
				,modelo_site = '{$this->values['modelo_site']}'
				,modelo_idade = '{$this->values['modelo_idade']}'
				,modelo_altura = '{$this->values['modelo_altura']}'
				,modelo_peso = '{$this->values['modelo_peso']}'
				,modelo_descricao = '{$this->values['modelo_descricao']}'
				,modelo_status = '{$this->values['modelo_status']}'
		";
		if(isset($this->values['modelo_img_dest_principal'])&&trim($this->values['modelo_img_dest_principal'])!=''){
			$sql[] = ",modelo_img_dest_principal = '{$this->values['modelo_img_dest_principal']}'";
		}
		if(isset($this->values['modelo_img_dest_semana'])&&trim($this->values['modelo_img_dest_semana'])!=''){
			$sql[] = ",modelo_img_dest_semana = '{$this->values['modelo_img_dest_semana']}'";
		}
		if(isset($this->values['modelo_img_area_modelo'])&&trim($this->values['modelo_img_area_modelo'])!=''){
			$sql[] = ",modelo_img_area_modelo = '{$this->values['modelo_img_area_modelo']}'";
		}
		$result = $this->dbConn->db_execute(implode("\n",$sql));
		if($result['success']===false){
			$this->dbConn->db_rollback();
		}else{
			$this->dbConn->db_commit();
		}
		return $result;
	}
	public function deleteImg(){
		$img = $this->getOne();
		$this->dbConn->db_start_transaction();
		$sql = array();
		$sql[] = "
			UPDATE	tb_modelo SET
				{$this->values['img']} = ''
				
		";
		$sql[] = "
			WHERE	modelo_id = '{$this->values['modelo_id']}'
		";
		$result = $this->dbConn->db_execute(implode("\n",$sql));
		if($result['success']===false){
			$this->dbConn->db_rollback();
		}else{
			$this->dbConn->db_commit();
			@unlink("{$this->pathImg}".$img[$this->values['img']]);
		}
		return $result;
	}
	public function deleteItem(){
		$this->dbConn->db_start_transaction();
		$sql = array();
		$sql[] = "
			DELETE FROM tb_modelo
			WHERE modelo_id = '{$this->values['modelo_id']}'
		";
		$result = $this->dbConn->db_execute(implode("\n",$sql));
		if($result['success']===false){
			$this->dbConn->db_rollback();
		}else{
			$this->dbConn->db_commit();
		}
		return $result;
	}
	public function getTipoModelo(){
		$path_root_modeloClass = dirname(__FILE__);
		$path_root_tipoModeloLista = dirname(__FILE__);
		$DS = DIRECTORY_SEPARATOR;
		$path_root_tipoModeloLista = "{$path_root_tipoModeloLista}{$DS}..{$DS}..{$DS}";
		require_once "{$path_root_tipoModeloLista}admin{$DS}class{$DS}tipoModelo.class.php";
		$obj = new tipoModelo();
		return $obj->getLista();
	}
	public function getCorOlho(){
		$path_root_corOlhoLista = dirname(__FILE__);
		$DS = DIRECTORY_SEPARATOR;
		$path_root_corOlhoLista = "{$path_root_corOlhoLista}{$DS}..{$DS}..{$DS}";
		require_once "{$path_root_corOlhoLista}admin{$DS}class{$DS}corOlho.class.php";
		$obj = new corOlho();
		return $obj->getLista();
	}
}
?>
