<?php
$path_root_tipoPublicidadeEdicao = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_tipoPublicidadeEdicao = "{$path_root_tipoPublicidadeEdicao}{$DS}..{$DS}";
include_once "{$path_root_tipoPublicidadeEdicao}admin{$DS}includes{$DS}Header.php";
require_once "{$path_root_tipoPublicidadeEdicao}admin{$DS}class{$DS}tipoPublicidade.class.php";
$obj = new tipoPublicidade();
$tipoPublicidade = array();
if(isset($_REQUEST['id']) && trim($_REQUEST['id'])!=''){
	$obj->setValues(array('tipo_publicidade_id'=>$_REQUEST['id']));
	$aResult = $obj->getOne();
	$tipoPublicidade = $aResult;
}
$session = $obj->getSessions();
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
<script type="text/javascript" src="js/tipoPublicidade.js"></script>
<h3 class="ui-state-active">Tipo de Publicidade</h3>
<div class="form-main">
	<div class="legend" ><?=empty($tipoPublicidade['tipo_publicidade_id']) ? 'Cadastro' : 'Edição' ?></div>
    <div class="forms cadastros">
		<form action="controller/tipoPublicidade.controller.php?action=edit-item" method="post" id="formSalvar" >
			<input type="hidden" name="tipo_publicidade_id" id="tipo_publicidade_id" value="<?=empty($tipoPublicidade['tipo_publicidade_id']) ? '' : $tipoPublicidade['tipo_publicidade_id'] ?>" style="width: 500px" />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr>
						<td>Título:</td>
						<td><input type="text" class="obrigatorio" name="tipo_publicidade_titulo" id="tipo_publicidade_titulo" value="<?=empty($tipoPublicidade['tipo_publicidade_titulo']) ? '' : $tipoPublicidade['tipo_publicidade_titulo'] ?>" style="width: 500px" /></td>
					</tr>
					<tr>
						<td>Abreviação:</td>
						<td><input type="text" class="obrigatorio" name="tipo_publicidade_attr" id="tipo_publicidade_attr" value="<?=empty($tipoPublicidade['tipo_publicidade_attr']) ? '' : $tipoPublicidade['tipo_publicidade_attr'] ?>" style="width: 500px" /></td>
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