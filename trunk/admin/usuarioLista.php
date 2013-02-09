<?php
	$path_root_UsuarioLista = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_UsuarioLista = "{$path_root_UsuarioLista}{$DS}..{$DS}";
	include_once "{$path_root_UsuarioLista}admin{$DS}includes{$DS}Header.php";
	require_once "{$path_root_UsuarioLista}admin{$DS}class{$DS}usuario.class.php";
	$obj = new usuario();
	$obj->setValues($_POST);
	$aResult = $obj->getLista();
	$session = $obj->getSessions();
	$aStatus = array(
		'1'=>'Ativo'
		,'0'=>'Inativo'
	);
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
	$aNivel = $obj->getNivel();
	$aStatus = $obj->getStatus();
?>
<script type="text/javascript" src="js/usuario.js"></script>
<h3 class="ui-state-active">Usuários</h3>
<div class="form-main">
	<div class="legend">Filtro</div>
	<div class="forms filtro">
		<form action="usuarioLista.php" method="post" id="formLista">
			<table width="100%">
				<tbody>
					<tr>
						<td style="width:100px">Nome:</td>
						<td><input type="text" name="usuario_nome" id="usuario_nome" value="<?=$_POST['usuario_nome']?>" /></td>
						<td style="width:100px">Login:</td>
						<td><input type="text" name="usuario_login" id="usuario_login" value="<?=$_POST['usuario_login']?>" /></td>
					</tr>
					<tr>
						<td>Nivel:</td>
						<td>
							<select name="usuario_nivel_id" id="usuario_nivel_id">
								<option value="">Selecione um nivel</option>
								<?	foreach($aNivel AS $k=> $v):?>
								<option value="<?=$v['usuario_nivel_id']?>" <?=$_POST['usuario_nivel_id'] == $v['usuario_nivel_id'] ? "selected='selected'" : '' ?>><?=$v['usuario_nivel_titulo']?></option>
								<?	endforeach;?>
							</select>
							
						</td>
						<td>Status:</td>
						<td>
							<select name="usuario_status_id" id="usuario_status_id">
								<option value="">Selecione um status</option>
								<?	foreach($aStatus AS $v):?>
								<option value="<?=$v['usuario_status_id']?>" <?=$_POST['usuario_status_id'] == $v['usuario_status_id'] ? "selected='selected'" : '' ?>><?=$v['usuario_status_titulo']?></option>
								<?	endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<button id="filtrar">Filtrar</button>
							<button id="limpar">Limpar</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<form action="usuarioEdicao.php" method="post" id="formEdicao" >
	<input type="hidden" id="id" name="id" />
	<div class="form-main">
		<table id="tabela_lista">
			<thead>
				<tr>
					<th>
						<a href='usuarioEdicao.php'>
							<img src="img/icon_novo.gif" alt="Adicionar novo Usuario" title="Adicionar novo Usuario" />
						</a>
					</th>
					<th>Nome</th>
					<th>Login</th>
					<th>Nivel</th>
					<th>Status</th>
					<th>Ação</th>
				</tr>
			</thead>
			<tbody>
				<?	if(count($aResult) > 0): 
						foreach($aResult AS $v):
				?>
				<tr>
					<td><?=$v['usuario_id']?></td>
					<td><?=$v['usuario_nome']?></td>
					<td><?=$v['usuario_login']?></td>
					<td><?=$v['usuario_nivel_titulo']?></td>
					<td><?=$v['usuario_status_titulo']?></td>
					<td class="al-c">
						<a href='javascript:void(0)' class='editItem' rel='<?=$v['usuario_id']?>'>
							<img src='img/icon_editar.gif' alt='Editar Usuário: <?=$v['usuario_nome']?>' title='Editar Usuário: <?=$v['usuario_nome']?>'/>
						</a>
						<a href='javascript:void(0)' class='deleteItem' rel='<?=$v['usuario_id']?>'>
							<img src='img/icon_delete.gif' alt='Excluir Usuário: <?=$v['usuario_nome']?>' title='Excluir Usuário: <?=$v['usuario_nome']?>'/>
						</a>
					</td>
				</tr>
				<?		endforeach;
					endif;?>
			</tbody>
		</table>
	</div>
</form>
<?php include_once 'includes/Footer.php'; ?>
