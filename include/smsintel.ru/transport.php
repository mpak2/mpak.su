<?php

define("HTTPS_ADDRESS", "https://transport.smsintel.ru:7214/"); //HTTPS-Адрес, к которому будут обращаться скрипты. Со слэшем на конце.
define("HTTP_ADDRESS", "http://lcab.smsintel.ru/API/XML/"); //HTTP-Адрес, к которому будут обращаться скрипты. Со слэшем на конце.
define("HTTPS_CHARSET", "utf-8"); //кодировка ваших скриптов. cp1251 - для Windows-1251, либо же utf-8 для, сообственно - utf-8 :)
define("HTTPS_METHOD", "curl"); //метод, которым отправляется запрос (curl)
define("USE_HTTPS", 1); //1 - использовать HTTPS-адрес, 0 - HTTP

class Transport{
	
	///Проверка баланса
	function balance(){
		return $this->get( $this->request("balance"), "account" );
	}
	
	function reports($start = "0000-00-00", $stop = "0000-00-00"){
		$result = $this->request("report", array("start" => $start, "stop" => $stop));
		if ($this->get($result, "code") != 1){
			$return =  array("code" => $this->get($result, "code"), "descr" => $this->get($result, "descr"));
		}
		else{
			$return =  array(
				"code" => $this->get($result, "code"), 
				"descr" => $this->get($result, "descr"),
			);
			if (isset($result['sms'])) $return["sms"] = $result['sms'];
		}
		return $return;
	}
	
	function detailReport($smsid){
		$result = $this->request("report", array("smsid" => $smsid));
		if ($this->get($result, "code") != 1){
			$return =  array("code" => $this->get($result, "code"), "descr" => $this->get($result, "descr"));
		}
		else{
			$detail = $result["detail"][0];
			$return =  array(
				"code" => $this->get($result, "code"), 
				"descr" => $this->get($result, "descr"),
				"delivered" => $detail['delivered'],
				"notDelivered" => $detail['notDelivered'],
				"waiting" => $detail['waiting'],
				"enqueued" => $detail['enqueued']
			);
			if (isset($result['sms'])) $return["sms"] = $result['sms'][0];
		}
		return $return;
	}
	
	//отправка смс
	//params = array (text => , source =>, datetime => , action =>, onlydelivery =>, smsid =>)
	function send($params = array(), $phones = array()){
		if (!isset($params["action"])) $params["action"] = "send";
		$someXML = "";
		foreach ($phones as $phone){
			if (is_array($phone)){
				if (isset($phone["number"])){
					$someXML .= "<to number='".$phone['number']."'>";
					if (isset($phone["text"])){
						$someXML .= $this->getConvertedString($phone["text"]);
					}
					$someXML .= "</to>";
				}
			}
			else{
				$someXML .= "<to number='$phone'></to>";
			}
		}
		$result = $this->request("send", $params, $someXML);
		if ($this->get($result, "code") != 1){
			$return =  array("code" => $this->get($result, "code"), "descr" => $this->get($result, "descr"));
		}
		else{
			$return = array(
				"code" => 1,
				"descr" => $this->get($result, "descr"),
				"datetime" => $this->get($result, "datetime"),
				"action" => $this->get($result, "action"),
				"allRecivers" => $this->get($result, "allRecivers"),
				"colSendAbonent" => $this->get($result, "colSendAbonent"),
				"colNonSendAbonent" => $this->get($result, "colNonSendAbonent"),
				"priceOfSending" => $this->get($result, "priceOfSending"),
				"colsmsOfSending" => $this->get($result, "colsmsOfSending"),
				"price" => $this->get($result,"price"),
				"smsid" => $this->get($result,"smsid"),
			);
		}
		return $return;
		
	}
	
	function get($responce, $key){
		if (isset($responce[$key], $responce[$key][0], $responce[$key][0][0])) return $responce[$key][0][0];
		return false;
	}
	
	function parseXML($xml){
		if (function_exists("simplexml_load_string"))
			return $this->XMLToArray($xml);
		else 
			return $xml;
	}
	
	function request($action,$params = array(),$someXML = ""){
		$xml = $this->makeXML($params,$someXML);
		if (HTTPS_METHOD == "curl"){
			return $this->parseXML( $this->request_curl($action,$xml) );
		}
		$this->error("В настройках указан неизвестный метод запроса - '".HTTPS_METHOD."'");
	}
	
	function request_curl($action,$xml){
        if (USE_HTTPS == 1)
            $address = HTTPS_ADDRESS.$action.".xml";
        else
            $address = HTTP_ADDRESS.$action.".php";
		$ch = curl_init($address);
		curl_setopt($ch, CURLOPT_URL, $address);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	function makeXML($params,$someXML = ""){
		global $conf;
		$xml = "<?xml version='1.0' encoding='UTF-8'?>
		<data>
			<login>".$conf['settings']['sendsms_https_login']."</login>
			<password>".$conf['settings']['sendsms_https_password']."</password>
			";
		foreach ($params as $key => $value){
			$value = $this->getConvertedString($value);
			$xml .= "<$key>$value</$key>";
		}
		$xml .= "$someXML
		</data>";
		return $xml;
	}
	
	function getConvertedString($value, $from = false){
		if (HTTPS_CHARSET != "utf-8") {
			if (function_exists("iconv")){
				if (!$from)
					return iconv(HTTPS_CHARSET,"utf-8",$value);
				else 
					return iconv("utf-8",HTTPS_CHARSET,$value);
			}
			else
				$this->error("Не удается перекодировать переданные параметры в кодировку utf-8 - отсутствует функция iconv");
		}
		return $value;
	}
	
	function error($text){
		die($text);
	}	
	
	function XMLToArray($xml){
        if (!strlen($xml)) {
            $descr = "Не удалось получить ответ от сервера!";
            if (USE_HTTPS == 1){
                $descr .= " Возможно конфигурация вашего сервера не позволяет отправлять HTTPS-запросы. Попробуйте установить значение USE_HTTPS = 0 в файле config.php";
            }
            return array("code" => 0, "descr" => $descr);
        }
		$xml = simplexml_load_string($xml);
		
		$return = array();
		foreach($xml->children() as $child)
	  	{
	  		$return[$child->getName()][] = $this->makeAssoc((array)$child);
		}
		$return = $this->convertArrayCharset($return);
		return $return;
	}
	
	function convertArrayCharset($return){
		foreach ($return as $key => $value){
			if (is_array($value)) $return[$key] = $this->convertArrayCharset($return[$key]);
			else $return[$key] = $this->getConvertedString($value, true);
		}
		return $return;
	}

	
	function makeAssoc($array){
		if (is_array($array))
			foreach ($array as $key => $value){
				if (is_object($value)) {
					$newValue = array();
					foreach($value->children() as $child)
				  	{
				  		$newValue[] = (string)$child;
					}
					$array[$key] = $newValue;
				}
			}
		else $array = (string)$array;
		
		return $array;
	}
	
	function __construct(){
//		require_once("config.php");
//		if (!defined("HTTPS_LOGIN")) $this->error("Не удалось подключить конфигурационный файл. Проверьте путь до файла config.php и права на него");
	}
}
?>
