<? die;

header("Cache-Control: no-cache, must-revalidate"); header("Pragma: no-cache");
$guest = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE name=\"".mpquot($conf['settings']['default_usr']). "\""), 0);
$sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE". ($_GET['id'] ? " id=".(int)max($_GET['id'], $_GET['set']) : " close=0")." AND (sid=". (int)$conf['user']['sess']['id']. ($conf['user']['uid'] == $guest['id'] ? '' : " OR uid=".(int)$conf['user']['uid']). ")";
$conf['tpl']['basket'] = mpql(mpqw($sql), 0);
if(empty($conf['tpl']['basket'])){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_basket SET time=".time().", sid=".(int)$conf['user']['sess']['id'].", uid=".(int)$conf['user']['uid']);
	$conf['tpl']['basket'] = mpql(mpqw($sql), 0);
}// mpre($conf['tpl']['basket']);

# Проводим заказ меняя его статус на заказан
if($_GET['set'] && $_POST['submit'] && $conf['tpl']['basket']['id']){
	$sum = mpql(mpqw($sql = "SELECT SUM(o.count * d.price) AS sum FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.did=d.id WHERE o.bid=".(int)$conf['tpl']['basket']['id']), 0, 'sum');
	$mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_basket", $_POST+array('id'=>$conf['tpl']['basket']['id'], 'close'=>1, 'sum'=>$sum));
	mpqw("UPDATE $tn SET $mpdbf WHERE id=".(int)$conf['tpl']['basket']['id']);
}

# Добавление товара к списку товаров в корзине
if($_GET['did']){
	$get = $_GET+array(
		'time'=>time(),
		'bid'=>$conf['tpl']['basket']['id'],
		'sid'=>$conf['user']['sess']['id'],
		'uid'=>$conf['user']['uid'],
		'did'=>$_GET['did'],
		'count'=>$_GET['count'] ? $_GET['count'] : 1,
	);
	$mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_order", $get);
	if($_GET['count'] > 0){
		mpqw("INSERT INTO $tn SET $mpdbf ON DUPLICATE KEY UPDATE count=count+".(int)($_GET['count'] ? $_GET['count'] : 1));
	}
}

$conf['tpl']['order'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.did=d.id WHERE o.bid=".(int)$conf['tpl']['basket']['id']));

?>