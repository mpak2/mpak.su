<? die;

$tpl['wks'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_workers"), "uid");

$tpl['projects'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_projects"));

$tpl['workers'] = mpqn(mpqw("SELECT w.*, pw.time, pw.duration, u.name AS uname, u.fm, u.im, u.ot, pw.description, p.name AS projects, t.id AS tasks_id, t.name AS tasks_name, w.price/(60*60*8*24) AS course
	FROM {$conf['db']['prefix']}{$arg['modpath']}_workers AS w
	LEFT JOIN {$conf['db']['prefix']}users AS u ON (w.uid=u.id)
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects_works AS pw ON (w.uid=pw.uid AND pw.duration=0)
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks AS t ON (pw.tasks_id=t.id)
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects AS p ON (pw.projects_id = p.id)
	WHERE 1". ($_GET['uid'] ? " AND w.uid=". (int)$_GET['uid'] : ""). "
"), "uid");// mpre($tpl['workers']);

if($uid = $tpl['workers'][ $_GET['uid'] ]){
	$tpl["projects_works"] = mpqn(mpqw("SELECT SQL_CALC_FOUND_ROWS pw.*, pd.year, pd.week, pd.month, p.name AS projects, p.id AS projects_id, w.name AS works, t.id AS tasks_id, t.name AS tasks_name
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works AS pw
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_period AS pd ON (pw.period_id=pd.id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks AS t ON (pw.tasks_id=t.id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects AS p ON (pw.projects_id=p.id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_works AS w ON (pw.works_id=w.id)
		WHERE pw.uid=". (int)$uid['uid']. " ORDER BY pw.id DESC LIMIT ". ($_GET['p']*5). ",5"
	)); $tpl['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/5 AS cnt"), 0, "cnt"), false);
//mpre($tpl["projects_works"]);

	$tpl['stat_day'] = mpqn(mpqw("SELECT pw.*, p.year, p.week, p.month, ps.uid AS projects_uid
		FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works AS pw
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects AS ps ON (pw.projects_id=ps.id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_period AS p ON (pw.period_id=p.id)
		WHERE pw.duration>0 AND pw.uid=". (int)$uid['uid']. "
		ORDER BY p.year, p.week, ps.uid DESC"
	));

	$tpl['wages'] = mpqn(mpqw("SELECT uid, SUM(sum) AS sum, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_wages WHERE workers_id=". (int)$uid['id']. " GROUP BY uid"), "uid");
	$tpl['wages_projects'] = mpqn(mpqw("SELECT uid, projects_id, SUM(sum) AS sum, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_wages WHERE workers_id=". (int)$uid['id']. " GROUP BY uid"), "uid", "projects_id");

	foreach($tpl['stat_day'] as $v){
		$d = date("d.m.Y", $v['time']);
		$tpl['days'][ $v['year'] ][ $v['month'] ][ $v['week'] ][ $d ][ $v['id'] ] = $v;
		$tpl['days'][ $v['year'] ][ $v['month'] ][ $v['week'] ][ $d ][0]["sum"] += $v['duration'];

//		$tpl['duration'][ $v['year'] ]['sum'] += $v['duration'];
		$tpl['duration'][ $v['year'] ][ $v['month'] ]['sum'] += $v['duration'];
		$tpl['duration'][ $v['year'] ][ $v['month'] ][ $v['week'] ]['sum'] += $v['duration'];

		$tpl['projects_stat'][ $v['projects_uid'] ][ $v['projects_id'] ] += $v['duration'];
		$tpl['projects_sum']["sum"][ $v['projects_uid'] ] += $v['duration'];
	}

	$tpl['month'] = explode(",", $conf['settings']['themes_month']);
}

?>