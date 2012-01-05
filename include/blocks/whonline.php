<? die; # КтоОнлайн

if ((int)$$arg['confnum']) return;
$sql = "SELECT s.id, u.name, s.last_time, s.ip FROM {$GLOBALS['conf']['db']['prefix']}sess as s, {$GLOBALS['conf']['db']['prefix']}users as u WHERE u.id = s.uid AND s.last_time >= ".(time() - $conf['settings']['sess_time']);
echo "<table cellspacing='0' cellpadding='0' border='0' width='100%'>";
foreach(mpql(mpqw($sql)) as $k=>$v){
	echo "<tr><td><a href='/?m[sess]=admin&del={$v['id']}'><img src='/img/del.png' border='0'></a></td><td>{$v['name']}</td><td>".date('H:i:s', $v['last_time'])."</td><td>{$v['ip']}</td></tr>";
}
echo "</table>";

?>