<?php
$path_root_UsuarioLista = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_UsuarioLista = "{$path_root_UsuarioLista}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_UsuarioLista}admin{$DS}class{$DS}usuario.class.php";
$obj = new usuario();
switch($_REQUEST['action']){
	case 'edit-item':
		$volta = "usuarioEdicao.php";
		if(isset($_POST['volta'])&&trim($_POST['volta'])!=''){
			$volta = $_POST['volta'];
		}
		$obj->setValues($_POST);
		$exec = $obj->edit();
		if(isset($_POST['usuario_id']) && trim($_POST['usuario_id'])!=''){
			if($exec['success']){
				$msg = "Usuário Atualizado com Sucesso!";
				$url = "{$volta}?id={$_POST['usuario_id']}";
			}else{
				$msg = "Erro ao atualizar o usuário!";
				$url = "{$volta}?id={$_POST['usuario_id']}";
			}
		}else{
			if($exec['success']){
				$msg = "Usuário Cadastrado com Sucesso!";
				$url = "{$volta}";
			}else{
				$msg = "Erro ao cadastrar o usuário!";
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
			$msg = "CMD_FAILED|Não é possivel excluir itens que possuem associações<br/>(galeria, links externos, downloads, etc)<br /> ou que tenham galerias ativas!<br />Remova as galerias e associações e então tente novamente!";
		}
		echo $msg;
	break;
}

?>
