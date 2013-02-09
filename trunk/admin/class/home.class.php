<?php
$path_root_homeClass = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_homeClass = "{$path_root_homeClass}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_homeClass}admin{$DS}class{$DS}default.class.php";
class home extends DefaultClass{
	protected $dbConn;
	
	public function __construct() {
		$this->dbConn = new DataBaseClass();
	}
}
?>
