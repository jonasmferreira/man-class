<?php
	$path_root_modeloLista = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_modeloLista = "{$path_root_modeloLista}{$DS}..{$DS}";
	include_once "{$path_root_modeloLista}admin{$DS}includes{$DS}Header.php";
	require_once "{$path_root_modeloLista}admin{$DS}class{$DS}modelo.class.php";
	$obj = new modelo();
	$obj->setValues($_POST);
	$aResult = $obj->getLista();
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
	<div class="legend">Filtro</div>
	<div class="forms filtro">
		<form action="modeloLista.php" method="post" id="formLista">
			<table width="100%">
				<tbody>
					<tr>
						<td style="width:100px">Título:</td>
						<td><input type="text" name="modelo_nome" id="modelo_nome" value="<?=$_POST['modelo_nome']?>" style="width: 100%" /></td>
						<td style="width:100px">Tipo de Modelo:</td>
						<td>
							<select name="tipo_modelo_id" id="tipo_modelo_id" style="width: 100%">
								<option value="">Selecione o Tipo de Modelo</option>
								<?	if(count($aTipoModelo)>0):?>
								<?		foreach($aTipoModelo AS $v):?>
								<option value="<?=$v['tipo_modelo_id']?>"<?=($_POST['tipo_modelo_id']==$v['tipo_modelo_id'])?' selected="selected"':''?>><?=$v['tipo_modelo_titulo']?></option>
								<?		endforeach;?>
								<?	endif;?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="width:100px">Cor de Olhos:</td>
						<td>
							<select name="cor_olho_id" id="cor_olho_id" style="width: 100%">
								<option value="">Selecione a Cor</option>
								<?	if(count($aCorOlhos)>0):?>
								<?		foreach($aCorOlhos AS $v):?>
								<option value="<?=$v['cor_olho_id']?>"<?=($_POST['cor_olho_id']==$v['cor_olho_id'])?' selected="selected"':''?>><?=$v['cor_olho_titulo']?></option>
								<?		endforeach;?>
								<?	endif;?>
							</select>
						</td>
						<td style="width:100px;">Status:</td>
						<td>
							<select name="modelo_status" id="modelo_status" style="width: 100%">
								<?	if(count($aStatus)>0):?>
								<?		foreach($aStatus AS $v):?>
								<option value="<?=$v['status_id']?>"<?=($_POST['modelo_status']==$v['status_id'])?' selected="selected"':''?>><?=$v['status_titulo']?></option>
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
<form action="modeloEdicao.php" method="post" id="formEdicao" >
	<input type="hidden" id="id" name="id" />
	<div class="form-main">
		<table id="tabela_lista">
			<thead>
				<tr>
					<th>
						<a href='modeloEdicao.php'>
							<img src="img/icon_novo.gif" alt="Adicionar novo Modelo" title="Adicionar novo Modelo" />
						</a>
					</th>
					<th>Nome</th>
					<th>Tipo de Modelo</th>
					<th>Cor Olhos</th>
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
					<td><?=$v['tipo_modelo_titulo']?></td>
					<td><?=$v['cor_olho_titulo']?></td>
					<td class="al-c">
						<a href='javascript:void(0)' class='editItem' rel='<?=$v['modelo_id']?>'>
							<img src='img/icon_editar.gif' alt='Editar Modelo: <?=$v['modelo_nome']?>' title='Editar Modelo: <?=$v['modelo_nome']?>'/>
						</a>
						<a href='javascript:void(0)' class='deleteItem' rel='<?=$v['modelo_id']?>'>
							<img src='img/icon_delete.gif' alt='Excluir Modelo: <?=$v['modelo_nome']?>' title='Excluir Modelo: <?=$v['modelo_nome']?>'/>
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
