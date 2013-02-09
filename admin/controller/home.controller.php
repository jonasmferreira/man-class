<?php
$path_root_homeControl = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_homeControl = "{$path_root_homeControl}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_homeControl}admin{$DS}class{$DS}os.class.php";

$obj = new os();
switch($_REQUEST['action']){
	case 'getPoints':
	break;
}
?>