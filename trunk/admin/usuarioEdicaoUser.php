<?php
$path_root_UsuarioLista = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_UsuarioLista = "{$path_root_UsuarioLista}{$DS}..{$DS}";
include_once "{$path_root_UsuarioLista}admin{$DS}includes{$DS}Header.php";
require_once "{$path_root_UsuarioLista}admin{$DS}class{$DS}usuario.class.php";
$obj = new usuario();
$usuario = array();
$aNivel = $obj->getNivel();
$aStatus = $obj->getStatus();
if(isset($_REQUEST['id']) && trim($_REQUEST['id'])!=''){
	$obj->setValues(array('usuario_id'=>$_REQUEST['id']));
	$aResult = $obj->getOne();
	$usuario = $aResult;
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
<script type="text/javascript" src="js/usuario.js"></script>
<h3 class="ui-state-active">Alterar Dados</h3>
<div class="form-main">
	<div class="legend" ><?=empty($usuario['usuario_id']) ? 'Cadastro' : 'Edição' ?></div>
    <div class="forms cadastros">
		<form action="controller/usuario.controller.php?action=edit-item" method="post" id="formSalvar" >
			<input type="hidden" name="usuario_id" id="usuario_id" value="<?=empty($usuario['usuario_id']) ? '' : $usuario['usuario_id'] ?>" style="width: 500px" />
			<input type="hidden" name="usuario_nivel_id" id="usuario_nivel_id" value="<?=empty($usuario['usuario_nivel_id']) ? '' : $usuario['usuario_nivel_id'] ?>" style="width: 500px" />
			<input type="hidden" name="usuario_status_id" id="usuario_status_id" value="<?=empty($usuario['usuario_status_id']) ? '' : $usuario['usuario_status_id'] ?>" style="width: 500px" />
			<input type="hidden" name="volta" id="volta" value="usuarioEdicaoUser.php" style="width: 500px" />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr>
						<td>Nome:</td>
						<td><input type="text" class="obrigatorio" name="usuario_nome" id="usuario_nome" value="<?=empty($usuario['usuario_nome']) ? '' : $usuario['usuario_nome'] ?>" style="width: 500px" /></td>
					</tr>
					<tr>
						<td>Login:</td>
						<td><input type="text" class="obrigatorio" name="usuario_login" id="usuario_login" value="<?=empty($usuario['usuario_login']) ? '' : $usuario['usuario_login'] ?>" style="width: 500px" /></td>
					</tr>
					<tr>
						<td><?=empty($usuario['usuario_id']) ? 'Senha:' : 'Nova Senha:' ?></td>
						<td><input type="password" class="<?=empty($usuario['usuario_id']) ? 'obrigatorio' : '' ?>" name="usuario_senha" id="usuario_senha" value="" style="width: 500px" /></td>
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