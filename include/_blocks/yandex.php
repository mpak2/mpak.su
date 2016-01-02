<? die; #ЯндексСчетчик

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		Номер яндекс счетчика
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;

	return;
}
$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

if($arg['access'] >= 4){
	echo "<ul style=\"margin:10px;\"><li><a href=\"http://metrika.yandex.ru/stat/?id=$param\">http://metrika.yandex.ru/stat/?id=$param</a></li></ul>";
}

echo <<<EOF
<!-- Yandex.Metrika -->
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<div style="display:none;"><script type="text/javascript">
try { var yaCounter$param = new Ya.Metrika($param); } catch(e){}
</script></div>
<noscript><div style="position:absolute"><img src="//mc.yandex.ru/watch/$param" alt="" /></div></noscript>
<!-- /Yandex.Metrika -->
EOF;

?>