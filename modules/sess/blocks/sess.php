<? # Сессии

if(array_key_exists('confnum', $arg)){
	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);
echo <<<EOF
	<form>
		<input type=text value="$param">
	</form>
EOF;

	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

echo <<<EOF
<style>
	.sess_info {
		position: absolute;
		left:30px;
		top:10px;
		width: 220px;
		display: none;
		padding:7px;
		background-color: #eee;
		border: 1px solid #888;
		z-index:10;
	}
</style>
EOF;
$sql = "SELECT s.*, u.name AS uname FROM {$conf['db']['prefix']}sess AS s, {$conf['db']['prefix']}users AS u WHERE u.id=s.uid AND s.uid<>{$conf['user']['uid']} AND s.cnull>0 AND s.count>0 ORDER BY s.id DESC LIMIT 15";
foreach(mpql(mpqw($sql)) as $k=>$v){
	echo "<div id=\"sess\" style=\"position:relative;\">";
	echo "<span style=\"".(empty($v['ref']) ? "" : 'border-bottom: 1px dashed blue;')."\">". substr($v['agent'], 0, 30) . "</span>";
	echo "<div class=\"sess_info\">";
	echo "<b>Пользователь</b>: {$v['uname']}<br />";
	echo "<b>Время входа</b>: ".date('Y.m.d H:i:s')."<br />";
	echo "<b>Время на сайте</b>: ".($v['count_time'] >= 60 ? (int)($v['count_time']/60)."&nbsp;мин." : '').($v['count_time']%60)."c.<br />";
	echo "<b>Открытых страниц</b>: {$v['count']} / {$v['cnull']}<br />";
	echo "<b>Сетевой адрес</b>: <a href=\"http://geotool.servehttp.com/?ip={$v['ip']}\">{$v['ip']}</a><br />";
	echo "<b>Агент</b>: {$v['agent']}<br />";
	echo "<b>Адрес входа</b>: <a href=\"{$v['url']}\">{$v['url']}</a><br />";
	if ($v['ref']) echo "<b>Реф</b>: <a terget=\"_blank\" href=\"{$v['ref']}\">".urldecode($v['ref'])."</a><br />";
	echo "</div>";
	echo "</div>";
}

echo <<<EOF
	<script>
		$('document').ready(function (){
			$("div#sess").hover(
				function() {
					$(this).find("div").show(300);
				},
				function() {
					$(this).find("div").hide(300);
				}
			)
		})
	</script>
EOF;

?>
