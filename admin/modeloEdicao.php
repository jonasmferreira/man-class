<?php
$path_root_modeloEdicao = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_modeloEdicao = "{$path_root_modeloEdicao}{$DS}..{$DS}";
include_once "{$path_root_modeloEdicao}admin{$DS}includes{$DS}Header.php";
require_once "{$path_root_modeloEdicao}admin{$DS}class{$DS}modelo.class.php";
$obj = new modelo();
$modelo = array();
if(isset($_REQUEST['id']) && trim($_REQUEST['id'])!=''){
	$obj->setValues(array('modelo_id'=>$_REQUEST['id']));
	$aResult = $obj->getOne();
	$modelo = $aResult;
}
$session = $obj->getSessions();
$aCorOlhos = $obj->getCorOlho();
$aTipoModelo = $obj->getTipoModelo();
$aStatus = $obj->getStatus();
if(trim($session['erro'])!='' && isset($session['erro'])){
?>
	<script type="text/javascript">
		newAlert('<?=$session['erro']?>');
	</script>
<?
	$erro = $session['erro'];
	$aErro['erro'] =  $erro;
	$obj->unRegisterSession($aErro);
}

?>
<script type="text/javascript" src="js/modelo.js"></script>
<h3 class="ui-state-active">Modelos</h3>
<div class="form-main">
	<div class="legend" ><?=empty($modelo['modelo_id']) ? 'Cadastro' : 'Edição' ?></div>
    <div class="forms cadastros">
		<form action="controller/modelo.controller.php?action=edit-item" method="post" id="formSalvar" enctype="multipart/form-data">
			<input type="hidden" name="modelo_id" id="modelo_id" value="<?=empty($modelo['modelo_id']) ? '' : $modelo['modelo_id'] ?>" style="width: 500px" />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr>
						<td style="width:120px;">Nome:</td>
						<td colspan="3"><input type="text" class="obrigatorio" name="modelo_nome" id="modelo_nome" value="<?=$modelo['modelo_nome']?>" style="width: 100%" /></td>
					</tr>
					<tr>
						<td style="width:120px;">Idade:</td>
						<td><input type="text" class="obrigatorio idade" name="modelo_idade" id="modelo_idade" value="<?=$modelo['modelo_idade']?>" style="width: 100%" /></td>
						<td style="width:120px;">Altura:</td>
						<td><input type="text" class="obrigatorio altura" name="modelo_altura" id="modelo_altura" value="<?=$modelo['modelo_altura']?>" style="width: 100%" /></td>
					</tr>
					<tr>
						<td style="width:120px;">Cor de Olhos:</td>
						<td>
							<select name="cor_olho_id" id="cor_olho_id" class="obrigatorio" style="width: 100%">
								<option value="">Selecione a Cor</option>
								<?	if(count($aCorOlhos)>0):?>
								<?		foreach($aCorOlhos AS $v):?>
								<option value="<?=$v['cor_olho_id']?>"<?=($modelo['cor_olho_id']==$v['cor_olho_id'])?' selected="selected"':''?>><?=$v['cor_olho_titulo']?></option>
								<?		endforeach;?>
								<?	endif;?>
							</select>
						</td>
						<td style="width:120px;">Tipo de modelo:</td>
						<td>
							<select name="tipo_modelo_id" id="tipo_modelo_id" class="obrigatorio" style="width: 100%">
								<option value="">Selecione o Tipo de Modelo</option>
								<?	if(count($aTipoModelo)>0):?>
								<?		foreach($aTipoModelo AS $v):?>
								<option value="<?=$v['tipo_modelo_id']?>"<?=($modelo['tipo_modelo_id']==$v['tipo_modelo_id'])?' selected="selected"':''?>><?=$v['tipo_modelo_titulo']?></option>
								<?		endforeach;?>
								<?	endif;?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="width:120px;">Peso:</td>
						<td><input type="text" class="obrigatorio peso" name="modelo_peso" id="modelo_peso" value="<?=$modelo['modelo_peso']?>" style="width: 100%" /></td>
						<td style="width:120px;">Telefone:</td>
						<td>
							(<input type="text" class="obrigatorio idade" name="modelo_ddd" id="modelo_ddd" value="<?=$modelo['modelo_ddd']?>" style="width: 20%" />)&nbsp;
							<input type="text" class="obrigatorio celular" name="modelo_telefone" id="modelo_telefone" value="<?=$modelo['modelo_telefone']?>" style="width: 75%" />
						</td>
					</tr>
					<tr>
						<td style="width:120px;">E-mail:</td>
						<td><input type="text" class="obrigatorio" name="modelo_email" id="modelo_email" value="<?=$modelo['modelo_email']?>" style="width: 100%" /></td>
						<td style="width:120px;">Site:</td>
						<td><input type="text" class="obrigatorio" name="modelo_site" id="modelo_site" value="<?=$modelo['modelo_site']?>" style="width: 100%" /></td>
					</tr>
					<tr>
						<td style="width:120px;">Destaque Principal: <br /><a href="javascript:void(0);" class="verImg" attr="modelo_img_dest_principal">(Ver Imagem)</a></td>
						<td><input type="file" class="" name="modelo_img_dest_principal" id="modelo_img_dest_principal"  style="width: 100%" /></td>
						<td style="width:120px;">Img. Área Modelo: <br /> <a href="javascript:void(0);" class="verImg" attr="modelo_img_area_modelo">(Ver Imagem)</a></td>
						<td><input type="file" class="" name="modelo_img_area_modelo" id="modelo_img_area_modelo"  style="width: 100%" /></td>
					</tr>
					<tr>
						<td style="width:120px;">Destaque Semana: <br /> <a href="javascript:void(0);" class="verImg" attr="modelo_img_dest_semana">(Ver Imagem)</a></td>
						<td><input type="file" class="" name="modelo_img_dest_semana" id="modelo_img_dest_semana"  style="width: 100%" /></td>
						<td style="width:120px;">Status:</td>
						<td>
							<select name="modelo_status" id="modelo_status" class="obrigatorio" style="width: 100%">
								<?	if(count($aStatus)>0):?>
								<?		foreach($aStatus AS $v):?>
								<option value="<?=$v['status_id']?>"<?=($modelo['modelo_status']==$v['status_id'])?' selected="selected"':''?>><?=$v['status_titulo']?></option>
								<?		endforeach;?>
								<?	endif;?>
							</select>
							
						</td>
					</tr>
					<tr>
						<td colspan="4">Descrição:</td>
					</tr>
					<tr>
						<td colspan="4">
							<textarea class="obrigatorio editor" name="modelo_descricao" id="modelo_descricao" style="width:100%;height:150px"><?=$modelo['modelo_descricao']?></textarea>
						</td>
					</tr>
				</tbody>
			</table><br clear="all" />
		</form>

		<div class="botao">
			<button id="salvar">Salvar</button>
			<button id="limparCadastro">Limpar</button>
			<button id="voltar">Voltar</button>
		</div>
    </div>
</div>
<?php include_once 'includes/Footer.php'; ?>