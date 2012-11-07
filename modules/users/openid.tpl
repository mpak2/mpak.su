<link type="text/css" rel="stylesheet" href="/include/jquery/openid-selector/css/openid.css" />
<script type="text/javascript" src="/include/jquery/openid-selector/js/openid-jquery.js"></script>
<script type="text/javascript" src="/include/jquery/openid-selector/js/openid-ru.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		openid.init('openid_identifier');
//		openid.setDemoMode(false); //Не сабмитить форму, только для тестирования клиентского javascript'а
	});
</script>
<!-- /Simple OpenID Selector -->
<style type="text/css">
	/* Basic page formatting */
	body {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	}
</style>
<h2>Демонстрация JQuery Simple OpenID Selector</h2>
<p>Этот простой пример показывает как вы можете включить Javascript на вашей странице.</p>
<br/>
<!-- Simple OpenID Selector -->
<form method="get" id="openid_form">
	<input type="hidden" name="action" value="verify" />
	<fieldset>
		<legend>Войти или создать новый аккаунт</legend>
		<div id="openid_choice">
			<p>Пожайлуста, выберите вашего аккаунт-провайдера:</p>
			<div id="openid_btns"></div>
		</div>
		<div id="openid_input_area">
			<input id="openid_identifier" name="openid_identifier" type="text" value="http://" />
			<input id="openid_submit" type="submit" value="Войти"/>
		</div>
		<noscript>
			<p>OpenID это сервис который позволяет вам входить в разные веб-сайты пользуясь одной учетной записью.
			Узнайте <a href="http://openid.net/what/">больше о OpenID</a> и <a href="http://openid.net/get/">как получить OpenID аккаунт</a>.</p>
		</noscript>
	</fieldset>
</form>
<!-- /Simple OpenID Selector -->
