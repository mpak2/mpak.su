<? die; # ПоследниеНовости

if ((int)$arg['confnum']){
	# Сохранение и востановление параметров модуля
	if (isset($_POST['param'])){
		$param = $_POST['param'];
		$sql = "UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}";
		mpqw($sql);
	}else{
		if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
			$param = unserialize($res[0]['param']);
	}

	echo "<form action='{$_SERVER['REQUEST_URI']}' method='post'>";
	echo "<table border='1' cellspacing='0' cellpadding='5' width='90%'>";

	echo "Количество новостей в блоке: <select name='param[count]'><option></option>";
	for($i = 3; $i < 30; $i ++)
		echo "<option".($i == $param['count'] ? ' selected' : '').">$i</option>";
	echo "</select></table><p><input type='submit' value='Сохранить'></form>";
	return;
}

# Выбор параметров
if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"))))
	$param = @unserialize($res[0]['param']);

$news = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_post"));

?>
<ul>
	<? foreach($news as $v): ?>
		<li><a href="/<?=$arg['modname']?>/<?=$v['id']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>