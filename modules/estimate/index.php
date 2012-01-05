<? die;

$refer = "/". implode('/', array_slice(explode('/', urldecode($_SERVER['HTTP_REFERER'])), 3));
if($_POST['estimate']){
	$est = min(max($_POST['estimate'], 0), 5);
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET time=". time(). ", uid=". $conf['user']['uid']. ",  name=\"". mpquot($refer). "\", count=1, estimate=". (int)$est. " ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id),time=". time(). ", uid=". $conf['user']['uid']. ", count=count+1, estimate=estimate+". (int)$est);
	if($index_id = mysql_insert_id()){
		$estimate = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$index_id), 0);
	} echo $estimate['count']. "/". number_format($estimate['estimate']/$estimate['count'], 2); exit;
}elseif(array_key_exists('null', $_GET)){
	$conf['tpl']['estimate'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE name=\"". mpquot($refer). "\""), 0);
	$conf['tpl']['estimate']['sum'] = number_format($conf['tpl']['estimate']['count'] ? $conf['tpl']['estimate']['estimate']/$conf['tpl']['estimate']['count'] : 0, 2);
}

?>