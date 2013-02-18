<?php
$path_root_tipoModeloLista = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_tipoModeloLista = "{$path_root_tipoModeloLista}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_tipoModeloLista}admin{$DS}class{$DS}tipoModelo.class.php";
$obj = new tipoModelo();
switch($_REQUEST['action']){
	case 'edit-item':
		$volta = "tipoModeloEdicao.php";
		if(isset($_POST['volta'])&&trim($_POST['volta'])!=''){
			$volta = $_POST['volta'];
		}
		$obj->setValues($_POST);
		$exec = $obj->edit();
		if(isset($_POST['tipo_modelo_id']) && trim($_POST['tipo_modelo_id'])!=''){
			if($exec['success']){
				$msg = "Tipo de Modelos Atualizado com Sucesso!";
				$url = "{$volta}?id={$_POST['tipo_modelo_id']}";
			}else{
				$msg = "Erro ao atualizar o tipo de modelos!";
				$url = "{$volta}?id={$_POST['tipo_modelo_id']}";
			}
		}else{
			if($exec['success']){
				$msg = "Tipo de Modelos Cadastrado com Sucesso!";
				$url = "{$volta}";
			}else{
				$msg = "Erro ao cadastrar o tipo de modelos!";
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
}

?>
