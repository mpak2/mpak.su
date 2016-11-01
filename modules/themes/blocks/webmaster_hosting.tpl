<p>
	<?=aedit("/themes:admin/r:mp_themes_webmaster_hosting")?>
	<? if(!$WEBMASTER_HOSTING = rb("webmaster_hosting")): mpre("Список хостингов не найден"); ?>
	<? elseif(!$WEBMASTER_HOSTING_PAYMENTS = rb("webmaster_hosting_payments", "webmaster_hosting_id", "id", $WEBMASTER_HOSTING)): mpre("Список оплат не задан"); ?>
	<? else: ?>
		<? foreach(rb($WEBMASTER_HOSTING, "hide", 'id', 0) as $webmaster_hosting): ?>
			<? if(!$webmaster_hosting_payments = rb($WEBMASTER_HOSTING_PAYMENTS, "webmaster_hosting_id", $webmaster_hosting['id'])): mpre("Ошибка получения оплаты хостинга"); ?>
			<? elseif(!$rest = number_format(($webmaster_hosting_payments['up']-time())/86400, 2)): mpre("Ошибка вычисления остатка дней") ?>
			<? else: ?>
				<a href="/themes:admin/r:themes-webmaster_hosting_payments?&where[webmaster_hosting_id]=<?=$webmaster_hosting['id']?>"><?=$webmaster_hosting['name']?></a>
				<span title="Суток до окончания оплаты" style="color:<?=($rest < 10 ? "red" : "inherit")?>; font-weight:bold;"><?=$rest?></span>
			<? endif; ?>
		<? endforeach; ?>
	<? endif; ?>
</p>
