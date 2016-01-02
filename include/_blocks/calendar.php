<? die; # Календарь

if ((int)$arg['confnum']){
	# Сохранение и востановление параметров модуля
	if (isset($_POST['param'])){
		$param = $_POST['param'];
		$sql = "UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}";
		mpqw($sql);
	}else{
		if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
			$param = base64_decode(unserialize($res[0]['param']));
	}

	echo "<form action='{$_SERVER['REQUEST_URI']}' method='post'>";
	echo "<textarea name='param[text]' style='width:100%; height: 400px'>".stripslashes ($param['text'])."</textarea>";
	echo "<p><input type='submit' value='Сохранить'>";
	echo "</form>";
	return;
}

$m = array('01'=>'Январь', '02'=>'Февраль', '03'=>'Март', '04'=>'Апрель', '05'=>'Май', '06'=>'Июнь', '07'=>'Июль', '08'=>'Август', '09'=>'Сентябрь', '10'=>'Октябрь', '11'=>'Ноябрь', '12'=>'Декабрь');
$dw = array('01'=>'пн', '02'=>'вт', '03'=>'ср', '04'=>'чт', '05'=>'пт', '07'=>'сб', '08'=>'вс');

$month = (int)$_GET['month'] ? (int)$_GET['month'] : date('m');
$year = (int)$_GET['year'] ? (int)$_GET['year'] : date('Y');

echo "<select id='month' name='month' onchange=\"document.location = '?".((int)$_GET['year'] ? "year={$_GET['year']}&" : '')."month=' + options[selectedIndex].value;\">";
foreach($m as $k=>$v){
	echo "<option value='$k'".($month == $k ? ' selected' : '').">$v</option>";
}
echo "</select>";
echo " <select id='year' name='year' onchange=\"document.location = '?".((int)$_GET['month'] ? "month={$_GET['month']}&" : '')."year=' + options[selectedIndex].value;\">";
for($i = date('Y') - 10; $i <= date('Y') + 10; $i++){
	echo "<option".($i == $year ? ' selected' : '').">$i</option>";
}
echo "</select><p>";

echo "<table bgcolor='#eeeeee' cellspacing='0' cellpadding='1' width='100%' border='0'><tr align='center' bgcolor='#bbbbbb'>";
foreach($dw as $k=>$v){
	echo "<td>".($k == 7 || $k == 8 ? '<b><font color=red>' : '')."$v".($k == 7 || $k == 8 ? '</font></b>' : '')."</td>";
}
//echo date('w', mktime(0, 0, 0, $month, 1, $year));
echo "</tr><tr align='center'>";
for($dt = 0; $dt < (6 + date('w', mktime(0, 0, 0, $month, 1, $year))) % 7 ; $dt++){
	echo "<td>&nbsp;</td>";
}


for($i = mktime (0, 0, 0, $month, 1, $year); $i <= mktime (0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year); $i +=86400){
	if (date('w', $i) % 7 == 1) echo "<tr align='center'>";
	echo "<td".(mktime (0, 0, 0, date('m'), date('d'), date('Y')) == $i ? " bgcolor='#dddddd'" : '').">".(date('w', $i) == 6 || date('w', $i) == 0 ? "<b><font color=red>" : '')."".date('d', $i)."".(date('w', $i) == 6 || date('w', $i) == 0 ? "</font></b>" : '')."</td>";
	if (date('w', $i) % 7 == 0) echo "</tr>";
}
echo "</table>";

//echo 60 * 60 * 24;
?>