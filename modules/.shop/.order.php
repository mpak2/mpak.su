<? die;

$guest = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE name=\"".mpquot($conf['settings']['default_usr']). "\""), 0);
$sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE". ($_GET['id'] ? " id=".(int)max($_GET['id'], $_GET['set']) : " close=0")." AND uid=".(int)$conf['user']['uid'];
$conf['tpl']['basket'] = mpql(mpqw($sql), 0);
if(empty($conf['tpl']['basket'])){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_basket SET time=".time().", uid=".(int)$conf['user']['uid']);
	$conf['tpl']['basket'] = mpql(mpqw($sql), 0);
}// mpre($conf['tpl']['basket']);

# Проводим заказ меняя его статус на заказан
if($_GET['set'] && $_POST['submit'] && $conf['tpl']['basket']['id']){
	$sum = mpql(mpqw($sql = "SELECT SUM(o.count * d.price) AS sum FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.desc_id=d.id WHERE o.basket_id=".(int)$conf['tpl']['basket']['id']), 0, 'sum');
	$mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_basket", $_POST+array('id'=>$conf['tpl']['basket']['id'], 'close'=>1, 'sum'=>$sum));
	mpqw($sql = "UPDATE $tn SET $mpdbf WHERE id=".(int)$conf['tpl']['basket']['id']);
	$uid = mpql(mpqw("SELECT u.*, d.name AS dname FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.desc_id=d.id INNER JOIN {$conf['db']['prefix']}users AS u ON d.uid=u.id WHERE o.basket_id=". (int)$conf['tpl']['basket']['id']. " GROUP BY u.id "));
	foreach($uid as $k=>$v){
		mpmail($v['email'], "У вас новый заказ на {$v['dname']}", "В магазине http://shop.mpak.su/ получен заказ на одно или несколько наименований добавленное вами в каталог магазина. Подробную информацию о заказе вы можете посмотреть в личном кабинете пользователя {$v['name']} авторизовавшись на сайте http://shop.mpak.su/");
	}
}

if($_GET['did']){
	if(mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_order WHERE basket_id=".(int)$conf['tpl']['basket']['id']. " AND desc_id=". (int)$_GET['did']))){
		if($_GET['count']){
			mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_order SET count=count+". (int)$_GET['count']. " WHERE basket_id=". (int)$conf['tpl']['basket']['id']. " AND desc_id=". (int)$_GET['did']);
		}else{
			mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_order WHERE  basket_id=". (int)$conf['tpl']['basket']['id']. " AND desc_id=". (int)$_GET['did']);
		}
	}else{
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_order SET time=". time(). ", basket_id=". (int)$conf['tpl']['basket']['id']. ", desc_id=". (int)$_GET['did']. ", count=". (int)$_GET['count']);
	}
	header("Location: /{$arg['modpath']}:{$arg['fn']}");
} $conf['tpl']['order'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.desc_id=d.id WHERE o.basket_id=".(int)$conf['tpl']['basket']['id']));

?>