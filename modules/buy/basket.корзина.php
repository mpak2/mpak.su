<? die;

$basket_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_basket",
	array("uid"=>$conf['user']['uid'], "status"=>0),
	array("time"=>time(), "uid"=>$conf['user']['uid'], "status"=>0)
);

if($index_id = $_GET['index_id']){
	if($count = $_GET['count']){
		mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_basket_orders",
			array("basket_id"=>$basket_id, "index_id"=>$index_id),
			array("basket_id"=>$basket_id, "time"=>time(), "index_id"=>$index_id, "count"=>$count),
			array("count"=>$count)
		);
	}else{
		mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_basket_orders WHERE basket_id=". (int)$basket_id. " AND index_id=". (int)$_GET['index_id']);
	} if(!array_key_exists("null", $_GET)){
		header("Location: /{$arg['modname']}:{$arg['fe']}");
	} exit;
}

$tpl['index'] = mpql(mpqw("SELECT i.*, o.count FROM {$conf['db']['prefix']}{$arg['modpath']}_basket_orders AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS i ON (o.index_id=i.id) WHERE o.basket_id=". (int)$basket_id));

//mpre($conf['price']['index']);

if($_POST && ($mpdbf = mpdbf("{$conf['db']['prefix']}{$arg['modpath']}_basket", $_POST))){
	if($tpl['index']){
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_basket SET status=1, $mpdbf WHERE id=". (int)$basket_id);
		echo "Ваш заказ #$basket_id принят. Вам перезвонят. <a href=/>На главную</a>";
	}else{
		echo "Корзина пуста";
	} exit;
}


?>