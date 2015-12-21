<? # ОпенИД

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

echo <<<EOF
<style>
form{ font:12px arial,helvetica,sans-serif; }
#openid_url { background:#FFFFFF url(http://wiki.openid.net/f/openid-16x16.gif) no-repeat scroll 5px 50%; padding-left:25px; }
</style>

<form action="auth.php" method="GET">
    <input type="hidden" value="login" name="actionType">
    <h2>Sign in using OpenID / OAuth</h2>

    <input type="text" style="font-size: 12px;" value="" size="40" id="openid_url" name="openid_url"> &nbsp;
    <input type="submit" value="Sign in"> <br><small>(e.g. http://username.myopenid.com)</small><br /><br />
</form>
EOF;

?>
