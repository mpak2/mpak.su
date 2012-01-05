<div style="margin:100px 0; text-align:center;">
	Ваш баланс в системе:
	<span style="font-size:150%; font-weight:bold;"><?=(int)$conf['tpl']['money']['sum']?></span>
	<!-- [settings:<?=$arg['modpath']?>_currency] -->
	<a href="/<?=$arg['modpath']?>:new">пополнить</a>
</div>