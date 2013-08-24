<? die;

/*$sql = "SELECT SQL_CALC_FOUND_ROWS p.*, p.id AS id, CONCAT(SUBSTR(p.text, 1, 500)) as txt, u.name AS uname, k.name AS kname
FROM {$conf['db']['prefix']}{$arg['modpath']}_post AS p
LEFT JOIN {$conf['db']['prefix']}users AS u
	ON p.uid=u.id
LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_cat AS k
	ON p.cat_id=k.id WHERE 1=1". ($_GET['id'] ? " AND p.id=".(int)$_GET['id'] : "").($_GET['cat_id'] ? " AND p.cat_id=".(int)$_GET['cat_id'] : '');

if (!$_GET['id']) $sql .= " ORDER BY time DESC LIMIT ". ((int)$_GET['p']*5). ",5";
$conf['tpl']['news'] = mpql(mpqw($sql));

if ((int)$_GET['id']){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_post SET count=count+1 WHERE id=".(int)$_GET['id']." LIMIT 1");
	$conf['settings']['title'] = $conf['tpl']['news'][0]['tema'];
	$conf['tpl']['news'][0]['count']++;
}else{
	$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS() AS count"), 0, 'count')/5);
}*/

$tpl['cat'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat");
$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY time DESC");

if($n = $tpl['index'][ $_GET['id'] ] && !empty($n['count'])){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET count=count+1 WHERE id=". (int)$n['id']);
}

?>