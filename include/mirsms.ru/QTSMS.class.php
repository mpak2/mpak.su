<?php
##########################################################
// Класс предназначен для отправки СМС через систему
/* v. 1.6 */

class QTSMS {
	var $user='xxxx';			// ваш логин в системе 
	var $pass='xxx';			// ваш пароль в системе
	var $hostname='host';		// host замените на адрес сервера указанный в меню "Поддержка -> протокол HTTP"  без префикса http://
	var $path='/public/http/';
	var $on_ssl=0; 				// 1 - использовать HTTPS соединение, 0 - HTTP 
	
	var $post_data=array();			// данные передаваемые на сервер
	var $multipost=false;			// множественный запрос по умолчанию false
	
	function __construct($user=false,$pass=false,$hostname=false) {
		if($user) $this->user=$user;
		if($pass) $this->pass=$pass;
		if($hostname) $this->hostname=$hostname;
	}
	
	// команда на начало мульти запроса
	function start_multipost() {
		$this->multipost=true;
	}
	// сбор данных запроса
	function to_multipost($inv) {
		$this->post_data['data'][]=$inv;
	}
	// результирующий запрос на сервер и получение результата
	function process() {
		return $this->get_post_request($this->post_data);
	}
	################# post_message
	// рассылка смс [mes] по телефонам [target] с возвратом результата XML
	function post_message($mes,$target,$sender=null,$post_id=null,$period=false) {
		if(is_array($target))	$target=implode(',',$target);
		return $this->post_mes($mes,$target,false,$sender,$post_id,$period);
	}
	// рассылка смс [mes] по кодовому имени контакт листа [phl_codename]
	function post_message_phl($mes,$phl_codename,$sender=null,$post_id=null,$period=false) {
		return $this->post_mes($mes,false,$phl_codename,$sender,$post_id,$period);
	}	
	
	################# post_mms_message
	// рассылка MMS [mes] по телефонам [target] с возвратом результата XML
	function post_mms_message($subj,$mes,$f_data,$target,$post_id=null) {
		if(is_array($target))	$target=implode(',',$target);
		return $this->post_mms($subj,$mes,$f_data,$target,false);
	}
	// рассылка смс [mes] по кодовому имени контакт листа [phl_codename]
	function post_mms_message_phl($subj,$mes,$f_data,$phl_codename,$post_id=null) {
		return $this->post_mms($subj,$mes,$f_data,false,$phl_codename);
	}
	
