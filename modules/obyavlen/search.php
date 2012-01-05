<? die;

$fields = mpql(mpqw("SELECT *, k.id as kid, k.name as kname, COUNT(*) as count FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat as k, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole as p, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kpole as kp WHERE p.type<>'img' AND k.id=kp.kid AND kp.pid=p.id".($_GET['kid'] ? " AND kp.kid=".(int)$_GET['kid'] : '')." GROUP BY p.id"));
if ($_POST){
	echo "<pre>"; print_r($_POST); echo "</pre>";
	$sql = "SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop WHERE";
	$qw = array('<'=>'<', '>'=>'>', '='=>'=', '<>'=>'<>');
	foreach($fields as $k=>$v){
//		echo "<pre>"; print_r($v); echo "</pre>";
		if ($_POST['zn_'.$v['pid']] && $_POST['qw_'.$v['pid']]){
			$sql .= " (pid={$v['pid']} AND val{$qw[ $_POST['zn_'.$v['pid']] ]}{$_POST['qw_'.$v['pid']]})";
		}
	}
	$sql .= " LIMIT 10";
	echo $sql;
	echo "<pre>"; print_r(mpql(mpqw($sql))); echo "</pre>";
}
echo "<form method='post'>";
echo "<select onchange=\"document.location = '/?m[{$arg['modpath']}]=search&kid=' + options[selectedIndex].value;\"><option value=0></option>";
foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE aid>=2 ORDER BY name")) as $k=>$v){
	echo "<option value={$v['id']}".($_GET['kid'] == $v['id'] ? ' selected' : '').">{$v['name']}</option>";
}
echo "</select> <input type=submit value='Искать'>";

echo "<p><table>";
foreach($fields as $k=>$v){
	echo "<tr><td>".($v['count'] <= 1 ? "<b>{$v['kname']}</b> :" : '')."</td>";
	echo "<td>{$v['title']} :</td>";
	echo "<td><select name='zn_{$v['pid']}'>";
	foreach(array(''=>'не важно', 'like'=>'СОДЕРЖИТ', '<'=>'МЕНЬШЕ', '>'=>'БОЛЬШЕ', '='=>'РАВНО') as $n=>$m){
		echo "<option value='$n'".($_POST["zn_{$v['pid']}"] == $n ? " selected" : '').">$m</option>";
	}
	echo "</select></td>";
	echo "<td><input type=text name='qw_{$v['pid']}' value='".$_POST["qw_{$v['pid']}"]."'></td></tr>";
}
echo "</table>";
echo "</form>";

?>