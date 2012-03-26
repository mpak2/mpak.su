<? die;

$basket_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_basket",
	array("uid"=>$conf['user']['uid'], "status"=>0),
	array("time"=>time(), "uid"=>$conf['user']['uid'], "status"=>0)
);

if($_GET['товар']){
	if($_GET['количество']){
		mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_order",
			array("basket_id"=>$basket_id, "index_id"=>$_GET['товар']),
			array("basket_id"=>$basket_id, "time"=>time(), "index_id"=>$_GET['товар'], "count"=>$_GET['количество']),
			array("count"=>$_GET['количество'])
		);
	}else{
		mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_order WHERE basket_id=". (int)$basket_id. " AND index_id=". (int)$_GET['товар']);// mpre($sql);
	}
	if(!array_key_exists("null", $_GET)){
		header("Location: /{$arg['modname']}:{$arg['fe']}");
	} exit;
}

$conf['price']['order'] = mpql(mpqw("SELECT id.*, o.count AS count, id.price, p.premium, d.markup, o.count*ROUND({$conf['settings']['price_kurs']}*id.price+(COALESCE(p.premium, 0)*COALESCE(pr.premium, 0))+COALESCE(d.markup, 0)) AS sum FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON o.index_id=id.id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_price AS pr ON id.price_id=pr.id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_premium AS p ON id.premium_id=p.id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_diameter AS d ON id.diameter_id=d.id WHERE o.basket_id=". (int)$basket_id. " GROUP BY o.id"));

//mpre($conf['price']['order']);

//mpre($conf['price']['order']);

//mpre($conf['price']['order']);

$conf['price']['delivery'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_delivery"));// mpre($conf['price']['order']);

if($_POST && ($mpdbf = mpdbf("{$conf['db']['prefix']}{$arg['modpath']}_basket", $_POST))){
	if($conf['price']['order']){
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_basket SET status=1, $mpdbf WHERE id=". (int)$basket_id);
		echo "Ваш заказ #$basket_id принят. Вам перезвонят. <a href=/>На главную</a>";
	}else{
		echo "Корзина пуста";
	} exit;
}


?>