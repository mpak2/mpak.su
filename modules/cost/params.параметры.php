<? die;

$tpl['projects'] = mpqn(mpqw($sql = "SELECT p.*, u.name AS uname
	FROM {$conf['db']['prefix']}{$arg['modpath']}_projects AS p
	LEFT JOIN {$conf['db']['prefix']}users AS u ON (p.uid=u.id)
	WHERE uid=". (int)$conf['user']['uid']. ($_GET['id'] ? " AND p.id=". (int)$_GET['id'] : " LIMIT 20")
));

if(empty($tpl['projects'])) header("Location: /{$arg['modname']}:projects");
