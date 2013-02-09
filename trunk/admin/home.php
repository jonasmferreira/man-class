<?php
	$path_root_IndexLista = dirname(__FILE__);
	$DS = DIRECTORY_SEPARATOR;
	$path_root_IndexLista = "{$path_root_IndexLista}{$DS}..{$DS}";
	include_once "{$path_root_IndexLista}admin{$DS}includes{$DS}Header.php";
	include_once("{$path_root_IndexLista}admin{$DS}class{$DS}home.class.php");
	
	$objHome = new home();
	$session = $objHome->getSessions();
?>
<style type="text/css">

</style>
<script type="text/javascript" src="js/Home.js"></script>
<h3 class="ui-state-active">Home</h3>
<div class="form-main">
</div>
<?php include_once "{$path_root_IndexLista}admin{$DS}includes{$DS}Footer.php"; ?>