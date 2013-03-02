<?php
$path_root_tipoPublicidadeLista = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_tipoPublicidadeLista = "{$path_root_tipoPublicidadeLista}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_tipoPublicidadeLista}admin{$DS}class{$DS}tipoPublicidade.class.php";
$obj = new tipoPublicidade();
switch($_REQUEST['action']){
	case 'edit-item':
		$volta = "tipoPublicidadeEdicao.php";
		if(isset($_POST['volta'])&&trim($_POST['volta'])!=''){
			$volta = $_POST['volta'];
		}
		$obj->setValues($_POST);
		$exec = $obj->edit();
		if(isset($_POST['tipo_publicidade_id']) && trim($_POST['tipo_publicidade_id'])!=''){
			if($exec['success']){
				$msg = "Tipo de Publicidade Atualizado com Sucesso!";
				$url = "{$volta}?id={$_POST['tipo_publicidade_id']}";
			}else{
				$msg = "Erro ao atualizar o tipo de publicidade!";
				$url = "{$volta}?id={$_POST['tipo_publicidade_id']}";
			}
		}else{
			if($exec['success']){
				$msg = "Tipo de Publicidade Cadastrado com Sucesso!";
				$url = "{$volta}";
			}else{
				$msg = "Erro ao cadastrar o tipo de publicidade!";
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
			$msg = "CMD_FAILED|O Item não pode ser excluído!<br />Deve existir publicidades cadastrados com esse item!";
		}
		echo $msg;
	break;
}
?>