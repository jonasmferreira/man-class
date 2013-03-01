<?php
$path_root_galeriaModeloLista = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_galeriaModeloLista = "{$path_root_galeriaModeloLista}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_galeriaModeloLista}admin{$DS}class{$DS}galeriaModelo.class.php";
$obj = new galeriaModelo();
switch($_REQUEST['action']){
	case 'edit-item':
		$volta = "galeriaModeloEdicao.php";
		if(isset($_POST['volta'])&&trim($_POST['volta'])!=''){
			$volta = $_POST['volta'];
		}
		//$obj->debug($_FILES);
		//die();
		$obj->setValues($_POST);
		$obj->setFiles($_FILES);
		$exec = $obj->edit();
		if($exec['success']){
			$msg = "Galeria Atualizada com Sucesso!";
			$url = "{$volta}?id={$_POST['modelo_id']}";
		}else{
			$msg = "Erro ao atualizar a galeria!";
			$url = "{$volta}?id={$_POST['modelo_id']}";
		}
		$obj->registerSession(array('erro'=>$msg));
		header("Location: ../{$url}");
	break;
	case 'deleteItem':
		$obj->setValues($_REQUEST);
		$exec = $obj->deleteItem();
		if($exec['success']){
			$msg = "CMD_SUCCESS|Item Excluido com Sucesso!";
		}else{
			$msg = "CMD_FAILED|O Item não pode ser excluído!<br />Deve existir modelos cadastrados com esse item!";
		}
		echo $msg;
	break;
	case 'deleteImg':
		$obj->setValues($_REQUEST);
		$exec = $obj->deleteImg();
		if($exec['success']){
			$msg = "CMD_SUCCESS|Imagem Excluido com Sucesso!";
		}else{
			$msg = "CMD_FAILED|Erro ao tentar excluir imagem";
		}
		echo $msg;
	break;
	case 'verImagem':
		$obj->setValues($_REQUEST);
		$exec = $obj->getOne(false);
		$img = $obj->getUrl_base()."imgModelo/modelo_{$exec['modelo_id']}/".$exec['galeria_imagem_img'];
		echo $img;
	break;
	case 'novaImagem':
?>
	<tr>
						<td>
							<input type="hidden" name="galeria_imagem_id[]" class="galeria_imagem_id" value="" />
							<a href='javascript:void(0)' class="deleteItem">
								<img src="img/icon_delete.gif" alt="Excluir Imagem" title="Excluir Imagem" />
							</a>
						</td>
						<td><input type="text" name="galeria_imagem_titulo[]" class="galeria_imagem_titulo obrigatorio" value="" /></td>
						<td><input type="text" name="galeria_imagem_descricao[]" class="galeria_imagem_descricao obrigatorio" value="" /></td>
						<td><input type="file" name="galeria_imagem_thumb[]" class="galeria_imagem_thumb obrigatorio" /></td>
						<td><input type="file" name="galeria_imagem_img[]" class="galeria_imagem_img obrigatorio" /></td>
						<td>
							<a href="javascript:void(0);" class="verImg" attr="">(Ver Imagem)</a>
						</td>
					</tr>
<?		
	break;
}

?>
