<? die;

$conf['tpl']['diameter'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_diameter"));
$conf['tpl']['premium'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_premium"));
$conf['tpl']['price'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_price"));


$conf['tpl']['csv'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE 1". (array_key_exists('season', $_GET) ? " AND price_id IN (SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_price WHERE season=". (int)$_GET['season']. ")" : "")));

foreach($conf['tpl']['csv'] as $k=>$v){
	$conf['tpl']['csv'][ $k ]['sum'] = number_format($v['price']*$conf['settings']['price_kurs']+$conf['tpl']['diameter'][ $v['diameter_id'] ]['markup']+$conf['tpl']['diameter'][ $v['diameter_id'] ]['delivery']+($conf['tpl']['premium'][ $v['premium_id'] ]['premium']*$conf['tpl']['price'][ $v['price_id'] ]['premium']), 2, ',', '');
//	$conf['tpl']['csv'][ $k ]['sum']
	$conf['tpl']['csv'][ $k ] = array_intersect_key($conf['tpl']['csv'][ $k ], array_flip(array('width','w','ot','d','name','count','sum')));
}// mpre($conf['tpl']['csv']); exit;

//mpre(array_shift($conf['tpl']['csv']));

echo mpqwt($conf['tpl']['csv']);

?>