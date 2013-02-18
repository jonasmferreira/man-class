<?php

if (session_id() == '') {
	session_name("OSWEB_CMS_2011");
	session_start();
}
error_reporting(E_ALL & ~E_NOTICE);
$path_root_dbClass = dirname(__FILE__);
$DS = DIRECTORY_SEPARATOR;
$path_root_dbClass = "{$path_root_dbClass}{$DS}..{$DS}..{$DS}";
require_once "{$path_root_dbClass}lib{$DS}DataBaseClass.php";

class defaultClass {

	protected $dbConn;
	protected $values;
	protected $files;
	protected $sort_field;
	protected $sort_direction;
	protected $limit_start;
	protected $limit_max;
	protected $post;
	protected $uf = array(
		"AC" => "Acre"
		, "AL" => "Alagoas"
		, "AM" => "Amazonas"
		, "AP" => "Amapa"
		, "BA" => "Bahia"
		, "CE" => "Ceará"
		, "DF" => "Distrito Federal"
		, "ES" => "Espirito Santo"
		, "GO" => "Goiás"
		, "MA" => "Maranhão"
		, "MG" => "Minas Gerais"
		, "MS" => "Mato Grosso do Sul"
		, "MT" => "Mato Grosso"
		, "PA" => "Pará"
		, "PB" => "Paraíba"
		, "PE" => "Pernambuco"
		, "PI" => "Piauí"
		, "PR" => "Parana"
		, "RJ" => "Rio de Janeiro"
		, "RN" => "Rio Grande do Norte"
		, "RO" => "Rondônia"
		, "RR" => "Roraima"
		, "RS" => "Rio Grande do Sul"
		, "SC" => "Santa Catarina"
		, "SE" => "Sergipe"
		, "SP" => "São Paulo"
		, "TO" => "Tocantins"
	);
	protected $meses = array(
		"01" => "Janeiro"
		, "02" => "Fevereiro"
		, "03" => "Março"
		, "04" => "Abril"
		, "05" => "Maio"
		, "06" => "Junho"
		, "07" => "Julho"
		, "08" => "Agosto"
		, "09" => "Setembro"
		, "10" => "Outubro"
		, "11" => "Novembro"
		, "12" => "Dezembro"
	);

	public function registerSession($arr) {
		if (is_array($arr) && count($arr) > 0) {
			foreach ($arr AS $k => $v) {
				$_SESSION['OSWEB_CMS'][$k] = $v;
			}
		}
	}

	public function unRegisterSession($arr) {
		if (is_array($arr) && count($arr) > 0) {
			foreach ($arr AS $k => $v) {
				unset($_SESSION['OSWEB_CMS'][$k]);
			}
		}
	}

	public function getSessions() {
		return $_SESSION['OSWEB_CMS'];
	}

	public function verifyLogon() {
		$session = $this->getSessions();
		if (isset($session['usuario_login']) && trim($session['usuario_login']) != '') {
			header('Location: home.php');
		}
	}

	public function verifyLogin() {
		$session = $this->getSessions();
		if (!isset($session['usuario_login']) && trim($session['usuario_login']) == '') {
			header('Location: index.php');
		}
	}

	public function __construct() {
		$this->dbConn = new DataBaseClass();
	}

	public function setValues($values) {
		$this->values = $values;
	}

	public function setPost($post) {
		$this->post = $post;
	}

	public function getUf() {
		return $this->uf;
	}

	public function getMeses() {
		return $this->meses;
	}

	public function setFiles($files) {
		$this->files = $files;
	}

	public function utf8Encode2Decode($string) {
		if (strtoupper(mb_detect_encoding($string . "x", 'UTF-8, ISO-8859-1')) == 'UTF-8') {
			return str_replace(array("\'",'\"'),array("'",'"'),$string);
		} else {
			return utf8_encode(str_replace(array("\'",'\"'),array("'",'"'),$string));
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

	public function antiInjection($input) {
		if (is_array($input)) {
			foreach ($input as $key => $val) {
				$sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $val);
				$sql = trim($sql);
				$sql = (get_magic_quotes_gpc()) ? $sql : addslashes($sql);
				$input[$key] = $sql;
			}
			return $input;
		}
		$sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $input);
		$sql = trim($sql);
		$sql = (get_magic_quotes_gpc()) ? $sql : addslashes($sql);
		return $sql;
	}

	public function alert($msg, $url='') {
		$aScript = array();
		$aScript[] = '<script type="text/javascript">';
		$aScript[] = "alert('{$msg}');";
		$aScript[] = (!is_null($url) && trim($url) != '') ? "window.location.href = '{$url}';" : '';
		$aScript[] = '</script>';
		echo implode("\r\n", $aScript);
	}

