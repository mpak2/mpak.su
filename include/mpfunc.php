<?

function mpsmtp($to, $obj, $text){
	global $conf;
	ini_set("include_path", ini_get("include_path"). ":". "./include/mail/");

	include_once("PEAR.php");
	include_once("Mail.php");

	$param = explode("@", $conf['settings']['smtp']);
	$host = explode(":", array_pop($param));
	$auth = explode(":", implode("@", $param));

	$mail = Mail::factory(
		'smtp',
		(!empty($smtp) ? $smtp : array (
			'host' => $host[0],
			'port' => $host[1],
			'auth' => true,
			'password' => $auth[1],
			'username' => $auth[0],
			'timeout'=>10,
		))
	);

	if (PEAR::isError($mail)){
		$return = $mail->getMessage();
	} $m = $mail->send( $to,
			(!empty($smtp) ? $smtp : array (
				'From' => $auth[0],
				'To' => $to,
				'Subject' => '=?UTF-8?B?'.base64_encode($obj).'?=',
				'Content-Type' =>'text/html; charset=UTF-8',
			)), $text);
	if (PEAR::isError($m)){
		$return = $m->getMessage();
	}else{
		$return = 0;
	} mpevent("SMTP электронное сообщение", $to, $conf['user']['uid'], func_get_args(), $return);
	return $return;
}

function mpue($name){
	return str_replace('%', '%25', $name);
}

function mpmc($key, $data = null, $compress = 0, $limit = 86400, $event = true){
	global $conf;
	if(!function_exists('memcache_connect')) return false;
	$memcache = memcache_connect("localhost", 11211);
	if($data){
		memcache_set($memcache, $key, $data, $compress, $limit);
//		if($event) mpevent($conf['settings']['users_event_memcache_set'], $key, $conf['user']['uid']);
	}else{
		$mc = memcache_get($memcache, $key);
//		if($event) mpevent($conf['settings']['users_event_memcache_get'], $key, $conf['user']['uid']);
	} return $mc;
}

function mprb($arr, $key = 'id'){
	foreach($arr as $k=>$v){
		if(empty($ar[ $v[$key] ])){
			foreach($v as $n=>$z){
				$ar[ $z[$key] ] = $z;
			}
		}else{
			mpre($v);
			$ar[ $v[$key] ] = $v;
		}
	} return $ar;
}

function mpde($string) { 
	static $list = array('utf-8', 'windows-1251');
	foreach ($list as $item) {
		$sample = @iconv($item, $item, $string);
		if (md5($sample) == md5($string))
			return $item;
	} return null;
}

function mpfdk($tn, $find, $insert = array(), $update = array()){
	global $conf, $arg;
	if($find && ($fnd = mpdbf($tn, $find, 1)) &&
		($sel = mpqn(mpqw($sql = "SELECT id FROM `". mpquot($tn). "` WHERE ". $fnd)))
	){
		if((count($sel) == 1) && ($s = array_shift($sel))){
			if($update && ($upd = mpdbf($tn, $update)))
				mpqw($sql = "UPDATE `". mpquot($tn). "` SET {$upd} WHERE id=". (int)$s['id']);
			return $s['id'];
		}else{
			if($update && ($upd = mpdbf($tn, $update)))
				mpqw($sql = "UPDATE `". mpquot($tn). "` SET {$upd} WHERE id IN (". implode(",", array_keys($sel)). ")");
			return array_keys($sel);
		}
	}elseif($insert){
		mpqw($sql = "INSERT INTO `". mpquot($tn). "` SET ". mpdbf($tn, $insert));
		return $sel['id'] = mysql_insert_id();
	}else{
//		mpre($find);
	}
}

function mpdk($tn, $insert, $update = array()){
	global $conf, $arg;
	if($ins = mpdbf($tn, $insert)){
		$upd = mpdbf($tn, $update);
		foreach(mpql(mpqw("SHOW COLUMNS FROM $tn")) as $k=>$v){
			$fields[$v['Field']] = $v['Type'];
		}
		if("SELECT id FROM `". mpquot($tn). "` WHERE ")
		mpqw("INSERT INTO `". mpquot($tn). "` SET $ins ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)". ($update ? ", $upd" : ""));
		return mysql_insert_id();
	}
}

