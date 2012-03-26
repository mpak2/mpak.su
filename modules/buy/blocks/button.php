<? die; # Нуль

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

if($basket_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_basket", array("uid"=>$conf['user']['uid'], "status"=>0))){
//	$sum = mpql(mpqw($sql = "SELECT SUM((id.price*{$conf['settings']['price_kurs']}+)*o.count) AS sum FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON o.index_id=id.id WHERE o.basket_id=". (int)$basket_id. " GROUP BY o.id"), 0);
//$sum = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10")); //$dat

$sum = mpql(mpqw("SELECT SUM(o.count*ROUND({$conf['settings']['buy_kurs']}*id.price+(COALESCE(p.premium, 0)*COALESCE(pr.premium, 0))+COALESCE(d.markup, 0))) AS sum FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON o.index_id=id.id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_price AS pr ON id.price_id=pr.id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_premium AS p ON id.premium_id=p.id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_diameter AS d ON id.diameter_id=d.id WHERE o.basket_id=". (int)$basket_id), 0);

}
?>
<a href="/<?=$arg['modpath']?>:basket" style="color:white;">
	<div style="cursor:pointer;" class="cart-inner">
		Корзина: <span id="cart_cost"><?=number_format($sum['sum'], 0)?></span> р.
	</div>
</a>