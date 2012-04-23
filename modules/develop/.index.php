<? die;

$conf['tpl']['cat'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_kat ORDER BY sort DESC")+array(0=>"Новые");

if(array_key_exists("null", $_GET)){
	if($_POST['class'] == "klesh"){
		if($arg['access'] >= 3){
			if($plan = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_plan WHERE id=". (int)$_POST['plan_id']), 0)){
				mpevent("Изменение статуса задач разработки", $plan['name'], $plan['uid']);
				mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_plan SET kid=". (int)$_POST['val']. " WHERE id=". (int)$_POST['plan_id']);
			}
		}else{
			exit("Доступ запрещен");
		} exit($_POST['plan_id']);
	}elseif($_POST['text'] && array_key_exists('null', $_GET)){
		$_POST['text'] = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['text']));
		$_POST['text'] = preg_replace ( '/(?<!S)([A-Za-zА-Яа-яЁё0-9_.\-]+\@{1}[A-Za-zА-Яа-яЁё0-9\.|-|_]*[.]{1}[a-z-а-я]{2,5})/u', '<a href="mailto:$1">$1</a>', $_POST['text'] );
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_work SET time=". time(). ", plan_id=". (int)$_POST['plan_id']. ", uid=". (int)$conf['user']['uid']. ", description=\"". mpquot($_POST['text']). "\"");
		echo json_encode(array('time'=>date('Y.m.d H:i:d'), 'name'=>$conf['user']['uname'], 'text'=>$_POST['text']));
		exit;
	}elseif($_POST['submit'] && !empty($_POST['plan'])){
		$max = mpql(mpqw("SELECT MAX(sort)+1 AS max FROM {$conf['db']['prefix']}{$arg['modpath']}_plan"), 0, 'max');
		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_plan SET uid=".(int)$conf['user']['uid'].", time=".time().", plan=\"".($arg['access'] >= 4 ? mpquot($_POST['plan']) : htmlspecialchars(mpquot($_POST['plan'])))."\", sort=".(int)$max);
		mpevent("Постановка новой задачи", "http://". mpidn($_SERVER['HTTP_HOST']). "/{$arg['modname']}/". mysql_insert_id(), $conf['user']['uid']);
		header("Location: ". $_SERVER['REQUEST_URI']);
	}elseif($_POST['golos']){
		mpevent("Голосование за задачу", $_POST['golos']);
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_golos SET time=". time(). ", pid=". (int)$_POST['golos']. ", sid=". (int)$conf['user']['sess']['id']. " ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_plan SET time=". time(). " WHERE id=". (int)$_POST['golos']);
		exit($_POST['golos']);
	}
}

$conf['tpl']['cc'] = spisok("SELECT kid, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_plan GROUP BY kid");

$conf['tpl']['dev'] = mpqn(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_plan"). mpwr($tn)." ORDER BY time DESC LIMIT ".($_GET['p']*5).",5"));

$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/5 AS pcount"), 0, 'pcount');

if(!$_GET['id']){
	$conf['tpl']['golos'] = spisok("SELECT pid, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_golos GROUP BY pid");

	$conf['tpl']['mygolos'] = spisok("SELECT pid, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_golos WHERE sid=".(int)$conf['user']['sess']['id']." GROUP BY pid");

	$conf['tpl']['work'] = mpqn(mpqw($sql = "SELECT w.*, u.name FROM {$conf['db']['prefix']}{$arg['modpath']}_work AS w LEFT JOIN {$conf['db']['prefix']}users AS u ON w.uid=u.id WHERE w.plan_id IN (". implode(',', array_keys($conf['tpl']['dev'] ?: array(0))). ") ORDER BY id DESC"), 'plan_id', 'id');

//	echo $sql;
//	mpre($conf['tpl']['work']);
}

?>