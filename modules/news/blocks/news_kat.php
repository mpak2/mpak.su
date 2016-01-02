<? # КатегорииНовостей

if(array_key_exists('confnum', $arg)){
	# Сохранение и востановление параметров модуля
//	print_r($_POST['param']);
/*	if (isset($_POST['param'])){
		$param = $_POST['param'];
		$sql = "UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}";
		mpqw($sql);
	}else{
		if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
			$param = unserialize($res[0]['param']);
	}

	echo "<form action='{$_SERVER['REQUEST_URI']}' method='post'>";
	echo "<table border='1' cellspacing='0' cellpadding='5' width='90%'>";

	echo "Количество новостей в блоке: <select name='param[count]'><option></option>";
	for($i = 3; $i < 30; $i ++)
		echo "<option".($i == $param['count'] ? ' selected' : '').">$i</option>";
	echo "</select>";

	echo "</table>";
	echo "<p><input type='submit' value='Сохранить'>";
	echo "</form>";*/
	return;
}

$cat = mpql(mpqw("SELECT id, parent, name FROM {$GLOBALS['conf']['db']['prefix']}news_kat"));

?>

<ul>
	<? foreach($cat as $k=>$v): ?>
		<li><a href="/<?=$arg['modpath']?>/kid:<?=$v['id']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