function mpevent($name, $description = null, $own = null){
	global $conf;
	if(empty($name)){
		$debug_backtrace = debug_backtrace();
		if($args = $debug_backtrace[1]['args'][0]){
			$src = "/{$args['modpath']}:{$args['fn']}". ($args['blocknum'] ? "/blocknum:{$args['blocknum']}" : "");
			mpevent("Неизвестное событие", $src, $conf['user']['uid'], $debug_backtrace);
		} return false;
	}
	$func_get_args = func_get_args(); mpre($func_get_args);
	$debug_backtrace = debug_backtrace();
	if(!empty($debug_backtrace[1]['args'][0]) && ($param = $debug_backtrace[1]['args'][0]) && $param['modpath']){
		$desc = "{$param['modpath']}:{$param['fn']}";
	}else{
		$desc = array_pop(explode("/", (!empty($debug_backtrace[1]['file']) ? $debug_backtrace[1]['file'] : "")));
	}
	if(is_numeric($own)){
		$func_get_args[2] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE id=". (int)$own), 0);
	}
	if(!empty($func_get_args[0]) && function_exists("event")){ $return = event($func_get_args); }

	if($conf['settings']['users_log']){
		$event = $conf['event'][$name];
		if((!empty($event['log']) && ($event['log'] > 1)) || !empty($event['send'])){
			if(!is_numeric($func_get_args[2])){
				unset($func_get_args[2]['pass']);
			}
			foreach($func_get_args as $k=>$v){
				$zam["{". $k. "}"] = (string)$v;
				foreach(is_array($v) ? $v : array() as $n=>$a){
					if(gettype($a) == 'array'){
						ob_start(); print_r($a);
						$a = ob_get_contents();
						ob_end_clean(); mpre($a);
					} $zam["{". $k. ":". $n. "}"] = (string)$a;
				}// mpre($zam);
			} mpre($zam);
		}

		if($event['log']){
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}users_event_log SET time=". time(). ", event_id=". (int)$event['id']. ", uid=". (int)(!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0). ", description=\"". mpquot($description). "\", own=". /*mpquot(is_array($own) ? var_export($own, true) : $own)*/ (int)$func_get_args[2]['id']. ", `return`=\"". mpquot($return). "\", zam=\"". mpquot(!empty($zam) ? var_export($zam, true) : ""). "\"");
			$event_log = mysql_insert_id();
		} mpqw($sql = "INSERT INTO {$conf['db']['prefix']}users_event SET time=". time(). ", uid=". (int)(!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0). ", name=\"". mpquot($name). "\", description=\"". mpquot($desc). "\", count=1 ON DUPLICATE KEY UPDATE time=". time(). ", uid=". (int)(!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0). ", count=count+1, last=". (int)$func_get_args[1]. ", max=IF(". (int)$func_get_args[1]. ">max, ". (int)$func_get_args[1]. ", max), min=IF(". (int)$func_get_args[1]. "<min, ". (int)$func_get_args[1]. ", min), description=\"". mpquot($desc). "\", log_last=". (int)(!empty($event_log) ? $event_log : 0));

		if($event['send']){
			
			$users = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}users_grp AS g INNER JOIN {$conf['db']['prefix']}users_mem AS m ON g.id=m.gid INNER JOIN {$conf['db']['prefix']}users AS u ON m.uid=u.id WHERE 1 AND ". ($event['send'] > 0 ? " g.id=". (int)$event['send'] : " u.id=". (int)$func_get_args[2]['id']. " GROUP BY u.id")));
			foreach($users as $k=>$v){
				mpqw("UPDATE {$conf['db']['prefix']}users_event SET cmail=cmail+1 WHERE id=". (int)$event['id']);
				mpmail($v['email'], strtr($event['subject'], $zam), strtr($event['text'], $zam), "events@zhiraf.info");
			}
		}
	} if(isset($return)) return $return;
}

function mpidn($value, $enc = 0){
	require_once(mpopendir('include/idna_convert.class.inc'));
	$IDN = new idna_convert();
	if($enc){
		return $IDN->encode($value);
	}else{
		return $IDN->decode($value);
	}
}

function mpsettings($name, $value = null){
	global $conf, $arg;
	if($value === null){
		return mpql(mpqw($sql = "SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"". mpquot($name). "\""), 0, "value");
	}elseif($conf['settings'][$name] != $value){
		if(mpql(mpqw($sql = "SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"". mpquot($name). "\""), 0)){
			mpqw($sql = "UPDATE {$conf['db']['prefix']}settings SET value=\"". mpquot($value). "\" WHERE name=\"". mpquot($name). "\"");
		}else{
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}settings SET modpath=\"". mpquot($arg['modpath']). "\", aid=5, name=\"". mpquot($name). "\", value=\"". mpquot($value). "\"");
		} return $value;
	} return $conf['settings'][$name];
}

function mpgt($REQUEST_URI, $get = array()){
	$part = explode('/null/', array_shift(explode('?', $REQUEST_URI)), 2);// mpre($part); exit;
	if(!empty($part[1])){
		$param = explode(':', $part[1], 2);// mpre($param);
		$val = array_pop($param);// mpre($val); exit;
		$get += array(urldecode(array_shift($param))=>urldecode($val));
		$get['null'] = '';
	}
	$part = explode('/', $part[0], 3);
	$mod = explode(':', $part[1]);
	if(!empty($mod[0])){
		$get['m'] = array(urldecode($mod[0])=>urldecode($mod[1]));
		if($mod[0] == 'include' || urldecode($mod[0]) == 'img') $get['null'] = '';
	}
	if($part[2] != ''){
		foreach(explode('/', $part[2]) as $k=>$v){
			if($param = explode(':', $v, 2)){
				if(!empty($param[0]) && !is_numeric($param[0])){
					$get += array(urldecode($param[0])=>urldecode($param[1]));
				}elseif(is_numeric($param[0])){
					$get += array('id'=>$param[0]);
				}
			}
		}
	} if($get['стр']) $get['p'] = $get['стр'];
//	$_SERVER['HTTP_REFERER'] = mpidn(urldecode($_SERVER['HTTP_REFERER']));
//	$_SERVER['HTTP_HOST'] = mpidn(urldecode($_SERVER['HTTP_HOST']));
	return $get;
}

function mpwr($tn, $get = array()){
	global $conf;
	$where = ' WHERE 1=1';
	$f = mpqn(mpqw("DESC {$tn}"), 'Field');
	foreach((array)$get ?: $_GET as $k=>$v){
		$n = array_pop(explode('.', $k));
		if((substr($k, 0, 1) == '!') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND `". mpquot(substr($k, 1)). "`<>\"". mpquot($v). "\"";
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '+') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND `". mpquot(substr($k, 1)). "`>". (int)$v;
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '-') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND `". mpquot(substr($k, 1)). "`<". (int)$v;
		}elseif(($v !== "") && $f[$n]){
			$where .= " AND `". mpquot($k). "`=\"". mpquot($v). "\"";
		}
	} return $where;
}

