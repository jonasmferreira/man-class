<?php
class DataBaseClass{
	private $dbChaveReferencia = ""; //CHAVE QUE IDENTIFICA OS OBJETOS QUE COMPARTILHAM A MESMA CONEXÃO

    private $dbConnection = null;
    private $dbHost = "localhost";
	private $dbUser = "root";
	private $dbPassword = "";
	private $dbName = "manclass";
	
	/*NO ar:*/
	/*private $dbHost = "dbmy0014.whservidor.com";
    private $dbUser = "movieco_1";
    private $dbPassword = "movieco2011site";
    private $dbName = "movieco_1";*/
	
	
	private $dbDriver = "mysql";

    public $refdir = ""; //INCLUIDO POR MARCELO RAMOS EM 17/05/2010
    public $SCRIPT_NAME = "";
	private $DS = "";
	private $info="";
	private $line="";
	private $file="";
	private $tempoExec = "";
	private $function="";
	private $stmt = null;
	private $log = true;
    
	protected $url_base="";

    private $aIniFile = Array();
	
	public function getUrl_base() {
		return $this->url_base;
	}
	public function  __construct($host="",$user="",$pass="",$db_name="",$driver='mysql') {
		$path_root_dbClass = dirname(__FILE__);
		$this->DS = DIRECTORY_SEPARATOR;
		$path_root_dbClass="{$path_root_dbClass}{$this->DS}";
		$this->refdir = $path_root_dbClass;

		$host=($host=="")?$this->dbHost:$host;
		$user=($user=="")?$this->dbUser:$user;
		$pass=($pass=="")?$this->dbPassword:$pass;
		$db_name=($db_name=="")?$this->dbName:$db_name;
		$driver=($driver=="")?$this->dbDriver:$driver;

		switch($driver){
			case 'mysql':
			case 'mysqli':
				$dsn = "mysql:dbname={$db_name};host={$host}";
			break;
		}
		
		try {
			$this->dbConnection = new PDO($dsn, $user, $pass);
			$this->dbChaveReferencia = $this->dbCriarChave();
			$this->dbHost = $host;
			$this->dbUser = $user;
			$this->dbPassword = $pass;
			$this->dbName = $db_name;
			$this->dbDriver = $driver;
			
			$result = $this->db_query("SELECT * FROM tb_configuracao WHERE configuracao_id = '1'");
			$rs = $this->db_fetch_assoc($result['result']);
			$this->url_base = $rs['configuracao_base_url'];
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
		
	}
	public function dbCriarChave(){
		/*
		** METODO QUE CRIA UMA CHAVE DE IDENTIFICA��O QUE � PASSADA AO OBJETO
		*/
		$strChave = "";
		$signos = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$tamanho_chave = rand(4,20);
		for($x = 0; $x < $tamanho_chave; $x++){
			$randIndice = rand(0, strlen($signos));
			$strChave .= $signos[$randIndice];
		}

		return $strChave;
	} //MÉTODO dbCriarChave
	public function getChave(){
		return $this->dbChaveReferencia;
	}

	public function db_log($fileName, $sql, $status, $total=null, $message=null){
		$fileName = $this->refdir . preg_replace("/^(\.\.\/)+/", "", $fileName);
		if(!is_dir("{$this->refdir}..{$this->DS}log")){
			@mkdir("{$this->refdir}..{$this->DS}log",0777);
		}
		@chmod("{$this->refdir}..{$this->DS}log", 0777);
		$fileName = "{$this->refdir}..{$this->DS}log{$this->DS}dblog" . "_" . date("YmdHi") . ".txt";
		$datetimeLog = date("d/m/Y H:i:s");
		$sql = trim($sql);
		$msg = array();
		$msg[] = "*************************************************************************************";
		$msg[] = $this->utf8Encode2Decode("Data: {$datetimeLog}");
		$msg[] = $this->utf8Encode2Decode("Arquivo: {$this->file} [{$this->line}]");
		$msg[] = $this->utf8Encode2Decode("Função: {$this->function}");
		$msg[] = $this->utf8Encode2Decode("Query: {$sql}");
		$msg[] = $this->utf8Encode2Decode("Resultado: [{$total}] Registros ");
		$msg[] = $this->utf8Encode2Decode("{$status} {$message}");
		$msg[] = $this->utf8Encode2Decode("Tempo de execução: {$this->tempoExec}");
		$msg[] = "\n";
		@chmod($fileName, 0777);
		if($this->log){
			file_put_contents($fileName, implode("\r\n",$msg), FILE_APPEND);
		}
		@chmod($fileName, 0777);

	}
	public function utf8Encode2Decode($string)
	{
		if(mb_detect_encoding($string."x", 'UTF-8, ISO-8859-1') == 'UTF-8'){
			return $string;
		}else{
			return utf8_encode($string);
		}
	}

	public function utf8_array_encode($input) {
		$return = array();
		foreach ($input as $key => $val) {
			if (is_array($val)) {
				$return[$key] = $this->utf8_array_encode($val);
			} else {
				$return[$key] = $this->utf8Encode2Decode($val);
			}
		}
		return $return;
	}

	public function db_query($sql){
		$query_time = microtime(true);
		$result = array(
			'success' => false,
			'result' => null,
			'total' => 0,
			'message' => ''
		);
        $qry = $this->dbConnection->query($sql);
		$this->tempoExec = number_format(microtime(true) - $query_time, 4)." segundos";
		$this->info = debug_backtrace();
		$this->line = $this->info[0]['line'];
		$this->file = $this->info[0]['file'];
		if(array_key_exists('class',$this->info[0])) {
			$this->function = "{$this->info[1]['class']}{$this->info[1]['type']}{$this->info[1]['function']}";
			if(count($this->info[1]['args']) > 0){
				$this->function .= "(".implode(',',$this->info[1]['args']).")";
			}
		}else {
			$this->function = $this->info[1]['function'];
			if(count($this->info[1]['args']) > 0){
				$this->function .= "(".implode(',',$this->info[1]['args']).")";
			}
		}

        if($qry!==false) {
            $result['success'] = true;
            $result['result'] = $qry;
            $result['message'] = '';
            $result['total'] = $this->db_num_rows($qry);
			$this->db_log('../log/dblog.txt', $sql, 'Query executada com sucesso', $result['total'], $result['message']);
        } else{
            $result['success'] = false;
            $result['message'] = $this->db_error();
            $result['result'] = array();
            $result['total'] = 0;
			$this->db_log('../log/dblog.txt', $sql, 'Erro executando query: ', 0, $result['message']);
        }
        return $result;
	}

	public function db_execute($sql){
		$query_time = microtime(true);
		$result = array(
			'success' => false,
			'result' => array(),
			'total' => 0,
			'message' => '',
			'last_id' => 0
		);

		$qry = $this->dbConnection->query($sql);
		$this->tempoExec = number_format(microtime(true) - $query_time, 4)." segundos";
		$this->info = debug_backtrace();
		$this->line = $this->info[0]['line'];
		$this->file = $this->info[0]['file'];
		if(array_key_exists('class',$this->info[0])) {
			$this->function = "{$this->info[1]['class']}{$this->info[1]['type']}{$this->info[1]['function']}";
			if(count($this->info[1]['args']) > 0){
				$this->function .= "(".implode(',',$this->info[1]['args']).")";
			}
		}else {
			$this->function = $this->info[1]['function'];
			if(count($this->info[1]['args']) > 0){
				$this->function .= "(".implode(',',$this->info[1]['args']).")";
			}
		}
		if ($qry) {
			if(stripos($sql,'DELETE')===false){
				$id = $this->db_last_insert_id();
			}else{
				$id=0;
			}
			$result['success'] = true;
			$result['message'] = '';
			$result['total'] = $this->db_num_rows($qry);
			$result['last_id'] = $id;
			$this->db_log('../log/dblog.txt', $sql, 'Query executada com sucesso', $result['total'], $result['message']);
		} else{
			$result['success'] = false;
			$result['message'] =  $this->db_error_execute();
			$result['total'] = 0;
			$result['last_id'] = 0;
			$this->db_log('../log/dblog.txt', $sql, 'Erro executando query: ', 0, $result['message']);

		}

		return $result;
	}

	public function db_last_insert_id(){
		$sql = "SELECT LAST_INSERT_ID() AS ID;";
		$qry = $this->dbConnection->query($sql);
		$rs = $qry->fetch(PDO::FETCH_ASSOC);
		return $rs['ID'];

	}

	public function setErrorCode($code){
        $this->error_code = $code;
    }//MÉTODO setErrorCode

    public function setErrorState($state){
        $this->error_state = $state;
    }//MÉTODO setErrorState

    public function setErrorMessage($message){
        $this->error_message = $message;
    }//MÉTODO setErrorMessage

    public function getErrorCode(){
        return ($this->error_code!=0) ? $this->error_code : 0;
    }//MÉTODO getErrorCode

    public function getErrorState(){
        return ($this->error_state!=0) ? $this->error_state : 0;
    }//MÉTODO getErrorState

    public function getErrorMessage(){
        return ($this->error_message!='') ? $this->error_message : '';
    }//MÉTODO getErrorMessage

    public function getFullError(){
        $aErro = Array(
            'code' => 0,
            'state' => 0,
            'message' => ''
        );
        $aErro['code']=$this->getErrorCode();
        $aErro['state']=$this->getErrorState();
        $aErro['message']=$this->getErrorMessage();
        return $aErro;
    }//MÉTODO getFullError

	public function db_start_transaction(){
		$this->dbConnection->beginTransaction();
	}
	public function db_commit(){
		$this->dbConnection->commit();
	}
	public function db_rollback(){
		$this->dbConnection->rollback();
	}

	public function db_error(){
		$arr = $this->dbConnection->errorInfo();
	    $erro[]= str_replace(',',' ','Code: ' . $arr[0]);
	    $erro[]= str_replace(',',' ','  / State: ' . $arr[1]);
	    $erro[]= str_replace(',',' ','<br/>Message: ' . $arr[2]);
		//tratando a virgula para evitar erro no json_encode!
		$this->setErrorCode($arr[0]);
		$this->setErrorState($arr[1]);
		$this->setErrorMessage($arr[2]);
		return trim(implode("",$erro));
	}
	public function db_error_execute(){
		$arr = $this->dbConnection->errorInfo();
	    $erro[]= str_replace(',',' ','Code: ' . $arr[0]);
	    $erro[]= str_replace(',',' ','  / State: ' . $arr[1]);
	    $erro[]= str_replace(',',' ','<br/>Message: ' . $arr[2]);
		//tratando a virgula para evitar erro no json_encode!
		$this->setErrorCode($arr[0]);
		$this->setErrorState($arr[1]);
		$this->setErrorMessage($arr[2]);
		return trim(implode("",$erro));
	}

	public function db_fetch_array($qry){
		return $qry->fetch(PDO::FETCH_BOTH);
	}
	public function db_fetch_assoc($qry){
		return $qry->fetch(PDO::FETCH_ASSOC);
	}
	public function db_fetch_row($qry){
		return $qry->fetch(PDO::FETCH_NUM);
	}
	public function db_fetch_object($qry){
		return $qry->fetch(PDO::FETCH_OBJ);
	}
	public function db_fetch_all_array($qry){
		return $qry->fetchAll(PDO::FETCH_BOTH);
	}
	public function db_fetch_all_assoc($qry){
		return $qry->fetchAll(PDO::FETCH_ASSOC);
	}
	public function db_fetch_all_row($qry){
		return $qry->fetchAll(PDO::FETCH_NUM);
	}
	public function db_fetch_all_object($qry){
		return $qry->fetchAll(PDO::FETCH_OBJ);
	}

	public function db_num_rows($qry){
		$qtde = $qry->rowCount();
		/*if($qtde<1){
			$qry = $this->dbConnection->query("SELECT FOUND_ROWS()");
			$qtde = count($qry->fetchAll());
		}*/
		return $qtde;
	}

	public function db_escape_string($value){
		$link = mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword) OR die(mysql_error());
		return mysql_real_escape_string($value);
	}

	public function array_escape_string($input) {
		$return = array();
		foreach ($input as $key => $val) {
			if (is_array($val)) {
				$return[$key] = $this->array_escape_string($val);
			} else {
				$return[$key] = $this->db_escape_string($val);
			}
		}
		return $return;
	}
}
