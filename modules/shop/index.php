<? die;

$tpl[$t = 'index'] = mpqn(mpqw("SELECT SQL_CALC_FOUND_ROWS i.*, v.name AS vendor
	FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index"). " AS i
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_vendor AS v ON (v.id=i.vendor_id)
	". (mpwr($tn, $_GET, "i.") ?: "LIMIT ". ($_GET['p']*20). ",20")
));

$tpl['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/20 AS cnt"), 0, 'cnt'));
