<?php
	$path_root_tipoModeloLista = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_tipoModeloLista = "{$path_root_tipoModeloLista}{$DS}..{$DS}";
	include_once "{$path_root_tipoModeloLista}admin{$DS}includes{$DS}Header.php";
	require_once "{$path_root_tipoModeloLista}admin{$DS}class{$DS}tipoModelo.class.php";
	$obj = new tipoModelo();
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
?>
<script type="text/javascript" src="js/tipoModelo.js"></script>
<h3 class="ui-state-active">Tipo de Modelos</h3>
<div class="form-main">
	<div class="legend">Filtro</div>
	<div class="forms filtro">
		<form action="tipoModeloLista.php" method="post" id="formLista">
			<table width="100%">
				<tbody>
					<tr>
						<td style="width:100px">Título:</td>
						<td><input type="text" name="tipo_modelo_titulo" id="tipo_modelo_titulo" value="<?=$_POST['tipo_modelo_titulo']?>" /></td>
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
<form action="tipoModeloEdicao.php" method="post" id="formEdicao" >
	<input type="hidden" id="id" name="id" />
	<div class="form-main">
		<table id="tabela_lista">
			<thead>
				<tr>
					<th>
						<a href='tipoModeloEdicao.php'>
							<img src="img/icon_novo.gif" alt="Adicionar novo Tipo de Modelos" title="Adicionar novo Tipo de Modelos" />
						</a>
					</th>
					<th>Titulo</th>
					<th>Ação</th>
				</tr>
			</thead>
			<tbody>
				<?	if(count($aResult) > 0): 
						foreach($aResult AS $v):
				?>
				<tr>
					<td><?=$v['tipo_modelo_id']?></td>
					<td><?=$v['tipo_modelo_titulo']?></td>
					<td class="al-c">
						<a href='javascript:void(0)' class='editItem' rel='<?=$v['tipo_modelo_id']?>'>
							<img src='img/icon_editar.gif' alt='Editar Tipo de Modelos: <?=$v['tipo_modelo_titulo']?>' title='Editar Tipo de Modelos: <?=$v['tipo_modelo_titulo']?>'/>
						</a>
						<a href='javascript:void(0)' class='deleteItem' rel='<?=$v['tipo_modelo_id']?>'>
							<img src='img/icon_delete.gif' alt='Excluir Tipo de Modelos: <?=$v['tipo_modelo_titulo']?>' title='Excluir Tipo de Modelos: <?=$v['tipo_modelo_titulo']?>'/>
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
