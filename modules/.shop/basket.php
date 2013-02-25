<? die;

if((int)$_GET['set']){
	$balans = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}onpay_balances WHERE uid=".(int)$conf['user']['uid']), 0);
	$schet = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE (uid=".(int)$conf['user']['uid']." OR sid=".(int)$conf['user']['sess']['id']. ") AND close=1 AND id=".(int)$_GET['set']), 0);
	if($balans['sum'] >= $schet['sum']){
		mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']}_basket SET close=2 WHERE close=1 AND id=".(int)$schet['id']);
		mpqw($sql = "UPDATE {$conf['db']['prefix']}onpay_balances SET sum=sum-".(int)$schet['sum']." WHERE id=".(int)$balans['id']);
	}
}

if($_GET['id']){
	$conf['tpl']['basket'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE id=".(int)$_GET['id']), 0);
}else{
	$conf['tpl']['sum'] = (int)mpql(mpqw("SELECT sum FROM {$conf['db']['prefix']}onpay_balances WHERE uid=".(int)$conf['user']['uid']), 0, 'sum');
	$guest = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE name=\"".mpquot($conf['settings']['default_usr']). "\""), 0);
	$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE (sid=".(int)$conf['user']['sess']['id']. ($conf['user']['uid'] == $guest['id'] ? '' : " OR uid=".(int)$conf['user']['uid']). ") ORDER BY id DESC LIMIT ".((int)$_GET['p']*10).", 10";
	$conf['tpl']['basket'] = mpql(mpqw($sql));
	$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS pcount"), 0, 'pcount');
}

?>