<?php
	$path_root_loginControl = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_loginControl = "{$path_root_loginControl}{$DS}..{$DS}..{$DS}";
	require_once "{$path_root_loginControl}admin{$DS}class{$DS}login.class.php";

	$obj = new login();
	switch($_REQUEST['action']){
		case 'login':
			$obj->setPost($_POST);
			
			if($obj->logon()){
				$session = $obj->getSessions();
				header('Location: ../home.php');
			}else{
				$obj->registerSession(array("erro"=>'Usuário e/ou senha inválido'));
				header('Location: ../index.php');
			}
		break;
	}
?>
