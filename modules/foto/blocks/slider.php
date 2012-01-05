<? die; # Слайдер

if ((int)$arg['confnum']){
$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");
	$option = '<option value="0">Все</option>';
	foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat")) as $k=>$v){
		$option .= "<option value='{$v['id']}'". ($param['kid'] == $v['id'] ? ' selected' : '') . ">{$v['name']}</option>";
	}
echo <<<EOF
	<form method="post">
		<br />Категория: <select name="param[kid]">$option</select>
		<br />Ширина: <input type="text" name="param[width]" value="{$param['width']}">
		<br />Высота: <input type="text" name="param[height]" value="{$param['height']}">
		<br /><input type="submit" value="Сохранить">
	</form>
EOF;

	return;
}
$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

echo <<<EOF
<link rel="stylesheet" href="/include/dhonishow/dhonishow.css" type="text/css" media="screen" />
<script src="/include/dhonishow/jquery.dhonishow.js" type="text/javascript"></script>
EOF;

echo "<div class=\"dhonishow true hide-buttons_true hide-alt_true autoplay_4 duration_1\" style=\"width:{$param['width']}px; height:{$param['height']}px; padding:0; margin:0;\">";
	foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE 1=1". ($param['kid'] ? " WHERE kid=".(int)$param['kid'] : ''). " ORDER BY RAND() LIMIT 5")) as $k=>$v){
		echo "<a href='/{$arg['modpath']}". ($param['kid'] ? "/cat:{$param['kid']}" : ''). "'>";
		echo "<img src=\"/{$arg['modpath']}:img/{$v['id']}/w:{$param['width']}/h:{$param['height']}/c:1/null/img.jpg\">";
		echo "</a>";
	}
echo "</div>";

?>