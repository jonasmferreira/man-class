<?php
	$path_root_corOlhoLista = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_corOlhoLista = "{$path_root_corOlhoLista}{$DS}..{$DS}";
	include_once "{$path_root_corOlhoLista}admin{$DS}includes{$DS}Header.php";
	require_once "{$path_root_corOlhoLista}admin{$DS}class{$DS}corOlho.class.php";
	$obj = new corOlho();
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
<script type="text/javascript" src="js/corOlho.js"></script>
<h3 class="ui-state-active">Cor de Olhos</h3>
<div class="form-main">
	<div class="legend">Filtro</div>
	<div class="forms filtro">
		<form action="corOlhoLista.php" method="post" id="formLista">
			<table width="100%">
				<tbody>
					<tr>
						<td style="width:100px">Título:</td>
						<td><input type="text" name="cor_olho_titulo" id="cor_olho_titulo" value="<?=$_POST['cor_olho_titulo']?>" /></td>
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
<form action="corOlhoEdicao.php" method="post" id="formEdicao" >
	<input type="hidden" id="id" name="id" />
	<div class="form-main">
		<table id="tabela_lista">
			<thead>
				<tr>
					<th>
						<a href='corOlhoEdicao.php'>
							<img src="img/icon_novo.gif" alt="Adicionar novo Cor de Olhos" title="Adicionar novo Cor de Olhos" />
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
					<td><?=$v['cor_olho_id']?></td>
					<td><?=$v['cor_olho_titulo']?></td>
					<td class="al-c">
						<a href='javascript:void(0)' class='editItem' rel='<?=$v['cor_olho_id']?>'>
							<img src='img/icon_editar.gif' alt='Editar Cor de Olhos: <?=$v['cor_olho_titulo']?>' title='Editar Cor de Olhos: <?=$v['cor_olho_titulo']?>'/>
						</a>
						<a href='javascript:void(0)' class='deleteItem' rel='<?=$v['cor_olho_id']?>'>
							<img src='img/icon_delete.gif' alt='Excluir Cor de Olhos: <?=$v['cor_olho_titulo']?>' title='Excluir Cor de Olhos: <?=$v['cor_olho_titulo']?>'/>
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
