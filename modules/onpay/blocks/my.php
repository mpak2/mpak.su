<? # Нуль

if(array_key_exists('confnum', $arg)){
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

$sum = (int)mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_sum WHERE uid=". (int)$arg['uid']), 0, 'sum'); //$dat
$operations = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_operations WHERE uid=". (int)$arg['uid']. " ORDER BY id DESC LIMIT 10"));
$status = array(0=>'', 1=>'<span style=color:green>оплачен</span>');

?>
<div>
	<? if($arg['access'] > 3): ?>
		<span>
			<a href="/?m[onpay]=admin&r=mp_onpay_operations&where[uid]=<?=$arg['uid']?>">
				<img src="/img/aedit.png">
			</a>
		</span>
	<? endif; ?>
	<div style="margin:10px;">
		<div style="float:right;">
			<span>
				<b><?=$sum?> <?=$conf['settings']["{$arg['modpath']}_currency"]?></b>
<!--				<span><a href="http://secure.onpay.ru/pay/<?=$conf['settings']["{$arg['modpath']}_onpay_form"]?>?pay_mode=free&pay_for=&currency=RUR&convert=no&url_success=/onpay">пополнить</a></span>-->
				<span><a href="/onpay:new">пополнить</a></span>
			</span>
		</div>
		<span>Баланс:</span>
	</div>
	<div>
		<? foreach($operations as $k=>$v): ?>
			<div>
				<div style="float:right;">
					<span><?=$v['sum']?> <?=$conf['settings']["{$arg['modpath']}_currency"]?></span>
					<span>
						<? if($v['status']): ?>
							<?=$status[ $v['status'] ]?>
						<? else: ?>
							<a href="http://secure.onpay.ru/pay/<?=$conf['settings']["{$arg['modpath']}_onpay_form"]?>?pay_mode=fix&price=<?=$v['sum']?>&currency=RUR&pay_for=6&convert=no&url_success=/onpay:new/<?=$v['id']?>">оплатить</a>
						<? endif; ?>
					</span>
				</div>
				<span><?=$v['date']?></span>
				<span>Счет #<?=$v['id']?></span>
			</div>
		<? endforeach; ?>
	</div>
</div>