/*function mpwr($tn, $get = array()){
	global $conf;
	$where = ' WHERE 1=1';
	$f = mpqn(mpqw("DESC {$tn}"), 'Field');
	foreach((array)$get ?: $_GET as $k=>$v){
		$n = array_pop(explode('.', $k));
		if((substr($k, 0, 2) == '!') && ($f[substr($k, 2)] || $f[$n])){
			$where .= " AND ". mpquot(substr($k, 2)). "<>\"". mpquot($v). "\"";
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '+') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND ". mpquot(substr($k, 1)). ">". (int)$v;
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '-') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND ". mpquot(substr($k, 1)). "<". (int)$v;
		}elseif(is_numeric($v) && $f[$n]){
			$where .= " AND ". mpquot($k). "=". (int)$v;
		}
	} return $where;
}*/

function mpmail($to = '', $subj='Проверка', $text = 'Проверка', $from = ''){
	global $conf;
	if($conf['settings']['smtp']){
		return mpsmtp($to, $subj, $text);
	} mpevent("Отправка сообщения", $to, $conf['user']['uid'], debug_backtrace());
	if(empty($to)) return;
	if(empty($from)) $from = "{$_SERVER['HTTP_HOST']}@mpak.su";

	if($to){
		$header = "Content-type: text/html; charset=UTF-8;"; //From: \"". mpidn($_SERVER['HTTP_HOST']). "\" <{$from}>; 
//		'Subject' => '=?UTF-8?B?'.base64_encode($mess['subject']).'?=',
		mail($to, $subj, $text, $header, "-f$from");
		mpevent($conf['settings']['users_event_mail'], $to, $conf['user']['uid'], $subj, $text);
		return true;
	}else{
		return false;
	}
}

function spisok($sql, $str_len = null, $left_pos = 0){
	$result = mpqw($sql);
	if (strlen(mysql_error()))
		echo "$sql<br><font color=red>".mysql_error()."</font>";
	while($line = mysql_fetch_array($result, MYSQL_NUM)){
		list($id, $name) = $line;
		if ($str_len) $name = substr($name, $left_pos , $str_len).(strlen($name) > $str_len ? '...' : '');
		$spisok[$id] = $name;
	} return (array)$spisok;
}

function mpfn($tn, $fn, $id = 0, $prefix = null, $exts = array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')){
	global $conf;
	if(!$prefix){
		$file = $_FILES[$fn];
	}else{
		$file = array(
			'name'=>$_FILES[$prefix]['name'][$fn],
			'type'=>$_FILES[$prefix]['type'][$fn],
			'tmp_name'=>$_FILES[$prefix]['tmp_name'][$fn],
			'error'=>$_FILES[$prefix]['error'][$fn],
			'size'=>$_FILES[$prefix]['size'][$fn],
		);
	}// mpre($_FILES); mpre($file);
	if($file['error'] === 0){
		if ($exts[ $file['type'] ] || isset($exts['*'])){
			if(!($ext = $exts[ $file['type'] ])){
				$ext = '.'. array_pop(explode('.', $file['name']));
			}
			$f = "{$tn}_{$fn}_{$id}". $ext;
/*			if($glob = glob(ini_get('upload_tmp_dir'). "/{$_SERVER['SERVER_NAME']}/*_{$tn}_{$fn}_{$id}*")){
				foreach ($glob as $fn) {
					unlink($fn);
				}
			}*/
			if(($ufn = mpopendir('include/images')) && move_uploaded_file($file['tmp_name'], "$ufn/$f")){
				$return = "images/$f";
			}else{
				echo "images/$f";
			}
		}else{
			echo " <span style='color:red;'>{$file['type']}</span>";
		} mpevent("Загрузка файла", $_SERVER['REQUEST_URI'], $conf['user']['uid'], $file);
		return $return ?: false;
	}elseif(empty($file)){
		return "error not null";
	} return null;
}

function mpdbf($tn, $post = null, $and = false){
	global $conf;
	if(!isset($post)) $post = $_POST;
	foreach(mpql(mpqw("SHOW COLUMNS FROM `$tn`")) as $k=>$v){
		$fields[$v['Field']] = $v['Type'];
	}// mpre($post);
	foreach($post AS $k=>$v){
		if(!empty($conf['settings']['analizsql_autofields']) && $conf['settings']['analizsql_autofields'] && !array_key_exists($k, $fields) && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) !== false){
			mpqw($sql = "ALTER TABLE `$tn` ADD `$k` ". (is_numeric($v) ? "INT" : "varchar(255)"). " NOT NULL"); echo "\n<br>". $sql;
			$f[] = "`$k`=\"". htmlspecialchars(mpquot($v)). "\"";
		}elseif(array_key_exists($k, $fields)){
			if(gettype($v) == 'array'){
				$f[] = "`$k` IN (". htmlspecialchars(mpquot(implode(",", $v))). ")";
			}else{
				$f[] = "`$k`=\"". htmlspecialchars(mpquot($v)). "\"";
			}
		}// mpre($f);
	} return implode(($and ? " AND " : ', '), (array)$f);
}

