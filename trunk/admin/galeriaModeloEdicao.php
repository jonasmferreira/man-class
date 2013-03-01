<?php
$path_root_modeloEdicao = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_modeloEdicao = "{$path_root_modeloEdicao}{$DS}..{$DS}";
include_once "{$path_root_modeloEdicao}admin{$DS}includes{$DS}Header.php";
require_once "{$path_root_modeloEdicao}admin{$DS}class{$DS}galeriaModelo.class.php";
$obj = new galeriaModelo();
$modelo = array();
if(isset($_REQUEST['id']) && trim($_REQUEST['id'])!=''){
	$obj->setValues(array('modelo_id'=>$_REQUEST['id']));
	$aResult = $obj->getOne();
	$modelo = $aResult;
}
$session = $obj->getSessions();
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
<script type="text/javascript" src="js/galeriaModelo.js"></script>
<h3 class="ui-state-active">Galeria de Imagens - Modelo: <?=$modelo[0]['modelo_nome']?></h3>
<div class="form-main">
	<div class="legend" ><?=empty($modelo[0]['modelo_id']) ? 'Cadastro' : 'Edição' ?></div>
    <div class="forms cadastros">
		<div class="botao">
			<button class="salvar">Salvar</button>
			<button class="limparCadastro">Limpar</button>
			<button class="voltar">Voltar</button>
		</div>
		<form action="controller/galeriaModelo.controller.php?action=edit-item" method="post" id="formSalvar" enctype="multipart/form-data">
			<input type="hidden" name="modelo_id" id="modelo_id" value="<?=empty($modelo[0]['modelo_id']) ? '' : $modelo[0]['modelo_id'] ?>" style="width: 500px" />
			<table width="100%" cellpadding="0" cellspacing="0" border="0" id="tableGaleria">
				<thead>
					<th style="width:18px">
						<a href='javascript:void(0)' id="novaImagem">
							<img src="img/icon_novo.gif" alt="Adicionar nova Imagem" title="Adicionar nova Imagem" />
						</a>
					</th>
					<th style="width:150px">Titulo</th>
					<th style="width:150px">Descrição</th>
					<th>Thumb</th>
					<th>Imagem</th>
					<th style="width:150px">Visualizar Imagem</th>
				</thead>
				<tbody>
					<?	if(is_array($modelo)&&count($modelo) > 0):?>
					<?		foreach($modelo AS $v):
								if(trim($v['galeria_imagem_id'])==""){
									continue;
								}
					?>
					<tr>
						<td>
							<input type="hidden" name="galeria_imagem_id[]" class="galeria_imagem_id" value="<?=$v['galeria_imagem_id']?>" />
							<a href='javascript:void(0)' class="deleteItem">
								<img src="img/icon_delete.gif" alt="Excluir Imagem" title="Excluir Imagem" />
							</a>
						</td>
						<td><input type="text" name="galeria_imagem_titulo[]" class="galeria_imagem_titulo obrigatorio" value="<?=$v['galeria_imagem_titulo']?>" /></td>
						<td><input type="text" name="galeria_imagem_descricao[]" class="galeria_imagem_descricao obrigatorio" value="<?=$v['galeria_imagem_descricao']?>" /></td>
						<td><input type="file" name="galeria_imagem_thumb[]" class="galeria_imagem_thumb" /></td>
						<td><input type="file" name="galeria_imagem_img[]" class="galeria_imagem_img" /></td>
						<td>
							<a href="javascript:void(0);" class="verImg" attr="">(Ver Imagem)</a>
						</td>
					</tr>
					<?		endforeach;?>
					<?	endif;?>
				</tbody>
			</table><br clear="all" />
		</form>

		<div class="botao">
			<button class="salvar">Salvar</button>
			<button class="limparCadastro">Limpar</button>
			<button class="voltar">Voltar</button>
		</div>
    </div>
</div>
<?php include_once 'includes/Footer.php'; ?>