	public function consoleLog($mixed='') {
		$msg = print_r($mixed, true);
		$aScript = array();
		$aScript[] = '<script type="text/javascript">';
		$aScript[] = "console.log('{$msg}');";
		$aScript[] = '</script>';
		echo implode("\r\n", $aScript);
	}

	public function debug($mixed) {
		echo "<pre>" . print_r($mixed, true) . "</pre>";
	}

	public function dateDB2BR($date, $separete="/") {
		return preg_replace(
						"/([0-9]{4})-([0-9]{2})-([0-9]{2})/i", "$3{$separete}$2{$separete}$1", $date
		);
	}

	public function dateBR2DB($date, $separete="-") {
		return preg_replace(
						"/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/i", "$3{$separete}$2{$separete}$1", $date
		);
	}

	public function dateDB2BRTime($date, $separete="/") {
		return preg_replace(
						"/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/i", "$3{$separete}$2{$separete}$1 $4:$5:$6", $date
		);
	}

	public function dateBR2DBTime($date, $separete="/") {
		return preg_replace(
						"/([0-9]{2})\/([0-9]{2})\/([0-9]{4}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/i", "$3{$separete}$2{$separete}$1 $4:$5:$6", $date
		);
	}

	public function timeSum($aHora) {
		$hora = 0;
		$minuto = 0;
		$segundo = 0;
		if (is_array($aHora) && count($aHora)) {
			foreach ($aHora as $v) {
				$tem = explode(":", $v);
				$hora+=$tem[0];
				$minuto+=$tem[1];
				$segundo+=(isset($tem[2])) ? $tem[2] : 0;
			}
		}
		$secMin = floor($segundo / 60);
		$segundo = $segundo - ($secMin * 60);

		$minuto += $secMin;
		$horaMin = floor($minuto / 60);
		$hora+= $horaMin;
		$minuto = $minuto - ($horaMin * 60);

		$hora = str_pad($hora, 2, '0', STR_PAD_LEFT);
		$minuto = str_pad($minuto, 2, '0', STR_PAD_LEFT);
		$segundo = str_pad($segundo, 2, '0', STR_PAD_LEFT);

		return "{$hora}:{$minuto}:{$segundo}";
	}

	public function SecToTime($sec, $bStyle=false) {
		$style = 'style="color:#00F !important;"';
		$sinal = '+';
		if ($sec < 0) {
			$sec = $sec * -1;
			$style = 'style="color:#F00 !important;"';
			$sinal = '-';
		}

		$sql = "SELECT SEC_TO_TIME({$sec}) AS hora";
		$qry = $this->dbConn->db_query($sql);
		$rs = $this->dbConn->db_fetch_assoc($qry['result']);
		if ($bStyle) {
			return "<span {$style}>{$sinal} {$rs['hora']}</span>";
		} else {
			return $rs['hora'];
		}
	}

	public function diasemana($data) {
		$data = explode("-", $data);
		$ano = $data[0];
		$mes = $data[1];
		$dia = $data[2];
		$diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
		switch ($diasemana) {
			case"0": $diasemana = "Domingo";
				break;
			case"1": $diasemana = "Segunda-Feira";
				break;
			case"2": $diasemana = "Terça-Feira";
				break;
			case"3": $diasemana = "Quarta-Feira";
				break;
			case"4": $diasemana = "Quinta-Feira";
				break;
			case"5": $diasemana = "Sexta-Feira";
				break;
			case"6": $diasemana = "Sábado";
				break;
		}

		echo $diasemana;
	}

	public function escape_string($string) {
		return $this->dbConn->db_escape_string($string);
	}

	public function escape_string_array_encode($input) {
		$return = array();
		foreach ($input as $key => $val) {
			if (is_array($val)) {
				$return[$key] = $this->escape_string_array_encode($val);
			} else {
				$return[$key] = $this->escape_string($val);
			}
		}
		return $return;
	}

	public function normaliza($string) {
		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$string = $this->utf8Encode2Decode($string);
		$string = strtr($string, $this->utf8Encode2Decode($a), $b);
		$string = str_replace(' ', '', $string);
		$string = strtolower($string);
		return $this->utf8Encode2Decode($string);
	}

