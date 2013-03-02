<?php
	$path_root_DefaultClass = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_DefaultClass = "{$path_root_DefaultClass}{$DS}..{$DS}..{$DS}";
	require_once "{$path_root_DefaultClass}admin{$DS}class{$DS}default.class.php";
	$obj = new DefaultClass();
	$obj->verifyLogin();
	$session = $obj->getSessions();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>CMS - ManClass</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery.loadmask.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.filter.css" />
		<link href="js/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen" />
		<!-- dataTable css -->
		<link rel="stylesheet" href="css/demo_table_jui.css">
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/i18n/jquery.ui.datepicker-pt-BR.js"></script>
		<script type="text/javascript" src="js/themeroller.js"></script>
		<script type="text/javascript" src="js/jquery.resizeTable.js"></script>
		<script type='text/javascript' src='js/jquery.loadmask.js'></script>
		<script type="text/javascript" src="js/jquery.multiselect.js"></script>
		<script type="text/javascript" src="js/jquery.multiselect.br.js"></script>
		<script type="text/javascript" src="js/jquery.multiselect.filter.js"></script>
		<script type="text/javascript" src="js/jquery.meiomask.min.js"></script>
		<script type="text/javascript" src="js/izzyColor.js"></script>
		<script type="text/javascript" src="../lib/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="../lib/ckeditor/adapters/jquery.js"></script>
		<script type="text/javascript" src="../lib/ckfinder/ckfinder.js"></script>
		<script src="js/jqueryFileTree.js" type="text/javascript"></script>
		<!-- dataTable -->
		<script src="js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
		<script src="js/gmaps.js" type="text/javascript"></script>
		
		<script type="text/javascript" src="js/funcoes.js"></script>
	</head>
	<body>
		<div style="text-align: center;">
			<div id="main">
				<div id="header">
					<div id="logo"><h1>CMS - ManClass</h1></div>
					<div id="status">
						Usuario Logado: <span class="titulo"><?php echo $session['usuario_nome'] ?></span> |
						<span class="titulo">
							<a href='javascript:void(0)' title='Sair' class='logoff' >Sair</a>
						</span>
						<!--div id="tema"></div -->
					</div>
				</div>
				<div id="main_content">
					<div id="menu">
						<div id="accordion">
							<h3><a href="#section1">CMS - ManClass</a></h3>
							<div>
								<ul>
									<li><a href="home.php">Home</a></li>
									<!--li><a href="homeLocalizacao.php">Ultimas Localizações</a></li -->
								</ul>
							</div>
							<h3><a href="#section2">Cadastros Básicos</a></h3>
							<div>
								<ul>
									<li><a href="corOlhoLista.php">Cor de Olhos</a></li>
									<li><a href="tipoModeloLista.php">Tipo de Modelos</a></li>
								</ul>
							</div>
							<h3><a href="#section3">Modelos</a></h3>
							<div>
								<ul>
									<li><a href="modeloLista.php">Modelos</a></li>
									<li><a href="galeriaModeloLista.php">Galeria de Imagens</a></li>
								</ul>
							</div>
							<h3><a href="#section4">Publicidade</a></h3>
							<div>
								<ul>
									<li><a href="tipoPublicidadeLista.php">Tipo de Publicidade</a></li>
									<li><a href="publicidadeLista.php">Publicidade</a></li>
								</ul>
							</div>
							
							<h3><a href="#section10">Usuários</a></h3>
							<div>
								<ul>
									<?	if($session['usuario_nivel_id'] == '2'):?>
									<li><a href="usuarioEdicaoUser.php?id=<?=$session['usuario_id']?>">Alterar Dados</a></li>
									<?	else:?>
									<li><a href="usuarioLista.php">Usuários</a></li>
									<?	endif;?>
								</ul>
							</div>
							<?	if($session['usuario_nivel_id']=='3'):?>
							<h3><a href="#section11">Configuração</a></h3>
							<div>
								<ul>
									<li><a href="configuracaoEdicao.php">Configurações</a></li>
								</ul>
							</div>
							<?	endif;?>
						</div>
					</div>
					<div id="content">