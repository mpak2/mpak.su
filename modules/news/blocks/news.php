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

$sql = "SELECT p.id, k.name, p.time, p.tema FROM {$conf['db']['prefix']}news_kat as k, {$conf['db']['prefix']}news_post as p WHERE k.id = p.kid ORDER BY id DESC LIMIT ".((int)$param['count'] ? $param['count'] : 3);
$month = array('01'=>'Январь', '02'=>'Февраль', '03'=>'Март', '04'=>'Апрель', '05'=>'Апрель');
foreach(mpql(mpqw($sql)) as $k=>$v){
	if ($cdate != date('Y.m.d', $v['time'])){
		echo "<b>".date('Y.m.d', $v['time'])."</b> <a href=/news>Все новости</a><br>";
		$cdate = date('Y.m.d', $v['time']);
	}
	echo "<small><b>".date('H:i', $v['time'])."</b> <a href='/news/{$v['id']}'>{$v['tema']}</small></a><br>";
}

?>