function mpager($count, $cur=null, $url=null){
	$p = (strpos($_SERVER['HTTP_HOST'], "xn--") === 0) ? "стр" : "p";
	if ($cur === null) $cur = $_GET[$p];
	if ($url === null) $url = strtr(urldecode($_SERVER['REQUEST_URI']), array("/{$p}:{$_GET[$p]}"=>'', "&{$p}={$_GET[$p]}"=>''));
	if(2 > $count = ceil($count)) return;
	$return .=  "<div class=\"pager\">". ($cur <= 0 ? "<span>&#8592; назад</span>" : "<a rel=\"prev\" href=\"$url".($cur > 1 ? "/{$p}:".($cur-1) : '')."\">&#8592; назад</a>");
	for($i = max(0, min($cur-10, $count-20)); $i < min($count, max($cur+10, 20)); $i++)
		$return .=  '&nbsp;'. ($i == $cur ? "<span>".($i+1)."</span>" : "<a href=\"$url".($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=$i" : "/{$p}:$i") : '')."\">".($i+1)."</a>");
	$return .=  '&nbsp;';
	$return .=  $cur+1 >= $count ? "<span>вперед &#8594;</span>" : "<a rel=\"next\" href=\"$url".($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($cur+1) : "/{$p}:".($cur+1)) : '')."\">вперед &#8594;</a>";
	$return .= "</div>";
	return $return;
}

function mphash($user, $pass){
	return md5("$user:".md5($pass));
}

function mpget($name, $value = null){
	$param = "$name".(strlen($value) ? "=$value" : '');
	if (isset($_GET[$name])){
		return str_replace("$name={$_GET[$name]}", $param, $_SERVER['REQUEST_URI']);
	}else{
		return $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?') ? '&' : '?').$param;
	}
}

function mpct($file_name, $arg = array(), $vr = 1){
	global $conf;
	foreach(explode(':', $conf['fs']['path'], 2) as $k=>$v)
		if (file_exists($file = "$v/$file_name")) break;
	if (!file_exists($file = "$v/$file_name")) return false;
	$func_name = create_function('$arg', "global \$conf;\n". strtr(file_get_contents($file), $vr ? array('<? die;'=>'', '?>'=>'') : array()));
	ob_start(); $func_name($arg);
	$content = ob_get_contents(); ob_end_clean();
	return $content;
}