	public function toNormaliza($string) {
		$array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
			, "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç",'º','ª','-');
		$array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
			, "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C",'','','');
		$string = str_replace(' ', '_', $string);
		$string = str_replace('/', '.', $string);
		return strtolower(str_replace($array1, $array2, $string));
	}

	public function cutHTML($text, $length=100, $ending=' ...', $cutWords=false, $considerHtml=true) {
		if ($considerHtml) {
			// se o texto for mais curto que $length, retornar o texto na totalidade
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}

			// separa todas as tags html em linhas pesquisáveis
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);

			$total_length = strlen($ending);
			$open_tags = array();
			$truncate = '';

			foreach ($lines as $line_matchings) {
				// se existir uma tag html nesta linha, considerá-la e adicioná-la ao output (sem contar com ela)
				if (!empty($line_matchings[1])) {
					// se for um "elemento vazio" com ou sem barra de auto-fecho xhtml (ex. <br />)
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
						// não fazer nada
						// se a tag for de fecho (ex. </b>)
					} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
						// apagar a tag do array $open_tags
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
							unset($open_tags[$pos]);
						}
						// se a tag é uma tag inicial (ex. <b>)
					} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
						// adicionar tag ao início do array $open_tags
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// adicionar tag html ao texto $truncate
					$truncate .= $line_matchings[1];
				}

				// calcular a largura da parte do texto da linha; considerar entidades como um caracter
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if ($total_length + $content_length > $length) {
					// o número dos caracteres que faltam
					$left = $length - $total_length;
					$entities_length = 0;
					// pesquisar por entidades html
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
						// calcular a largura real de todas as entidades no alcance "legal"
						foreach ($entities[0] as $entity) {
							if ($entity[1] + 1 - $entities_length <= $left) {
								$left--;
								$entities_length += strlen($entity[0]);
							} else {
								// não existem mais caracteres
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left + $entities_length);
					// chegamos à largura máxima, por isso saímos do loop
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}

				// se chegarmos à largura máxima, saímos do loop
				if ($total_length >= $length) {
					break;
				}
			}
		} else {
			if (strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}

		// se as palavras não puderem ser cortadas a meio...
		if (!$cutWords) {
			// ...procurar a última ocorrência de um espaço...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				// ...e cortar o texto nesta posição
				$truncate = substr($truncate, 0, $spacepos);
			}
		}

		// adicionar $ending no final do texto
		$truncate .= $ending;

		if ($considerHtml) {
			// fechar todas as tags html não fechadas
			foreach ($open_tags as $tag) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
	
	public function getSeqAleatoria(){
		$CaracteresAceitos = 'abcdxywzABCDZYWZ0123456789';
		$max = strlen($CaracteresAceitos)-1;
		$password = null;
		for($i=0; $i < 8; $i++) {
			$password .= $CaracteresAceitos{mt_rand(0, $max)};
		}
		return $password;
	}
	
	
	public function getIcon($color,$bolinha=false,$stroke_color="#FF0000"){
		$stroke_color = str_replace("#","",$stroke_color);
		$path_root_listatelClass = dirname(__FILE__);
		$DS = DIRECTORY_SEPARATOR;
		$path_root_listatelClass = "{$path_root_listatelClass}{$DS}..{$DS}..{$DS}";
		if(!is_dir("{$path_root_listatelClass}admin{$DS}img_icon{$DS}")){
			@mkdir("{$path_root_listatelClass}admin{$DS}img_icon{$DS}");
			@chmod("{$path_root_listatelClass}admin{$DS}img_icon{$DS}", 0777);
		}
		@chmod("{$path_root_listatelClass}admin{$DS}img_icon{$DS}", 0777);
		if(!$bolinha){
			if($stroke_color=="FF0000"){
				$file = str_replace("#","",$color).".png";
			}else{
				$file = str_replace("#","",$color)."_{$stroke_color}.png";
			}
			$fileName = "{$path_root_listatelClass}admin{$DS}img_icon{$DS}".$file;
			
			if(!is_file($fileName)&& !is_readable($fileName)){
				file_put_contents($fileName,file_get_contents('http://www.googlemapsmarkers.com/v1/+/'.str_replace("#","",$color)."/FFFFFF/{$stroke_color}/"));
				if(!is_file($fileName)){
					return '';
				}else{
					chmod($fileName, 0777);
				}
			}
		}else{
			$file = "bolinha_".str_replace("#","",$color).".png";
			$fileName = "{$path_root_listatelClass}admin{$DS}img_icon{$DS}".$file;
			if(!is_file($fileName)){
				$aRgb = $this->HexToRGB($color);

				$imagem = imagecreatetruecolor(8, 8);

				$transparent = imagecolorallocatealpha($imagem, 0, 0, 0, 127 );

				$cor = imagecolorallocate($imagem, $aRgb['r'], $aRgb['g'],$aRgb['b']);
				imagealphablending($imagem, false );
				imagefill($imagem, 0, 0, $transparent );
				imagesavealpha($imagem,true );
				imagealphablending($imagem, true );

				imagefilledellipse($imagem, 3,3,6,6,$cor );
				if(!imagepng($imagem,"{$fileName}")){
					return '';
				}else{
					chmod($fileName, 0777);
				}
			}
		}
		return $this->dbConn->getUrl_base()."admin/img_icon/".$file;
	}
	public function getUrl_base(){
		return $this->dbConn->getUrl_base();
	}
	public function HexToRGB($hex) {
		$hex = trim(str_replace("#", "", $hex));
		$color = array();

		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}
		return $color;
	}
 
	public function RGBToHex($r, $g, $b) {
		//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
		$hex = "#";
		$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
		return $hex;
	}
	
	public function trataCoordenadas($nCoordenada){
		$aCoordenada = explode(".",$nCoordenada);
		return $aCoordenada[0].".".  substr($aCoordenada[1], 0,6);
	}
	public function getGeocode($address){
		$arr = array();
		$arr['address'] = $address;
		$_url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=".rawurlencode($address);
		
		$_json = file_get_contents($_url);
		
		$objJson = json_decode($_json);
		if(count($objJson->results[0]->address_components) > 0){
			foreach($objJson->results[0]->address_components AS $v){
				$type = print_r($v->types[0],true);
				$long_name = print_r($v->long_name,true);
				$short_name = print_r($v->short_name,true);
				if($type!='country'){
					$arr[$type] = $long_name;
				}else{
					$arr[$type] = $short_name;
				}
			}
		}
		$arr['lat'] = $this->trataCoordenadas($objJson->results[0]->geometry->location->lat);
		$arr['lon'] = $this->trataCoordenadas($objJson->results[0]->geometry->location->lng);
		return $arr;
	}
	public function insertGeocode($arr){
		$sql = array();
		$arr = $this->getGeocode($arr['address']);
		if(trim($arr['address'])==''||trim($arr['lon'])==''||trim($arr['lat'])==''){
			return false;
		}
		$arr['address'] = $this->dbConn->db_escape_string($arr['address']);
		$sql[] = "
		INSERT INTO tb_geocode SET
			geocode_lat = '{$arr['lat']}'
			,geocode_lon = '{$arr['lon']}'
			,geocode_address = '{$arr['address']}'
		";
		if(isset($arr['street_number'])&&trim($arr['street_number'])!=''){
			$sql[] = ",geocode_street_number = '{$arr['street_number']}'";
		}
		if(isset($arr['postal_code'])&&trim($arr['postal_code'])!=''){
			$sql[] = ",geocode_postal_code = '{$arr['postal_code']}'";
		}
		if(isset($arr['country'])&&trim($arr['country'])!=''){
			$sql[] = ",geocode_country='{$arr['country']}'";
		}
		$this->dbConn->db_execute(implode("\n",$sql));
	}
	
	public function getAddressGeocode($lat,$lon){
		$sql = array();
		$sql[] = "
			SELECT	geocode_address
			FROM	tb_geocode
			WHERE	geocode_lat = '{$lat}'
			AND		geocode_lon = '{$lon}'
			ORDER BY LENGTH(geocode_address) > 0 DESC
			LIMIT 1
		";
		$result = $this->dbConn->db_query(implode("\n",$sql));
		$endereco = "";
		if($result['total'] > 0){
			$rs = $this->dbConn->db_fetch_assoc($result['result']);
			$endereco = $rs['geocode_address'];
		}
		return $endereco;
	}
	public function getLatLonByAddress($address){
		$address = $this->dbConn->db_escape_string($address);
		$sql = array();
		$sql[] = "
			SELECT	geocode_lat AS lat
					,geocode_lon AS lon
			FROM	tb_geocode
			WHERE	geocode_address LIKE '%{$address}%'
			ORDER BY  LENGTH(trim(geocode_lat)) > 0 DESC
			LIMIT 1
		";
		$result = $this->dbConn->db_query(implode("\n",$sql));
		$endereco = "";
		$rs = array();
		if($result['total'] > 0){
			$rs = $this->utf8_array_encode($this->dbConn->db_fetch_assoc($result['result']));
		}
		return $rs;
	}
	
	public function floatBR2DB($valor){
		$valor = str_replace(".","",$valor);
		$valor = str_replace(",",".",$valor);
		return $valor;
	}
	public function floatDB2BR($valor,$decimals=2){
		return number_format($valor, $decimals, ",", ".");
	}

}
require_once "{$path_root_dbClass}lib{$DS}canvas.php";
?>
