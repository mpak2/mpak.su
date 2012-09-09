<? die;

//$tpl['cat'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_cat ORDER BY sort DESC")+array('0'=>'Новое');
$tpl['cat'] = array(0=>array('id'=>'0', 'name'=>'Новое'))+mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat ORDER BY sort DESC"));

if($_POST['f']){
	$plan_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_plan",
		array("id"=>$_POST["plan_id"]), null, array($_POST['f']=>$_POST['val'])
	); exit(''. $plan_id);
}else if($_POST['text'] && array_key_exists('null', $_GET)){
	$_POST['text'] = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['text']));
	$_POST['text'] = preg_replace ( '/(?<!S)([A-Za-zА-Яа-яЁё0-9_.\-]+\@{1}[A-Za-zА-Яа-яЁё0-9\.|-|_]*[.]{1}[a-z-а-я]{2,5})/u', '<a href="mailto:$1">$1</a>', $_POST['text'] );
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_work SET time=". time(). ", plan_id=". (int)$_POST['plan_id']. ", uid=". (int)$conf['user']['uid']. ", description=\"". mpquot($_POST['text']). "\"");
	echo json_encode(array('time'=>date('Y.m.d H:i:d'), 'name'=>$conf['user']['uname'], 'text'=>$_POST['text']));
	exit;
}else if($_POST['submit'] && !empty($_POST['plan'])){
	$max = mpql(mpqw("SELECT MAX(sort)+1 AS max FROM {$conf['db']['prefix']}{$arg['modpath']}_plan"), 0, 'max');
	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_plan SET uid=".(int)$conf['user']['uid'].", time=".time().", plan=\"".($arg['access'] >= 4 ? mpquot($_POST['plan']) : htmlspecialchars(mpquot($_POST['plan'])))."\", performers_id=". (int)$_POST['performers_id']. ", sort=".(int)$max);
	mpevent("Постановка новой задачи", "http://". mpidn($_SERVER['HTTP_HOST']). "/{$arg['modname']}/". mysql_insert_id(), $conf['user']['uid']);
	header("Location: ". $_SERVER['REQUEST_URI']);
}elseif($_POST['golos']){
	mpevent("Голосование за задачу", $_POST['golos']);
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_golos SET time=". time(). ", plan_id=". (int)$_POST['golos']. ", uid=". (int)$conf['user']['sess']['id']);
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_plan SET time=". time(). " WHERE id=". (int)$_POST['golos']);
	echo $_POST['golos']; exit;
}

/*elseif(($arg['access'] >= 5) && array_key_exists('value', $_POST) && $_POST['id']){
	if($plan = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_plan WHERE id=". (int)$_POST['id']), 0)){
		mpevent("Изменение статуса задачи", $_POST['id'], $plan['uid']);
		mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']}_plan SET time=". time(). ", cat_id=". (int)$_POST['value']. " WHERE id=". (int)$plan['id']);
	} if(!mysql_error()){
		echo $tpl['cat'][ $_POST['value'] ];
	} exit;
}*/

$tpl['cc'] = mpqn(mpqw("SELECT p.*, p.id AS plan_id, p.cat_id, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_plan AS p LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_cat AS c  ON (c.id=p.cat_id) WHERE 1". ($_GET['performers_id'] ? " AND performers_id=". (int)$_GET['performers_id'] : ""). " GROUP BY c.id"), 'cat_id');

$tpl['dev'] = mpqn(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_plan"). mpwr($tn)." ORDER BY time DESC LIMIT ".($_GET['p']*5).",5"));// echo $sql;

$tpl['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/5 AS pcount"), 0, 'pcount');

$tpl['performers'] = array(0=>array("id"=>0, "name"=>"Всем"))+mpqn(mpqw("SELECT p.*, CONCAT(u.name, ' (', p.name, ')') AS name FROM {$conf['db']['prefix']}{$arg['modpath']}_performers AS p LEFT JOIN {$conf['db']['prefix']}users AS u ON (p.uid=u.id)"));

if(!$_GET['id']){
	$tpl['golos'] = spisok("SELECT plan_id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_golos GROUP BY plan_id");
	$tpl['mygolos'] = spisok("SELECT plan_id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_golos WHERE uid=".(int)$conf['user']['sess']['id']." GROUP BY plan_id");
	$tpl['work'] = mpqn(mpqw($sql = "SELECT w.*, u.name FROM {$conf['db']['prefix']}{$arg['modpath']}_work AS w LEFT JOIN {$conf['db']['prefix']}users AS u ON w.uid=u.id WHERE w.plan_id IN (". implode(',', array_keys($tpl['dev'] ?: array(0))). ") ORDER BY id DESC"), 'plan_id', 'id');
}

?>