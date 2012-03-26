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

$banner = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE banner=1"));

?>
<? if($banner): ?>
	<div class="recommend block">
		<? foreach($banner as $k=>$v): ?>
			<div class="recommend-item">
				<div class="image"><img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:index/w:80/h:130/null/img.jpg"></div>
				<div class="info" style="height:135px; position:relative;">
						<a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=$v['name']?>"><?=mpue($v['name'])?></a>
						<div class="price">
							<strong>Цена: </strong><span><?=$v['coast_1']?> руб.</span>
						</div>
						<div style="margin-bottom:10px;">
							<? if($v['coast_1_old']): ?>
								<strong>Цена старая: </strong><span style="text-decoration:line-through;"><?=$v['coast_1_old']?> руб.</span>
							<? endif; ?>
						</div>
						<div style="position:absolute; bottom:5px; right:0;">
							<a href="/<?=$arg['modname']?>/<?=$v['id']?>" class="buy">Купить</a> <span id="ok281"></span>
						</div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>