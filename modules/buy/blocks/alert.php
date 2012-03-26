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

$basket = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE status=1 ORDER BY id DESC"));

$order = mpqn(mpqw("SELECT o.* FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON id.id=o.index_id WHERE o.basket_id IN (". implode(",", array_keys($basket ?: array(0))). ")"), 'basket_id', 'id');

$status = array(0=>'Новый', 1=>'Заказ', 2=>'Выполнен', 3=>'Отменен');

?>
<div>
	<? foreach($basket as $k=>$v): ?>
		<div>
			<span style="float:right;"><?=date('Y.m.d H:i:s', $v['time'])?></span>
			<span>
				<a href="/?m[<?=$arg['modpath']?>]=admin&r=<?=$conf['db']['prefix']?><?=$arg['modpath']?>_basket&where[id]=<?=$v['id']?>">
					Заказ #<?=$v['id']?> <?=count($order[ $v['id'] ])?>
				</a> <?=$status[ $v['status'] ]?>
			</span>
		</div>
	<? endforeach; ?>
</div>