function mpeval($file_name, $arg = array(), $vr = 1){
	global $conf;
	foreach(explode(':', $conf['fs']['path'], 2) as $k=>$v)
		if (file_exists($file = "$v/$file_name")) break;
	if (!file_exists($file = "$v/$file_name")) return "<div style=\"margin-top:100px; text-align:center;\"><span style=color:red;>Ошибка доступа к файлу</span> $v/$file_name</div>";

	ob_start();
	eval('?>'. strtr(file_get_contents($file), array('<? die;'=>'<?', '<?php die;'=>'<?php')));
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function mpreaddir($file_name, $merge=0){
	global $conf;
	$itog = array();
	$prefix = $merge ? explode(':', $conf['fs']['path'], 2) : array('./');
	if ($merge < 0) krsort($prefix);
	foreach($prefix as $k=>$v){
		if (!is_dir("$v/$file_name")) continue;
		$dir = opendir("$v/$file_name");
		$files = array();
		while($file = readdir($dir)){
			if (substr($file, 0, 1) == '.') continue;
			$files[] = $file;
		}
		$itog = array_merge_recursive($itog, $files);
	}
	return $itog;
}

function mpopendir($file_name, $merge=1){
	global $conf;
	$prefix = $merge ? explode(':', $conf['fs']['path'], 2) : array('./');
	if ($merge < 0) krsort($prefix);
	foreach($prefix as $k=>$v){
		$file = strtr("$v/$file_name", array('/modules/..'=>''));
		if (file_exists($file)){
			return $file; break;
		}
	}
}

function mpql($dbres, $ln = null, $fd = null){
	$result = array();
	while($line = @mysql_fetch_array($dbres, 1))
		$result[] = $line;
	if ($ln !== null && $result){
		$result = $result[$ln];
		if ($fd)
			$result = $result[$fd];
	}
	return $result;
}

function mpqn($dbres, $x = "id", $y = null, $n = null, $z = null){
	$result = array();
	while($line = @mysql_fetch_array($dbres, 1)){
		if($z){
			$result[ $line[$x] ][ $line[$y] ][ $line[$n] ][ $line[$z] ] = $line;
		}elseif($n){
			$result[ $line[$x] ][ $line[$y] ][ $line[$n] ] = $line;
		}elseif($y){
			$result[ $line[$x] ][ $line[$y] ] = $line;
		}else{
			$result[ $line[$x] ] = $line;
		}
	} return $result;
}

function mpqw($sql, $info = null, $conn = null){
	global $conf;
	$mt = microtime(true);
	$result = @mysql_query($sql, ($conn ?: $conf['db']['conn']));
	if ($error = mysql_error()){// mpre($conf['user']);
		if(!empty($conf['modules']['sqlanaliz']['access']) && (array_search($conf['user']['uname'], explode(",", $conf['settings']['admin_usr'])) !== false)){
			echo "<p>$sql<br><font color=red>".mysql_error()."</font>";
		}
		$check = array(
			"Unknown column 'r.fn' in 'where clause'" => array(
				"SELECT * FROM mp_blocks_reg AS r WHERE" => array(
					"ALTER TABLE `mp_blocks_reg` ADD `fn` varchar(255) NOT NULL",
					"ALTER TABLE `mp_blocks_reg` ADD INDEX (fn)",
				),
			),
			"Unknown column 'log_last' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}users_event SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}users_event` ADD `log_last` int(11) NOT NULL AFTER `log`",
				),
			),
			"Unknown column 'last' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}users_event SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}users_event` ADD `last` int(11) NOT NULL AFTER `log`",
					"ALTER TABLE `{$conf['db']['prefix']}users_event` ADD `max` int(11) NOT NULL AFTER `last`",
					"ALTER TABLE `{$conf['db']['prefix']}users_event` ADD `min` int(11) NOT NULL AFTER `max`",
				),
			),
			"Unknown column 'faq.cat_id' in 'on clause'" => array(
				"SELECT cat.*, COUNT(*) AS cnt FROM {$conf['db']['prefix']}faq_cat" => array(
					"ALTER TABLE `{$conf['db']['prefix']}faq` CHANGE `cid` `cat_id` int(11) NOT NULL",
					"ALTER TABLE `{$conf['db']['prefix']}faq` ADD INDEX (cat_id)",
					"ALTER TABLE `{$conf['db']['prefix']}faq` ADD INDEX (hide)",
				),
			),
			"Table 'shop_mpak_su.mp_faq' doesn't exist" => array(
				"SELECT cat.*, COUNT(*) AS cnt FROM {$conf['db']['prefix']}faq_cat" => array(
					"ALTER TABLE {$conf['db']['prefix']}faq RENAME mp_faq_index",
				),
			),
			"Unknown column 'reg_id' in 'where clause'" => array(
				"SELECT * FROM mp_blocks_reg" => array(
					"ALTER TABLE `{$conf['db']['prefix']}blocks_reg` ADD `reg_id` int(11) NOT NULL AFTER `id`",
					"ALTER TABLE `{$conf['db']['prefix']}blocks_reg` ADD INDEX (reg_id)",
				),
			),
			"Unknown column 'cmail' in 'field list'" => array(
				"UPDATE {$conf['db']['prefix']}users_event SET cmail=cmail+1" => array(
					"ALTER TABLE `{$conf['db']['prefix']}users_event` ADD `cmail` int(11) NOT NULL AFTER `send`",
				),
			),
			"Unknown column 'zam' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}users_event_log SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}users_event_log` ADD `zam` text NOT NULL",
				),
			),
			"Unknown column 'text' in 'field list'" => array(
				"SELECT * FROM {$conf['db']['prefix']}users_event" => array(
					"ALTER TABLE `{$conf['db']['prefix']}users_event` ADD `text` text NOT NULL ",
				),
			),
			"Unknown column 'own' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}users_event_log SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}users_event_log` ADD `own` varchar(255) NOT NULL",
				),
			),
			"Unknown column 'pages' in 'field list'" => array(
				"UPDATE {$conf['db']['prefix']}search SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}search` ADD `pages` int(11) NOT NULL AFTER `count`",
				),
			),
			"Unknown column 'flush' in 'field list'"=>array(
				"UPDATE mp_users SET pass="=>array(
					"ALTER TABLE `{$conf['db']['prefix']}users` ADD `flush` int(11) NOT NULL AFTER `refer`",
					"ALTER TABLE `{$conf['db']['prefix']}users` ADD INDEX (flush)",
				),
			),
			"Unknown column 'r.mid' in 'where clause'" => array(
				"SELECT b.* FROM {$conf['db']['prefix']}blocks_reg AS r INNER JOIN {$conf['db']['prefix']}blocks AS b ON r.id=b.rid WHERE"=> array(
					"ALTER TABLE `{$conf['db']['prefix']}blocks_reg` ADD `mid` int(11) NOT NULL AFTER `id`",
					"ALTER TABLE `{$conf['db']['prefix']}blocks_reg` ADD INDEX (mid)",
				),
			),
			"Unknown column 'time' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}foto_img SET"=>array(
					"ALTER TABLE `{$conf['db']['prefix']}foto_img` ADD `time` int(11) NOT NULL AFTER `id`",
					"ALTER TABLE `{$conf['db']['prefix']}foto_img` ADD INDEX (time)",
				),
			),
			"Unknown column 'ref' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}users SET"=>array(
					"ALTER TABLE `{$conf['db']['prefix']}users` ADD `ref` varchar(255) NOT NULL AFTER `img`",
					"ALTER TABLE `{$conf['db']['prefix']}users` ADD INDEX (ref)",
				),
			),
			"Unknown column 'url_id' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}comments_txt SET url_id=" => array(
					"ALTER TABLE `{$conf['db']['prefix']}comments_txt` CHANGE `uid` `url_id` int(11) NOT NULL",
					"ALTER TABLE `{$conf['db']['prefix']}comments_txt` ADD INDEX (url_id)",
				),
			),
			"Unknown column 'txt.url_id' in 'where clause'" => array(
				"SELECT txt.* FROM {$conf['db']['prefix']}comments_txt AS" => array(
					"ALTER TABLE `{$conf['db']['prefix']}comments_txt` CHANGE `uid` `url_id` int(11) NOT NULL",
					"ALTER TABLE `{$conf['db']['prefix']}comments_txt` ADD INDEX (url_id)",
				),
			),
			"Unknown column 'url.name' in 'where clause'" => array(
				"SELECT txt.* FROM {$conf['db']['prefix']}comments_txt" => array(
					"ALTER TABLE `{$conf['db']['prefix']}comments_url` CHANGE `url` `name` text NOT NULL",
//					"ALTER TABLE `{$conf['db']['prefix']}comments_txt` ADD INDEX (url_id)",
				),
			),
			"Unknown column 'count' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}search" => array(
					"ALTER TABLE `{$conf['db']['prefix']}search` ADD `count` int(11) NOT NULL AFTER `search`",
					"ALTER TABLE `{$conf['db']['prefix']}search` ADD INDEX (uid)",
				),
				"UPDATE {$conf['db']['prefix']}search SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}search` ADD `count` int(11) NOT NULL AFTER `search`",
					"ALTER TABLE `{$conf['db']['prefix']}search` ADD INDEX (uid)",
				),
			),
			"Unknown column 'uid' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}users_event SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}users_event` ADD `uid` int(11) NOT NULL AFTER `time`",
				),
				"INSERT INTO {$conf['db']['prefix']}comments_txt SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}comments_txt` ADD `uid` int(11) NOT NULL AFTER `url_id`",
					"ALTER TABLE `{$conf['db']['prefix']}comments_txt` ADD INDEX (uid)",
				),
			),
			"Unknown column 'modpath' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}comments_url SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}comments_url` ADD `modpath` varchar(255) NOT NULL AFTER `name`",
					"ALTER TABLE `{$conf['db']['prefix']}comments_url` ADD INDEX (modpath)",
				),
			),
			"Unknown column 'fn' in 'field list'" => array(
				"INSERT INTO {$conf['db']['prefix']}comments_url SET" => array(
					"ALTER TABLE `{$conf['db']['prefix']}comments_url` ADD `fn` varchar(255) NOT NULL AFTER `modpath`",
					"ALTER TABLE `{$conf['db']['prefix']}comments_url` ADD INDEX (fn)",
				),
			),
			"Table '{$conf['db']['name']}.{$conf['db']['prefix']}pages_index' doesn't exist" => array(
				"SELECT * FROM {$conf['db']['prefix']}pages_index" => array(
					"ALTER TABLE {$conf['db']['prefix']}pages_post RENAME mp_pages_index",
				),
			),
			"Table '{$conf['db']['name']}.{$conf['db']['prefix']}users_event' doesn't exist" => array(
				"INSERT INTO mp_users_event SET" => array(
					"CREATE TABLE `{$conf['db']['prefix']}users_event` ( `id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) NOT NULL, `name` varchar(255) NOT NULL, `count` int(11) NOT NULL, `log` smallint(6) NOT NULL, `description` varchar(255) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name_2` (`name`), KEY `name` (`name`) ) ENGINE=InnoDB DEFAULT CHARSET=cp1251",
					"CREATE TABLE `{$conf['db']['prefix']}users_event_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) NOT NULL, `event_id` int(11) NOT NULL, `uid` int(11) NOT NULL, `description` text NOT NULL, PRIMARY KEY (`id`), KEY `event_id` (`event_id`), KEY `uid` (`uid`) ) ENGINE=InnoDB DEFAULT CHARSET=cp1251",
				),
			),
