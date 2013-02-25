<? die;

$basket_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_basket",
	array("uid"=>$conf['user']['uid'], "status"=>0),
	array("time"=>time(), "uid"=>$conf['user']['uid'], "status"=>0)
);

if($_REQUEST['index_id']){
	if($_REQUEST['count']){
		$order_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_basket_order",
			array("basket_id"=>$basket_id, "index_id"=>$_REQUEST['index_id']),
			array("basket_id"=>$basket_id, "time"=>time())+$_REQUEST,
			array("count"=>$_GET['count'])
		);
	}else{
		mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_basket_order WHERE basket_id=". (int)$basket_id. " AND index_id=". (int)$_GET['товар']);// mpre($sql);
	}
	if(!array_key_exists("null", $_GET)){
		header("Location: /{$arg['modname']}:{$arg['fe']}");
	} exit($order_id);
}

$conf['price']['order'] = mpql(mpqw("SELECT id.*, o.count AS count, id.price FROM {$conf['db']['prefix']}{$arg['modpath']}_basket_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON o.index_id=id.id WHERE o.basket_id=". (int)$basket_id. " GROUP BY o.id"));

if($_POST && ($mpdbf = mpdbf("{$conf['db']['prefix']}{$arg['modpath']}_basket", $_POST))){
	if($conf['price']['order']){
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_basket SET status=1, $mpdbf WHERE id=". (int)$basket_id);
		echo "Ваш заказ #$basket_id принят. Вам перезвонят. <a href=/>На главную</a>";
		mpevent("Оформление заказа", mysql_insert_id());
	}else{
		echo "Корзина пуста";
	} exit;
}


?>