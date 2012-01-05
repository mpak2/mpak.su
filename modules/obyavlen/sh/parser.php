<?

include("../../../../www.sdaem.mpak.su/config/config.php");
include("../../../include/mpfunc.php");
include("../../../include/func.php");

$conf['db']['conn'] = @mysql_connect($conf['db']['host'], $conf['db']['login'], $conf['db']['pass']);
@mysql_select_db($conf['db']['name'], $conf['db']['conn']);
if (strlen(mysql_error())){
	echo "Ошибка соединения с базой данных<p>";
	echo mysql_error();
	exit;
}

print_r(
    $assoc=array(
	'комната' => '2',
	'1'=>'3',
	'2'=>'4',
	'3'=>'5',
	'4'=>'6',
	'5'=>'7',
    )
);

print_r($data = spisok("SELECT id, name FROM mp_obyavlen_kat"));
print_r($data = spisok("SELECT id, title FROM mp_obyavlen_pole"));

$pole = array(
    '4'=>'1', # Размер кухни
    '5'=>'2', # Площадь жилья
    '6'=>'3', # Цена
    '7'=>'2', # 
    '9'=>'6',
    '10'=>'7'
);

mpqw("DELETE FROM mp_obyavlen");
mpqw("DELETE FROM mp_obyavlen_dop");
for($i=1; $i<=900; $i++){
	sleep(1); unset($out);
	$data = file_get_contents("http://www.cian.ru/cat.php?p=$i&type=-2"));
//	if (file_exists($file_name = "./data/cian.ru_$i.html")){
//		$data = file_get_contents($file_name);
//	}else{
//		file_put_contents($file_name, $data = file_get_contents("http://www.cian.ru/cat.php?p=$i&type=-2"));
//	}

	preg_match_all ('|<td>(\d{1,5})</td>'.str_repeat('\r\n<td.*>(.*)</td>', 7).'\r\n<td.*>(.*)</td>'.'\r\n<td.*><a.*>(.*)</a>.*</td>'.'\r\n<td.*><noindex><font color="#0000A0">(.*)</font> <a.*>(.*)</a>(.*)<\/?\w+[^>]*>\r\n|U', $data, $out, PREG_PATTERN_ORDER);
//	preg_match_all ('|<td>(\d{1,5})</td>'.str_repeat('\r\n<td.*>(.*)</td>', 7).'|U', $data, $out, PREG_PATTERN_ORDER);
//	echo "<pre>"; print_r($out); echo "</pre>"; continue;
	echo "$i(".count($out[0])."), "; 
	foreach($out[0] as $k=>$v){
		if ($assoc[ $out['3'][$k] ]){
			$kid = $assoc[ $out['3'][$k] ];
		}else{
			$kid = 8;
			echo "kid={$out['3'][$k]}";
		}
		mpqw("INSERT INTO mp_obyavlen (uid, gid, kid, description) VALUES (1, 1, $kid, '{$out['13'][$k]}')");
		$mysql_insert_id = mysql_insert_id();
		foreach($pole as $n=>$m){
			if ($m == 3){
				$out[$n][$k] = strtr($out[$n][$k], array(','=>''));
			}elseif($m == 6 && strpos($out[$n][$k], '<a')){
				$out[$n][$k] = substr($out[$n][$k], 0, strpos($out[$n][$k], '<a'));
			}
			mpqw("INSERT INTO mp_obyavlen_dop (oid, pid, val) VALUES ($mysql_insert_id, $m, '".strtr($out[$n][$k], array('<br>'=>", ", "<hr>"=>'/'))."')");
		}
		mpqw("INSERT INTO mp_obyavlen_dop (oid, pid, val) VALUES ($mysql_insert_id, 4, '".strtr($out['8'][$k], array('<br>'=>", ", "<hr>"=>'/'))."')");
		
	}
}

?>