/*			"Unknown column 'time' in 'order clause'" => array(
				"SELECT * FROM {$conf['db']['prefix']}search" => array(
					"ALTER TABLE `{$conf['db']['prefix']}search` ADD `time` int(11) NOT NULL AFTER `uid`",
					"ALTER TABLE `{$conf['db']['prefix']}search` ADD INDEX (time)",
					"ALTER TABLE `mp_search` DROP `date`ALTER TABLE `mp_search` DROP `date`",
				),
			),*/
/*			"Table '{$conf['db']['name']}.mp_search_index' doesn't exist" => array(
				"SELECT * FROM mp_search_index" => array(
					"ALTER TABLE mp_search RENAME mp_search_index",
				),
			),*/
		);
		if($init = $check[ $error ]){
			foreach($init as $r=>$q){
				if(strpos($sql, $r) !== false){
					mpre($q);
					foreach($q as $n=>$s){
						mpqw($s); echo $s;
					}
				}
			};
		}
	}

	if (!empty($conf['settings']['analizsql_log'])){
		$conf['db']['sql'][] = $q = array(
			'info' => $info ? $info : $conf['db']['info'],
			'time' => microtime(true)-$mt,
			'sql' => $sql,
		);
		if(!empty($conf['settings']['sqlanaliz_time_log']) && $q['time'] > $conf['settings']['sqlanaliz_time_log']){
			mpevent("Долгий запрос к базе данных", $sql. " {$q['time']}c.", $conf['user']['uid'], $q);
		}
	} return($result);
}

