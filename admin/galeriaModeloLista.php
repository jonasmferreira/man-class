<?php
	$path_root_galeriaModeloLista = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_galeriaModeloLista = "{$path_root_galeriaModeloLista}{$DS}..{$DS}";
	include_once "{$path_root_galeriaModeloLista}admin{$DS}includes{$DS}Header.php";
	require_once "{$path_root_galeriaModeloLista}admin{$DS}class{$DS}galeriaModelo.class.php";
	$obj = new galeriaModelo();
	$obj->setValues($_POST);
	$aResult = $obj->getLista();
	$session = $obj->getSessions();
	$aModelos = $obj->getModelo();
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
<script type="text/javascript" src="js/galeriaModelo.js"></script>
<h3 class="ui-state-active">Modelos</h3>
<div class="form-main">
	<div class="legend">Filtro</div>
	<div class="forms filtro">
		<form action="galeriaModeloLista.php" method="post" id="formLista">
			<table width="100%">
				<tbody>
					<tr>
						<td style="width:100px">Modelo:</td>
						<td colspan="4">
							<select name="modelo_id" id="modelo_id">
								<option value="">Todos</option>
								<?	if(count($aModelos) > 0):?>
								<?		foreach($aModelos AS $v):?>
								<option value="<?=$v['modelo_id']?>"<?=($_POST['modelo_id']==$v['modelo_id'])?'selected="selected"':''?>><?=$v['modelo_nome']?></option>
								<?		endforeach;?>
								<?	endif;?>
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
<form action="galeriaModeloEdicao.php" method="post" id="formEdicao" >
	<input type="hidden" id="id" name="id" />
	<div class="form-main">
		<table id="tabela_lista">
			<thead>
				<tr>
					<th>
						<a href='galeriaModeloEdicao.php'>
							<img src="img/icon_novo.gif" alt="Adicionar novo Modelo" title="Adicionar novo Modelo" />
						</a>
					</th>
					<th>Nome</th>
					<th>Qtde Imagens</th>
					<th>Ação</th>
				</tr>
			</thead>
			<tbody>
				<?	if(count($aResult) > 0): 
						foreach($aResult AS $v):
				?>
				<tr>
					<td><?=$v['modelo_id']?></td>
					<td><?=$v['modelo_nome']?></td>
					<td><?=$v['qtde_img']?></td>
					<td class="al-c">
						<a href='javascript:void(0)' class='editItem' rel='<?=$v['modelo_id']?>'>
							<img src='img/icon_editar.gif' alt='Editar Galeria: <?=$v['modelo_nome']?>' title='Editar Galeria: <?=$v['modelo_nome']?>'/>
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
