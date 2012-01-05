<? die; # Баннер

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<p />Ширина: <input type="text" name="param[w]" value="{$param['w']}"> <input type="submit" value="Сохранить">
		<p />Высота: <input type="text" name="param[h]" value="{$param['h']}"> <input type="submit" value="Сохранить">
	</form>
EOF;

	return;
}
$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

if(!$param['w']) $param['w'] = 80; if(!$param['h']) $param['h'] = 100;

$v = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_type WHERE img<>'' $in ORDER BY RAND() LIMIT 1"), 0);
$conf['items_banner_tmp'][] = (int)$v['id'];

?>
<div class="gk-box column gk-box-left">
	<a class="serviceCatAa" href="/<?=$arg['modpath']?>:cat/<?=$v['id']?>">
			<div class="serviceCat" style="margin: 0 10px 0 0; border: 1px solid white; width: 108px; height:130px;">
					<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:obj/w:106/h:128/c:1/null/img.jpg" border="0" title="<?=$v['name']?>" alt="<?=$v['name']?>" />
			</div>
	</a>
</div>
