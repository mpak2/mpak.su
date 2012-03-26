<? die;

if($_GET['id']){
	$tpl['index'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$_GET['id']));
	$tpl['orders'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket AS b LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_basket_orders AS o ON (b.id=o.basket_id) WHERE b.status=0 AND b.uid=". (int)$conf['user']['uid']. " AND o.index_id=". (int)$_GET['id']), 0);// mpre($tpl['orders']);
}else{
	$tpl['index'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). mpwr($tn). " LIMIT ". ($_GET['p']*20). ",20"));

	$tpl['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/20 AS cnt"), 0, 'cnt'));
}

$tpl['type'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_type"));
$tpl['manufacturers'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_manufacturers"));

?>