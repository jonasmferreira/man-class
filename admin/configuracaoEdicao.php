<?php
$path_root_ConfiguracaoEdicao = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_ConfiguracaoEdicao = "{$path_root_ConfiguracaoEdicao}{$DS}..{$DS}";
include_once "{$path_root_ConfiguracaoEdicao}admin{$DS}includes{$DS}Header.php";
require_once "{$path_root_ConfiguracaoEdicao}admin{$DS}class{$DS}configuracao.class.php";
$obj = new configuracao();
$configuracao = array();

$obj->setValues(array('configuracao_id'=>1));
$aResult = $obj->getOne();
$configuracao = $aResult;

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
<script type="text/javascript" src="js/configuracao.js"></script>
<h3 class="ui-state-active">Configurações</h3>
<div class="form-main">
	<div class="legend" ><?=empty($configuracao['configuracao_id']) ? 'Cadastro' : 'Edição' ?></div>
    <div class="forms cadastros">
		<form action="controller/configuracao.controller.php?action=edit-item" method="post" id="formSalvar"   enctype="multipart/form-data">
			<input type="hidden" name="configuracao_id" id="configuracao_id" value="<?=empty($configuracao['configuracao_id']) ? '' : $configuracao['configuracao_id'] ?>" style="width: 500px" />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr>
						<td>Base Url CKfinder:</td>
						<td><input type="text" class="obrigatorio" name="configuracao_baseurl_ckfinder" id="configuracao_baseurl_ckfinder" value="<?=empty($configuracao['configuracao_baseurl_ckfinder']) ? '' : $configuracao['configuracao_baseurl_ckfinder'] ?>" style="width: 500px" /></td>
					</tr>
				</tbody>
			</table><br clear="all" />
		</form>
		<div class="botao">
			<button id="salvar">Salvar</button>
		</div>
    </div>
</div>
<?php include_once 'includes/Footer.php'; ?>