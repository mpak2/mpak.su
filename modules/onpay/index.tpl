<?// if($conf['user']['uid'] <= 0): ?>
	<?// include mpopendir("/modules/users/login.tpl"); ?>
<? if($_GET['id']): ?>
	<iframe src="https://secure.onpay.ru/pay/<?=$conf['settings']['onpay_onpay_form']?>?pay_mode=fix&price=<?=$conf['tpl']['operation']['sum']?>&currency=RUR&pay_for=<?=$conf['tpl']['operation']['id']?>&convert=no<?=($_conf['user']['email'] ? "&user_email=admin@{$_conf['user']['email']}" : '')?>&url_success=http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modname']?>" width="100%" height="1500px" frameborder=0 scrolling=no /></iframe>
<? else: ?>
	<div style="margin:100px 0; text-align:center;">
		Ваш баланс в системе:
		<span style="font-size:150%; font-weight:bold;"><?=(float)$conf['tpl']['money']['sum']?></span>
		<!-- [settings:<?=$arg['modpath']?>_currency] -->
		<a href="/<?=$arg['modpath']?>:new">пополнить</a>
	</div>
<? endif; ?>