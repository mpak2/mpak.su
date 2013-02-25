<? die;

$tpl['sess'] = mpqn(mpqw($sql = "SELECT s.*, u.name AS uname
	FROM {$conf['db']['prefix']}{$arg['modpath']} AS s
	LEFT JOIN {$conf['db']['prefix']}users AS u ON (s.uid=u.id)
	LEFT JOIN {$conf['db']['prefix']}users_mem AS m ON (u.id=m.uid) 
	LEFT JOIN {$conf['db']['prefix']}users_grp AS g ON (m.grp_id=g.id)
	WHERE s.geo<>'' AND s.geo<>','". ($_GET['grp_id'] ? " AND g.id=". (int)$_GET['grp_id'] : ""). " ORDER BY id DESC LIMIT ". ($_GET['limit'] ? min($_GET['limit'], 100) : 20)
));// mpre($tpl['sess']);

$tpl['grp'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}users_grp"));
