<? die;

$tpl['tasks_status'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_tasks_status"));
$tpl['tags'] = array(0=>array("id"=>0, "name"=>""))+mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_tags WHERE uid=". (int)$conf['user']['uid']));

$tpl['tasks'] = mpqn(mpqw($sql = "SELECT t.*, SUM(pw.duration) AS duration, p.name AS projects_name
	FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_tasks"). " AS t
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects AS p ON (t.projects_id=p.id)
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks_status AS ts ON (t.tasks_status_id=ts.id)
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_projects_works AS pw ON (t.id=pw.tasks_id)
	WHERE 1". mpwr($tn, $_GET, "t."). " GROUP BY t.id ORDER BY t.up DESC"
));// mpre($tpl['tasks']);

$tpl['tasks_comments'] = mpqn(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_tasks_comments WHERE tasks_id IN (". implode(",", array_keys($tpl['tasks']) ?: array(0)). ") ORDER BY id DESC"), 'tasks_id', 'id');

?>