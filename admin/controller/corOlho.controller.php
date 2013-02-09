<?php
$path_root_corOlhoLista = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_corOlhoLista = "{$path_root_corOlhoLista}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_corOlhoLista}admin{$DS}class{$DS}corOlho.class.php";
$obj = new corOlho();
switch($_REQUEST['action']){
	case 'edit-item':
		$volta = "corOlhoEdicao.php";
		if(isset($_POST['volta'])&&trim($_POST['volta'])!=''){
			$volta = $_POST['volta'];
		}
		$obj->setValues($_POST);
		$exec = $obj->edit();
		if(isset($_POST['cor_olho_id']) && trim($_POST['cor_olho_id'])!=''){
			if($exec['success']){
				$msg = "Cor de Olhos Atualizado com Sucesso!";
				$url = "{$volta}?id={$_POST['cor_olho_id']}";
			}else{
				$msg = "Erro ao atualizar o cor de olhos!";
				$url = "{$volta}?id={$_POST['cor_olho_id']}";
			}
		}else{
			if($exec['success']){
				$msg = "Cor de Olhos Cadastrado com Sucesso!";
				$url = "{$volta}";
			}else{
				$msg = "Erro ao cadastrar o cor de olhos!";
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
