<?php
$path_root_DefaultClass = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_DefaultClass = "{$path_root_DefaultClass}{$DS}..{$DS}";
	require_once "{$path_root_DefaultClass}admin{$DS}class{$DS}default.class.php";
	$obj = new DefaultClass();
	$session = $obj->getSessions();
	$obj->unRegisterSession($session);
	$obj->verifyLogin();
?>