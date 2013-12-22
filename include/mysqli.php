<?

function qn($sql){ # Выполнение запроса к базе данных. В случае превышения лимита времени кеширование результата
	$microtime = microtime(true);
	$func_get_args = func_get_args();
	$func_get_args[0] = mpqw($sql);
	$r = call_user_func_array('mpqn', $func_get_args);
	return $r;
}

function mpqn($dbres, $x = "id", $y = null, $n = null, $z = null){
	$result = array();
	while($line = mysqli_fetch_array($dbres, MYSQLI_ASSOC)){
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

function ql($sql, $ln = null, $fd = null){ # Выполнение запроса к базе данных. В случае превышения лимита времени кеширование результата
	$microtime = microtime(true);
	$r = mpql(mpqw($sql), $ln, $fd);
	return $r;
}

function mpql($dbres, $ln = null, $fd = null){
	$result = array();
	while($line = mysqli_fetch_array($dbres, MYSQLI_ASSOC))
		$result[] = $line;
	if ($ln !== null && $result){
		$result = $result[$ln];
		if ($fd)
			$result = $result[$fd];
	}
	return $result;
}

function qw($sql){
	global $conf, $db;
	$db->prepare($sql)->execute();
	if($error = mysqli_error($db)){
		echo $error;
	}else{
		return true;
	}
}

function mpqw($sql, $info = null, $conn = null){
	global $conf, $db;
	$result = $db->query($sql);
	if($error = mysqli_error($db)){
		mpre($error);
	} return($result);
}

function mpre($array = false, $access = 4, $line = 0){
	global $conf, $arg, $argv;
	if($_SERVER['REMOTE_ADDR'] != "91.122.47.82") return;
//	if(empty($argv) && ($arg['access'] < $access)) return;
	foreach(debug_backtrace() as $k=>$v){
		if(!is_numeric($line) || $k === $line){
			if($array){ # Комментарии выводим для javascript шаблонов. Чтобы они игнорировались как код
				echo "/*<fieldset><legend>[$k] {$v['file']}:{$v['line']} function <b>{$v['function']}</b> ()</legend>*/";
			}else{
				echo "/*[$k] {$v['file']}:{$v['line']} function <b>{$v['function']}</b> ()<br>*/";
			}
			foreach($v['args'] as $n=>$z){
				echo "/*<pre>"; print_r($z); echo "</pre>*/";
			}
			if($array) echo "/*</fieldset>*/";
		}
	}
}
