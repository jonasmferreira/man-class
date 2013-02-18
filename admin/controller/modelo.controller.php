<?php
$path_root_modeloLista = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_modeloLista = "{$path_root_modeloLista}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_modeloLista}admin{$DS}class{$DS}modelo.class.php";
$obj = new modelo();
switch($_REQUEST['action']){
	case 'edit-item':
		$volta = "modeloEdicao.php";
		if(isset($_POST['volta'])&&trim($_POST['volta'])!=''){
			$volta = $_POST['volta'];
		}
		$obj->setValues($_POST);
		$obj->setFiles($_FILES);
		$exec = $obj->edit();
		if(isset($_POST['modelo_id']) && trim($_POST['modelo_id'])!=''){
			if($exec['success']){
				$msg = "Modelo Atualizado com Sucesso!";
				$url = "{$volta}?id={$_POST['modelo_id']}";
			}else{
				$msg = "Erro ao atualizar o modelo!";
				$url = "{$volta}?id={$_POST['modelo_id']}";
			}
		}else{
			if($exec['success']){
				$msg = "Modelos Cadastrado com Sucesso!";
				$url = "{$volta}";
			}else{
				$msg = "Erro ao cadastrar o modelos!";
				$url = "{$volta}";
			}
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
		$exec = $obj->getOne();
		$img = $obj->getUrl_base()."imgModelo/".$exec[$_REQUEST['img']];
		echo $img;
	break;
}

?>
