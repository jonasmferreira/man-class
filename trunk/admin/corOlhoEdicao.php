<?php
$path_root_corOlhoEdicao = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_corOlhoEdicao = "{$path_root_corOlhoEdicao}{$DS}..{$DS}";
include_once "{$path_root_corOlhoEdicao}admin{$DS}includes{$DS}Header.php";
require_once "{$path_root_corOlhoEdicao}admin{$DS}class{$DS}corOlho.class.php";
$obj = new corOlho();
$corOlho = array();
if(isset($_REQUEST['id']) && trim($_REQUEST['id'])!=''){
	$obj->setValues(array('cor_olho_id'=>$_REQUEST['id']));
	$aResult = $obj->getOne();
	$corOlho = $aResult;
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
<script type="text/javascript" src="js/corOlho.js"></script>
<h3 class="ui-state-active">Cor de Olhos</h3>
<div class="form-main">
	<div class="legend" ><?=empty($corOlho['cor_olho_id']) ? 'Cadastro' : 'Edição' ?></div>
    <div class="forms cadastros">
		<form action="controller/corOlho.controller.php?action=edit-item" method="post" id="formSalvar" >
			<input type="hidden" name="cor_olho_id" id="cor_olho_id" value="<?=empty($corOlho['cor_olho_id']) ? '' : $corOlho['cor_olho_id'] ?>" style="width: 500px" />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr>
						<td>Título:</td>
						<td><input type="text" class="obrigatorio" name="cor_olho_titulo" id="cor_olho_titulo" value="<?=empty($corOlho['cor_olho_titulo']) ? '' : $corOlho['cor_olho_titulo'] ?>" style="width: 500px" /></td>
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