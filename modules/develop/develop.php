<? die;

if($_POST['submit'] && !empty($_POST['plan'])){
	$max = mpql(mpqw("SELECT MAX(sort)+1 AS max FROM {$conf['db']['prefix']}{$arg['modpath']}_plan"), 0, 'max');
	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_plan SET uid=".(int)$conf['user']['uid'].", time=".time().", plan=\"".($arg['access'] >= 4 ? mpquot($_POST['plan']) : htmlspecialchars(mpquot($_POST['plan'])))."\", sort=".(int)$max);
}

$conf['tpl']['users'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}users ORDER BY name");
$conf['tpl']['cat'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_kat ORDER BY sort DESC")+array('0'=>'Новое');
$conf['tpl']['cc'] = spisok("SELECT kid, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_plan GROUP BY kid");
$conf['tpl']['dev'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_plan WHERE 1=1".($_GET['id'] ? " AND id=".(int)$_GET['id'] : '').(isset($_GET['kid']) ? " AND kid=".(int)$_GET['kid'] : '')." ORDER BY sort DESC LIMIT ".($_GET['p']*5).",5"));
$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/5 AS pcount"), 0, 'pcount');

if(!$_GET['id']){
	$conf['tpl']['comments'] = spisok("SELECT u.num, COUNT(*) FROM {$conf['db']['prefix']}comments_url AS u, {$conf['db']['prefix']}comments_txt AS t WHERE u.id=t.uid AND u.url LIKE '%/{$arg['modpath']}/%' GROUP BY u.num");
	$conf['tpl']['golos'] = spisok("SELECT pid, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_golos GROUP BY pid");
	$conf['tpl']['mygolos'] = spisok("SELECT pid, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_golos WHERE sid=".(int)$conf['user']['sess']['id']." GROUP BY pid");
}

?>