	function post_mes($mes,$target,$phl_codename,$sender,$post_id=null,$period=false) {
		$in=array(
			'action' => 'post_sms',
			'message' => $mes,
			'sender' => $sender,
		);
		if($target) $in['target']=$target;
		if($phl_codename) $in['phl_codename']=$phl_codename;
		if($post_id) $in['post_id']=$post_id;
		if($period) $in['period']=$period;
		if($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}
	function post_mms( $subject, $txt, $file_data, $target, $phl_codename=null,$post_id=null) {
		$in=array(
			'action' => 'post_mms',
			'message' => $txt,
			'subject' => $subject
		);
		if($target) $in['target']=$target;
		if($phl_codename) $in['phl_codename']=$phl_codename;	
		if($post_id) $in['post_id']=$post_id;
		return $this->get_multipost_request($in,$file_data);		
	}
	################# status_sms
	/*	получение стстуса смс 
		допустимые параметры:
		1.	date_from
			date_to
		2.	sms_id	
		3.	sms_group_id	*/
	function status_sms_id($sms_id) {
		return $this->status_sms(false,false,false,false,$sms_id);
	}
	function status_sms_group_id($sms_group_id) {
		return $this->status_sms(false,false,false,$sms_group_id,false);
	}
	function status_sms_date($date_from,$date_to,$smstype='SENDSMS') {
		return $this->status_sms($date_from,$date_to,$smstype,false,false);
	}
	function status_sms($date_from,$date_to,$smstype,$sms_group_id,$sms_id) {
		$in=array('action' => 'status');
		if($date_from)		$in['date_from']=$date_from;
		if($date_to)		 $in['date_to']=$date_to;
		if($smstype)		$in['smstype']=$smstype;
		if($sms_group_id)	$in['sms_group_id']=$sms_group_id;
		if($sms_id)			$in['sms_id']=(string)$sms_id;
		if($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}
	################ get_balance
	function get_balance() {
		$in=array('action' => 'balance');
		if($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}
	
	################ inbox_sms
	function inbox_sms($new_only=false,$sib_num=false,$date_from=false,$date_to=false,$phone=false,$prefix=false) {
		$in=array('action' => 'inbox');
		if($new_only)    $in['new_only']=$new_only;
		if($sib_num)     $in['sib_num']=$sib_num;
		if($date_from)      $in['date_from']=$date_from;
		if($date_to)      $in['date_to']=$date_to;
		if($phone)      $in['phone']=$phone;
		if($prefix)      $in['prefix']=$prefix;
		if($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}
	################################################
	// запрос на сервер и получение результата
	function get_post_request($invars) {
		$invars['user'] = ($this->user);
		$invars['pass'] = ($this->pass);
		$invars['CLIENTADR'] = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;
		$invars['HTTP_ACCEPT_LANGUAGE'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])?$_SERVER['HTTP_ACCEPT_LANGUAGE']:false;
		$PostData=http_build_query($invars,'','&');
		$len=strlen($PostData);
		$nn="\r\n";
		$send="POST ".$this->path." HTTP/1.0".$nn."Host: ".$this->hostname."".$nn."Content-Type: application/x-www-form-urlencoded".$nn."Content-Length: $len".$nn."User-Agent: AISMS PHP class".$nn.$nn.$PostData;
		flush();
		if(($fp = @fsockopen(($this->on_ssl?'ssl://':'').$this->hostname, ($this->on_ssl?'443':'80'), $errno, $errstr, 30))!==false) {
			fputs($fp,$send);
			$header='';
			do { 
				$header.= fgets($fp, 4096);
			} while (strpos($header,"\r\n\r\n")===false);
			if(get_magic_quotes_runtime())	$header=$this->decode_header(stripslashes($header));
			else							$header=$this->decode_header($header);
			
			$body='';
			while (!feof($fp))	
				$body.=fread($fp,8192);
			if(get_magic_quotes_runtime())	$body=$this->decode_body($header, stripslashes($body));
			else							$body=$this->decode_body($header, $body);
			
			fclose($fp);
			return $body;
			
		} else
			return 'Невозможно соединиться с сервером.';
	}	
	
	################################################
	// запрос на сервер и получение результата для MMS
	function get_multipost_request($invars,$file_data) {
		define("CRLF", "\r\n");
		define("DCRLF", CRLF.CRLF);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
		$fieldsData = "";
		
		$invars['user'] = ($this->user);
		$invars['pass'] = ($this->pass);
		$invars['CLIENTADR'] = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;
		$invars['HTTP_ACCEPT_LANGUAGE'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])?$_SERVER['HTTP_ACCEPT_LANGUAGE']:false;
		foreach($invars as $field => $value) {
			$fieldsData .=  "--".$boundary.CRLF;
			$fieldsData .=  "Content-Disposition: form-data; name=\"".$field."\"".DCRLF;
			$fieldsData .=  $value.CRLF;
		}
		if( isset( $file_data["name"] ) && $file_data["name"] != "" ) {
			$fileHeaders = "--".$boundary.CRLF;
			$fileHeaders .= "Content-Disposition: form-data; name=\"files\"; filename=\"".$file_data["name"]."\"".CRLF;
			$fileHeaders .= "Content-Type: text/plain".DCRLF;
			$fileHeadersTail = CRLF."--".$boundary."--".CRLF;		
		}
		else $fileHeaders = "";
		
		$filesize = isset( $file_data["name"] ) && $file_data["name"] != "" ? filesize($file_data["path"]) : 0;
		$contentLength = strlen($fieldsData) + strlen($fileHeaders) + $filesize + strlen($fileHeadersTail);

		$headers  = "POST ".$this->path." HTTP/1.0".CRLF;
		$headers .= "Host: ".$this->hostname.CRLF;
		$headers .= "Referer: ".$this->hostname.CRLF;
		$headers .= "Content-type: multipart/form-data, boundary=".$boundary.CRLF;
		$headers .= "Content-length: ".$contentLength.CRLF;
		$headers .= "User-Agent: AISMS PHP class".DCRLF;
		$headers .= $fieldsData;
		$headers .= $fileHeaders;

		if(($fp = @fsockopen(($this->on_ssl?'ssl://':'').$this->hostname, ($this->on_ssl?'443':'80'), $errno, $errstr, 30))!==false) {
			fputs($fp,$headers);
			$header = "";
			$fp2 = fopen($file_data["path"], "rb"); //пытаемся открыть передаваемый файл
			if(!$fp2) return "Невозможно открыть файл {$file_data['path']}";
			
			while(!feof($fp2)) 
				fputs($fp, fgets($fp2, 1024*100));
			fclose($fp2);			
			fputs($fp, $fileHeadersTail);
			
			do { 
				$header.= fgets($fp, 4096);
			} while (strpos($header,"\r\n\r\n")===false);				
			
			if(get_magic_quotes_runtime())	$header=$this->decode_header(stripslashes($header));
			else							$header=$this->decode_header($header);
			
			$body='';
			while (!feof($fp))	
				$body.=fread($fp,8192);
			if(get_magic_quotes_runtime())	$body=$this->decode_body($header, stripslashes($body));
			else							$body=$this->decode_body($header, $body);
			
			fclose($fp);
			return $body;
			
		} else
			return 'Невозможно соединиться с сервером.';
	}
	
	function decode_header ($str) {
		$part = preg_split ( "/\r?\n/", $str, -1, PREG_SPLIT_NO_EMPTY);
		$out = array ();
		for ($h=0;$h<sizeof($part);$h++) {
		if ($h!=0) {
			$pos = strpos($part[$h],':');
			$k = strtolower ( str_replace (' ', '', substr ($part[$h], 0, $pos )));
			$v = trim(substr($part[$h], ($pos + 1)));
		} else {
			$k = 'status';
			$v = explode (' ',$part[$h]);
			$v = $v[1];
		}
		if ($k=='set-cookie') {
			$out['cookies'][] = $v;
		} else
			if ($k=='content-type') {
				if (($cs = strpos($v,';')) !== false) {
					$out[$k] = substr($v, 0, $cs);
				} else {
					$out[$k] = $v;
				}
			} else {
				$out[$k] = $v;
			}
		}
		return $out;
	}
	
	function decode_body($info,$str,$eol="\r\n" ) {
		$tmp=$str;
		$add=strlen($eol);
		if (isset($info['transfer-encoding']) && $info['transfer-encoding']=='chunked') {
			$str='';
			do {
				$tmp=ltrim($tmp);
				$pos=strpos($tmp, $eol);
				$len=hexdec(substr($tmp,0,$pos));
				if (isset($info['content-encoding'])) {
					$str.=gzinflate(substr($tmp,($pos+$add+10),$len));
				} else {
					$str.=substr($tmp,($pos+$add),$len);
				}
				$tmp = substr($tmp,($len+$pos+$add));
				$check = trim($tmp);
			} while(!empty($check));
		} elseif (isset($info['content-encoding'])) {
			$str=gzinflate(substr($tmp,10));
		}
		return $str;
	}
}

if(!function_exists('http_build_query')) {
	function http_build_query($data,$prefix='',$sep='',$key='') {
		$ret = array(); 
		foreach ((array)$data as $k => $v) { 
			if (is_int($k) && $prefix != null) $k = urlencode($prefix . $k); 
			if ((!empty($key)) || ($key === 0))  $k = $key.'['.urlencode($k).']'; 
			if (is_array($v) || is_object($v)) array_push($ret, http_build_query($v, '', $sep, $k)); 
			else array_push($ret, $k.'='.urlencode($v)); 
		} 
		if (empty($sep)) $sep = ini_get('arg_separator.output'); 
		return implode($sep, $ret); 
	};
};
