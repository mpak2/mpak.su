<? die;

$sql = "SELECT SQL_CALC_FOUND_ROWS p.*, p.id AS id, CONCAT(SUBSTR(p.text, 1, 500)) as txt, u.name AS uname, k.name AS kname
FROM {$conf['db']['prefix']}{$arg['modpath']}_post AS p
LEFT JOIN {$conf['db']['prefix']}users AS u
	ON p.uid=u.id
LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_kat AS k
	ON p.kid=k.id WHERE 1=1". ($_GET['id'] ? " AND p.id=".(int)$_GET['id'] : '').($_GET['kid'] ? " AND p.kid=".(int)$_GET['kid'] : '');

if (!$_GET['id']) $sql .= " ORDER BY time DESC LIMIT ". ((int)$_GET['p']*5). ",5";
$conf['tpl']['news'] = mpql(mpqw($sql));

if ((int)$_GET['id']){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_post SET count=count+1 WHERE id=".(int)$_GET['id']." LIMIT 1");
	$conf['settings']['title'] = $conf['tpl']['news'][0]['tema'];
	$conf['tpl']['news'][0]['count']++;
}else{
	$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS() AS count"), 0, 'count')/5);
}


?>