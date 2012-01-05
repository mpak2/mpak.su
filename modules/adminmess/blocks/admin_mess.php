<? die; # Админсообщение

if ($func == 'conf') return;

$sql = "SELECT time, title, mess FROM {$GLOBALS['conf']['db']['prefix']}adminmess WHERE enabled = 1";
foreach(mpql(mpqw($sql)) as $k=>$v)
	echo "<p><center><b>{$v['title']}</b><p>{$v['mess']}</center>";

?>