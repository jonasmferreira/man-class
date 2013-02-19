<?
	$path_root_loginPage = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_loginPage = "{$path_root_loginPage}{$DS}..{$DS}";
	require_once "{$path_root_loginPage}admin{$DS}class{$DS}login.class.php";
	$obj = new login();
	$obj->verifyLogon();
	
	$session = $obj->getSessions();
	$erro = $session['erro'];
	$aErro['erro'] =  $erro;
	$obj->unRegisterSession($aErro);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>CMS - ManClass - Login</title>
		<link href="css/login.css" rel="stylesheet" type="text/css" />
		<link href="js/css/custom-theme/jquery-ui-1.8.10.custom.css" rel="stylesheet" type="text/css"  />
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.ui.js"></script>
		
		<script type="text/javascript" src="js/utils.js"></script>
		<script type="text/javascript" src="js/index.js"></script>
	</head>
	<body>
		<div class="header">
			<img src="img/logo.png" alt="CMS - ManClass" />
		</div>
		<div class="form">
			<div class="error">
				<?=$erro?>
			</div>
			<form name="frmlogin" id="frmlogin" method="post" action="controller/login.controller.php">
				<input type="hidden" name="action" id="action" value="login" />

				<label for="user">Usuário:</label>
				<input type="text" name="user" id="user" maxlength="32" class="obrigatorio" />

				<label for="password">Senha:</label>
				<input type="password" id="pass" name="pass" maxlength="32" class="obrigatorio" />

				<input type="submit" id="enviar" name="login" value="Enviar" />
			</form>
		</div>
		<div class="footer">CMS - ManClass</div>
	</body>
</html>
