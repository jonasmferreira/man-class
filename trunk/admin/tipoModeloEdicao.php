<?php
$path_root_tipoModeloEdicao = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_tipoModeloEdicao = "{$path_root_tipoModeloEdicao}{$DS}..{$DS}";
include_once "{$path_root_tipoModeloEdicao}admin{$DS}includes{$DS}Header.php";
require_once "{$path_root_tipoModeloEdicao}admin{$DS}class{$DS}tipoModelo.class.php";
$obj = new tipoModelo();
$tipoModelo = array();
if(isset($_REQUEST['id']) && trim($_REQUEST['id'])!=''){
	$obj->setValues(array('tipo_modelo_id'=>$_REQUEST['id']));
	$aResult = $obj->getOne();
	$tipoModelo = $aResult;
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
<script type="text/javascript" src="js/tipoModelo.js"></script>
<h3 class="ui-state-active">Tipo de Modelos</h3>
<div class="form-main">
	<div class="legend" ><?=empty($tipoModelo['tipo_modelo_id']) ? 'Cadastro' : 'Edição' ?></div>
    <div class="forms cadastros">
		<form action="controller/tipoModelo.controller.php?action=edit-item" method="post" id="formSalvar" >
			<input type="hidden" name="tipo_modelo_id" id="tipo_modelo_id" value="<?=empty($tipoModelo['tipo_modelo_id']) ? '' : $tipoModelo['tipo_modelo_id'] ?>" style="width: 500px" />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr>
						<td>Título:</td>
						<td><input type="text" class="obrigatorio" name="tipo_modelo_titulo" id="tipo_modelo_titulo" value="<?=empty($tipoModelo['tipo_modelo_titulo']) ? '' : $tipoModelo['tipo_modelo_titulo'] ?>" style="width: 500px" /></td>
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