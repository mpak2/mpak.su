<? die;

//if($_GET['id']) $conf['settings']['title'] = $conf['tpl'][$arg['fn']]['0']['name'];
if($arg['access'] <= 1){
	header("Location: /users:reg");
	die;
}

$conf['tpl']['producer'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_producer");
$conf['tpl']['obj'] = spisok("SELECT o2.id, CONCAT(o1.name, ' / ', o2.name) FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o1 INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_obj AS o2 ON o1.id=o2.pid ORDER BY o1.name");

if($_POST['name']){
	$mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}", array_merge($_POST, array('uid'=>$conf['user']['uid'])));
	mpqw("INSERT INTO $tn SET ". $mpdbf);
	if(($id = mysql_insert_id()) && $fn = mpfn($tn, 'img', $id)){
		mpqw("UPDATE $tn SET img=\"". mpquot($fn). "\" WHERE id=".(int)$id);
	}
}elseif($_GET['del']){
	mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} SET disable=1 WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_GET['del']);
}

$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE disable=0 AND uid=". (int)$conf['user']['uid']. ($_GET['id'] ? " AND id=".(int)$_GET['id'] : '')." ORDER BY id DESC LIMIT ".($_GET['p']*5).",5"));
$conf['tpl']['cnt'] = mpql(mpqw("SELECT FOUND_ROWS()/5 AS cnt"), 0, 'cnt');
//$conf['tpl']['comments'] = spisok("SELECT u.num, COUNT(*) AS cnt FROM {$conf['db']['prefix']}comments_url AS u, {$conf['db']['prefix']}comments_txt AS t WHERE u.id=t.uid AND u.url LIKE '%/{$arg['modpath']}/%' GROUP BY u.id");

?>