function mpfile($filename, $description = null){
//	$file_name = strtr($file_name, array('../'=>'', '/./'=>'/', '//'=>'/'));
	$file_name = mpopendir("include/$filename");
	if (file_exists($file_name)){
		$ext = explode('.', $file_name); $ext = $ext[count($ext) - 1];
//		header("Content-Type:	 text/html; charset=windows-1251");
		header("Content-Type: application/force-download; name=\"".($description ? "$description.$ext" : basename($file_name))."\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize("$file_name"));
		header("Content-Disposition: attachment; filename=\"".($description ? "$description.$ext" : basename($file_name))."\"");
		header("Expires: ".date('r'));
		header("Cache-Control: max-age=3600");
//		header("Cache-Control: max-age=3600, must-revalidate");
//		header("Pragma: no-cache");
//		readfile($file_name); exit;
		$handle = fopen($file_name, 'rb');
		while (!feof($handle)){
			echo fread($handle, 4096);
			ob_flush();
			flush();
		} fclose($handle); exit;
	}else{
		return '';
	}
}

function mpgc($value, $param = null){
	if ($param) unset($value[$param]);
	ob_start();
	var_dump($value);
	$str = ob_get_contents();
	ob_end_clean();
	return $str;
}

function mpwysiwyg($name, $content = null, $tpl = ""){
	global $conf;
//	if(mpopendir('include/spaw2')){
	if(!empty($conf['modules']['tinymce']['access'])){
		$conf['settings']['tinymce_name'] = $name;
		$conf['settings']['tinymce_text'] = $content;
		if($tpl && $fn = mpopendir("modules/tinymce/". basename($tpl))){
			include $fn;
		}else{
			include mpopendir("modules/tinymce/wysiwyg.tpl");
		}
	}elseif(true){
		include_once("include/spaw2/spaw.inc.php");
		ob_start();
		$spaw1 = new SpawEditor($name, $content);
		$spaw1->show();
		$spaw2 = ob_get_contents();
		ob_end_clean();
		return $spaw2;
	}elseif(!empty($conf['modules']['rte']['access'])){
		$conf['settings']['rte_name'] = $name;
		$conf['settings']['rte_text'] = $content;
		include mpopendir("modules/rte/wysiwyg.tpl");
	}else{
		return "<textarea name='$name' style='width:100%; height:200px;'>$content</textarea>";
	}
}

function mpmenu($m = array()){
	global $conf, $arg;
	if(array_key_exists("null", $_GET)) return false;

	$tab = (int)$_GET['r'];
	echo <<<EOF
		<script>
			$(document).ready(function(){
				$('.tabs li.{$tab}').add('.tabs li.{$_GET['r']}').addClass('act');
			});
		</script>
EOF;
	if(empty($conf['settings']['admin_help_hide'])){
		echo '<div style="float:right; margin:5px;"><a target=blank href="http://mpak.su/help/modpath:'. $arg['modpath']. "/fn:". $arg['fn']. '/r:'. $_GET['r']. '">Помощь</a></div>';
	}
	echo '<ul class="nl tabs">';
	foreach($m as $k=>$v){
		if ($v[0] == '.') continue;
		echo "<li class=\"$k\"><a href=\"/?m[". array_search('admin', $_GET['m']). "]=admin". ($k ? "&r=$k" : ''). "\">$v</a></li>";
	}
	echo '</ul>';
	if(!empty($m) && empty($_GET['r'])){
		if(!is_numeric($r = array_shift(array_keys($m))) && (strpos($_SERVER['REQUEST_URI'], "?") !== false)){
			header("Location: {$_SERVER['REQUEST_URI']}&r=". array_shift(array_keys($m)));
		}
	}
}

function mpre($array = false, $access = 4, $line = 0){
	global $conf, $arg, $argv;
	if(empty($argv) && ($arg['access'] < $access)) return;
	foreach(debug_backtrace() as $k=>$v){
		if(!is_numeric($line) || $k === $line){
			if($array){
				echo "<fieldset><legend>[$k] {$v['file']}:{$v['line']} function <b>{$v['function']}</b> ()</legend>";
			}else{
				echo "[$k] {$v['file']}:{$v['line']} function <b>{$v['function']}</b> ()<br>";
			}
			foreach($v['args'] as $n=>$z){
				echo "<pre>"; print_r($z); echo "</pre>";
			}
			if($array)
				echo "</fieldset>";
		}
	}
}

function mpqwt($result){
	echo "<table style='background-color:#888;' cellspacing=0 cellpadding=3 border=1><tr>";
	foreach($result[0] as $k=>$v){
		echo "<td align=center style='background-color: #888; color:white;'><b>$k</b></td>";
	} echo "</tr>";
	foreach($result as $k=>$l){
		echo "<tr valign='top' style='background-color: #eee;'>";
		foreach($l as $null=>$v){
			echo "<td>".(strlen($v) ? $v : '&nbsp;')."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}

function mptree($array, $sid=0, $shablon =array()){
        foreach($array as $k=>$v){
                $id[ $v[ $shablon['id'] ] ] = $v;
                $pid[ $v[ $shablon['pid'] ] ][] = $v[ $shablon['id'] ];
        }
        return mptreer($id, $pid, $sid, $shablon);
}

function mptreer($id, $pid, $sid, $shablon, $prefix=0, $use=array()){
        foreach((array)$pid[$sid] as $null=>$n){
                foreach($id[ $n ] as $k=>$v){
                        $zamena["{".$k."}"] = $v;
                }
                foreach($shablon as $k=>$v){
                        if (is_array($v)){
                                $zamena["{".$k."}"] = (isset($v[$n]) ? $v[$n] : $v['*']);
                        }
                }
                $zamena['{tmp:prefix}'] = $shablon['prefix']['pre'];
                foreach($use as $k=>$v){
                        $zamena['{tmp:prefix}'] .= $shablon['prefix'][ (count($pid[$k])-1 <= $v ? '-' : '+') ];
                }
                $zamena['{tmp:prefix}'] .= $shablon['prefix']['post'];
                $zamena['{line}'] = $shablon['line'][ (count($pid[ $n ]) ? '+' : '').(array_search($n, $pid[$sid]) == count($pid[$sid])-1 ? '-' : '+') ];
                if ((strlen($n) != strlen((int)$n)) && !$use[ $sid ]){
                        if (!isset($use[$null])){
                                $zamena['{folder}'] = mptreer($id, $pid, $n, $shablon, $prefix+1, $use+array($sid=>$null));
                        }
                        $result .= strtr($shablon['folder'], $zamena);
                }else{
                        $result .= strtr($shablon['file'], $zamena);
                }
        }
        return $result;
}

function mpquot($text){
	$text = stripslashes($text);
	$text = str_replace('\\', '\\\\', $text);
	$text = str_replace('"', '\\"', $text);
	$text = str_replace("'", "\\'", $text);
	return $text;
}

function mpuf($name, $table, $field, $id, $ext){
	if ($_FILES[$name]){
		if ($ext = $ext[ $_FILES[$name]['type'] ]){
			$fname = "images/{$table}_{$field}_{$id}.$ext";
			if(@copy($_FILES[$name]['tmp_name'], array_shift(explode(':', $GLOBALS['conf']['fs']['path'], 2))."/include/$fname")){
				return $fname;
			}else{
				echo "1";
				return false;
			}
		}else{
			echo "2";
			return false;
		}
	}else{
		echo "3";
		return false;
	}
}

function mprs($file_name, $max_width=0, $max_height=0, $crop=0){
	global $conf;
	$func = array(
		'jpg' => 'imagejpeg',
		'jpeg' => 'imagejpeg',
		'png' => 'imagepng',
		'gif' => 'imagegif',
	);
	$ext = array_pop(explode('.', $file_name));
	$cache_name = ini_get('upload_tmp_dir'). "/images";
	$host_name = strpos('www.', $_SERVER['SERVER_NAME']) === 0 ? substr($_SERVER['SERVER_NAME'], 4) : $_SERVER['SERVER_NAME'];
	$fl_name = (int)$max_width. "x". (int)$max_height. "x". (int)$crop. "_" .basename($file_name);
	$prx = basename(dirname($file_name));

//	header("Pragma: public");
//	header("Cache-Control: maxage=". ($expires = 60*60*24));
//	header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');

	if (!array_key_exists('nologo', $_GET) && file_exists("$cache_name/$host_name/$prx/$fl_name") && (filectime("$cache_name/$host_name/$prx/$fl_name") > ($filectime = filectime($file_name)))){ // 
		if(!($mc = mpmc($key = "{$_SERVER['HTTP_HOST']}/{$fl_name}/filectime:{$filectime}/{$conf['event']['Формирование изображения']['count']}", null, false, 0, false))){
			echo $mc = file_get_contents("$cache_name/$host_name/$prx/$fl_name");
			mpmc($key, $mc, false, 86400, false);
		} /*echo $key;*/ return $mc;
	}elseif ($src = @imagecreatefromstring(file_get_contents($file_name))){
		$width = imagesx($src);
		$height = imagesy($src);
		if(empty($max_width) || empty($max_height) || (($width <= $max_width) && ($height <= $max_height))){
			$content = file_get_contents($file_name);
		}else{
			if ($crop){
				$cdst = array($max_width, $max_height);
				$max = max($max_width/$width, $max_height/$height);
				$irs = array(4=>($width-$max_width/$max)/2, ($height-$max_height/$max)/2, $max_width, $max_height, ($max_width/$max), ($max_height/$max),);
			}else{
				$x_ratio = $max_width / $width;
				$y_ratio = $max_height / $height;
				if ( ($width <= $max_width) && ($height <= $max_height) ){
					$tn_width = $width;
					$tn_height = $height;
				}elseif (($x_ratio * $height) < $max_height){
					$tn_height = ceil($x_ratio * $height);
					$tn_width = $max_width;
				}else{
					$tn_width = ceil($y_ratio * $width);
					$tn_height = $max_height;
				}
				$irs = array(6=>$tn_width, $tn_height, $width, $height,);
				$cdst = array($tn_width, $tn_height);
			}
			$dst = imagecreatetruecolor($cdst[0], $cdst[1]);
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
			imagecopyresampled($dst, $src, 0, 0, $irs[4], $irs[5], $irs[6], $irs[7], $irs[8], $irs[9]);
			if (
				!array_key_exists('nologo', $_GET) &&
				!empty($conf['settings']['theme_logo']) &&
				(imagesx($dst) > 250) &&
				(imagesy($dst) > 150) &&
				!isset($_GET['m']['themes']) &&
				($lg = explode(':', $conf['settings']['theme_logo'])) &&
				($f = mpopendir("themes/{$conf['settings']['theme']}/". array_shift($lg))) &&
				$logo = imagecreatefromstring(file_get_contents($f))
			){
				imagealphablending($dst, true);
				$w = array_shift($lg); $h = array_shift($lg);
				imagecopyresampled($dst, $logo, ($w < 0 ? imagesx($dst)-imagesx($logo)+$w : $w), ($h < 0 ? imagesy($dst)-imagesy($logo)+$h : $h), 0, 0, imagesx($logo), imagesy($logo), imagesx($logo), imagesy($logo));
			}
			ob_start();
			if ($rext){
				$func[$rext]($dst, null, -1);
			}else{
				$func[ strtolower(array_pop(explode('.', $file_name))) ]($dst, null, -1);
			}
			$content = ob_get_contents();
			ob_end_clean();
			ImageDestroy($src);
			ImageDestroy($dst);
		}
		if(!file_exists("$cache_name/$host_name/$prx")){
			require_once(mpopendir('include/idna_convert.class.inc'));
			$IDN = new idna_convert();
			mkdir("$cache_name/$host_name/$prx", 0755, 1);
			if($host_name != $IDN->decode($host_name) && !file_exists("$cache_name/". $IDN->decode($host_name))){
				symlink("$cache_name/$host_name", "$cache_name/". $IDN->decode($host_name));
			}
		} file_put_contents("$cache_name/$host_name/$prx/$fl_name", $content);
		mpevent("Формирование изображения", $fl_name, $conf['user']['uid']);
		return $content;
	}else{
		$src = imagecreate (50, 30);
		$bgc = imagecolorallocate ($src, 255, 255, 255);
		$tc = imagecolorallocate ($src, 0, 0, 0);
		imagefilledrectangle ($src, 0, 0, 150, 30, $bgc);
		header("Content-type: image/jpeg");
		mpevent("Ошибка открытия изображения", $file_name, $conf['user']['uid']);
		imagestring ($src, 1, 5, 5, "NoIMAGE", $tc);
		return ImageJpeg($src);
	}
}

?>