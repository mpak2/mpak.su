<? die;

if(array_key_exists("null", $_GET)){
	$conf['settings']['theme'] = "print";
}

$tpl["workers"] = mpqn(mpqw("SELECT w.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_workers AS w LEFT JOIN {$conf['db']['prefix']}users AS u ON (w.uid=u.id)"), 'uid');

$conf['projects'] = mpqn(mpqw($sql = "SELECT *
	FROM {$conf['db']['prefix']}{$arg['modpath']}_projects
	WHERE 1 ". ($tpl["workers"][ $conf['user']['uid'] ] || ($arg['access'] >= 3) ? "" : " AND hide=0"). "
	". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : ""). "
	ORDER BY up DESC"
));// mpre($conf['projects']);

$tpl['tasks_status'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_tasks_status"));
$tpl['tags'] = array(0=>array("id"=>0, "name"=>""))+mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_tags WHERE uid=". (int)$conf['user']['uid']));

if(array_key_exists("null", $_GET) && $_POST){
	if($_POST['tasks_id']){
		if(array_key_exists("val", $_POST)){ # Обновление статуса задачи
			$tasks_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_tasks",
				array("id"=>$_POST['tasks_id']), null, array("up"=>time(), $_POST['f']=>$_POST['val'])
			);
			mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_projects SET up=". time(). " WHERE id=(SELECT projects_id FROM {$conf['db']['prefix']}{$arg['modpath']}_tasks WHERE id=". (int)$tasks_id. ")");
			exit($tasks_id);
		}else if($_POST['estimate']){ # Повышение оценки задачи
			$tasks_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_tasks", array("id"=>$_POST['tasks_id']), null, array("up"=>time(), "estimate"=>$_POST['estimate']));
			/*mpre($_POST);*/ exit($tasks_id);
		}else{
			$_POST['description'] = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['description']));
			$comments_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_tasks_comments",
				null, $w = array("time"=>time(), "uid"=>$conf['user']['uid'], "up"=>time())+$_POST
			);
			if($comments_id){
				if($_FILES['file'] && $fn = mpfn("{$conf['db']['prefix']}{$arg['modpath']}_tasks_comments", "file", $comments_id)){
					mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_tasks_comments", array("id"=>$comments_id), null, $f = array("file"=>$fn, "name"=>$_FILES["file"]["name"]));
				} $task_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_tasks", array("id"=>$w['tasks_id']), null, array("up"=>time()));
			}
			$tpl['tasks'] = array($w['tasks_id']=>array("id"=>$w['tasks_id']));// mpre($tpl['tasks']); exit;
			$tpl['tasks_comments'] = array($w['tasks_id']=>array($comments_id=>array("id"=>$comments_id, "uname"=>$conf['user']['uname'])+(array)$f+$w));
		}
	}else if(($p = $conf['projects'][ $_POST['projects_id'] ]) && array_key_exists('val', $_POST) && (($p['uid'] == $conf['user']['uid']) || ($arg['access'] > 3))){
		$tasks_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_projects",
			array("id"=>$_POST['projects_id']), null, array("hide"=>$_POST['val'])
		); exit($tasks_id);
	}else{ # Добавление новой задачи
		$_POST['description'] = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['description']));
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_projects SET up=". time(). " WHERE id=". $_GET['id']);

		$tasks_status = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_tasks_status ORDER BY sort LIMIT 1"), 0);
		$tasks_id = mpfdk($tn = "{$conf['db']['prefix']}{$arg['modpath']}_tasks",
			null, $w = array("time"=>time(),
			"tasks_status_id"=>$conf['settings']['cost_tasks_status_id'], "uid"=>$conf['user']['uid'], "projects_id"=>$_GET['id'], "up"=>time(), "tasks_status_id"=>$tasks_status['id'])+$_POST
		); $tpl['tasks'] = array($tasks_id=>array("id"=>$tasks_id, "uname"=>$conf['user']['uname'])+$w);
	}
}else if($p = $conf['projects'][ $_GET['id'] ]){
	$tpl['projects_works'] = mpqn(mpqw("SELECT pw.*, t.name AS tasks_name, t.description AS tasks_description, p.premium
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works AS pw
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects AS p ON (pw.projects_id=p.id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_works AS w ON (pw.works_id=w.id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks AS t ON (pw.tasks_id=t.id)
		WHERE pw.projects_id=". (int)$p['id']. " ORDER BY pw.id DESC
	"), "works_id", "uid", "id");// mpre($tpl['projects_works']);

	$tpl["works"] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_works"));

	$tpl["worker"] = mpqn(mpqw("SELECT w.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_workers AS w LEFT JOIN {$conf['db']['prefix']}users AS u ON (w.uid=u.id)"));

	foreach($tpl['projects_works'] as $work_id=>$users){
		foreach($users as $uid=>$projects_works){
			$tpl["workers"][ $uid ]["course"] = $tpl["workers"][ $uid ]["price"] / (60*60*8*24); # Стоимость одной секунды сотрудника
			foreach($projects_works as $id=>$v){
				$cost = $v['duration'] * $tpl["workers"][ $uid ]["course"];
				if(array_key_exists("premium", $_GET)){
					$cost = $cost * (100+$p['premium'])/100;
				}
				$v['cost'] = number_format($cost, 2, '.', '');
				$v['length'] = mptс(time()-$v['duration'], 1);
				$tpl['projects_works'][ $work_id ][ $uid ][ $id ] = $v;

				$projects_works['cost'] = number_format($projects_works['cost'] + $cost, 2, '.', '');
				$projects_works['sum'] = $projects_works['sum']+$v['duration']*$tpl["workers"][ $uid ]["course"];
				$projects_works['length'] = mptс(time()-($projects_works['duration'] += $v['duration']), 1);
			} $tpl['projects_works'][ $work_id ][ $uid ][0] = $projects_works;

			$users['cost'] = number_format($users['cost'] + $projects_works['cost'], 2, '.', '');
			$users['sum'] = $users['sum']+$projects_works['sum'];
			$users['length'] = mptс(time()-($users['duration'] += $projects_works['duration']), 1);
		} $tpl['projects_works'][ $work_id ][0] = $users;

		if(!array_key_exists("premium", $_GET)){
			$projects['cost'] = ($projects['cost'] + $users['cost']) * ((100+$p['premium'])/100);
		} $projects['cost'] = number_format($projects['cost'], 2, '.', '');

		$projects['sum'] = $projects['sum']+$users['sum'];
		$projects['length'] = mptс(time()-($projects['duration'] += $users['duration']), 1);
	} $projects['sum'] = $projects['sum']*(1+$p['premium']/100); $tpl['projects_works'][0] = $projects;// mpre($projects);

	$tpl['sum'] = mpqn(mpqw("SELECT p.*, SUM(pp.sum) AS sum FROM {$conf['db']['prefix']}{$arg['modpath']}_projects AS p LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects_payment AS pp ON (p.id=pp.projects_id) WHERE p.id=". (int)$p['id']. " GROUP BY p.id"), 'id');

	$tpl['tasks'] = mpqn(mpqw("SELECT t.*, SUM( IF(pw.duration=0, UNIX_TIMESTAMP(NOW())-pw.time, pw.duration) ) AS duration, pw.id AS projects_works_id, pw.uid AS projects_works_uid, u.name AS uname
		FROM {$conf['db']['prefix']}{$arg['modpath']}_tasks AS t
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks_status AS ts ON (t.tasks_status_id=ts.id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects_works AS pw ON (t.id=pw.tasks_id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects AS p ON (pw.projects_id=p.id)
		LEFT JOIN {$conf['db']['prefix']}users AS u ON (pw.uid=u.id)
		WHERE ts.hide=0 AND t.projects_id=". (int)$p['id']. " GROUP BY t.id
		ORDER BY t.up DESC"
	));// mpre($tpl['tasks']);

	# Список комментариев к задачам выбранных по идентификаторам задач
	$tpl['tasks_comments'] = mpqn(mpqw($sql = "SELECT c.*, u.name AS uname
		FROM {$conf['db']['prefix']}{$arg['modpath']}_tasks_comments AS c
		LEFT JOIN {$conf['db']['prefix']}users AS u ON (c.uid=u.id)
		WHERE c.tasks_id IN (". implode(",", array_keys($tpl['tasks']) ?: array(0)). ") ORDER BY c.id DESC"
	), 'tasks_id', 'id');

	# Список запущенных на данный момент задач
	$tpl['tasks_works'] = mpqn(mpqw("SELECT *
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works
		WHERE duration=0"
	), 'tasks_id');// mpre($tpl['tasks_works']);

/*	$tpl['tasks_duration'] = mpqn(mpqw("SELECT tasks_id, SUM(duration) AS sum
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works
		WHERE tasks_id IN (". implode(",", array_keys($tpl['tasks']) ?: array(0)). ")
		GROUP BY tasks_id"
	), "tasks_id");*/

	$tpl['hide'] = array(0=>array("id"=>0, 'name'=>'Доступен'), 1=>array('id'=>1, 'name'=>'Скрыт'));

}else{
	$tpl['tasks_cnt'] = mpqn(mpqw("SELECT p.id, p.name, COUNT(t.id) AS cnt
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects AS p
		INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks AS t ON (p.id=t.projects_id)
		INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks_status AS ts ON (t.tasks_status_id=ts.id)
		WHERE ts.hide=0 GROUP BY p.id"
	));// mpre($tpl['tasks_cnt']);

	$tpl['tasks_uid'] = mpqn(mpqw("SELECT p.id, p.name, COUNT(t.id) AS cnt
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects AS p
		INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks AS t ON (p.id=t.projects_id)
		INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks_status AS ts ON (t.tasks_status_id=ts.id)
		WHERE ts.id=". (int)$conf['settings']["{$arg['modpath']}_tasks_status_id"]. " AND t.workers_id=". (int)$tpl["workers"][ $conf['user']['uid'] ]['id']. "
		GROUP BY p.id"
	));// mpre($tpl['tasks_uid']);

	$tpl['psum'] = mpqn(mpqw($sql = "SELECT projects_id, SUM(sum) AS sum
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_payment
		WHERE projects_id IN (". implode(",", array_keys($conf['projects']) ?: array(0)). ")
		GROUP BY projects_id"
	), 'projects_id');

	$tpl['wsum'] = mpqn(mpqw($sql = "SELECT pw.projects_id, SUM(pw.duration*(w.price/(3600*8*24))) AS duration
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works AS pw
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_workers AS w ON (pw.uid=w.uid)
		WHERE pw.projects_id IN (". implode(",", array_keys($conf['projects']) ?: array(0)). ")
		GROUP BY pw.projects_id"
	), 'projects_id');// echo $sql; mpre($conf['psum']);
}

?>