<? die; # ФлешОблако

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

/*foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index LIMIT 10")) as $k=>$v){
	mpre($v);
}*/

//$read = mpql(mpqw($sql = "SELECT MAX(`read`) AS 'max', MIN(`read`) AS 'min' FROM {$conf['db']['prefix']}{$arg['modpath']}_obj"), 0);
//mpre($read);

$list = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj ORDER BY `read` DESC LIMIT 30"));
$min = $max = $list[0]['read'];
foreach($list as $k=>$v){
	$max = max($max, $v['read']);
	$min = min($min, $v['read']);
}

foreach($list as $k=>$v){
	$tagcloud .= "<a href=\"/{$arg['modpath']}:cat/{$v['id']}\" rel=\"tag\" style=\"font-size: ". (4 + (int)(20 / $max * ($v['read']+0.001))). "px;\">". $v['name']. "</a>";
}// $tagcloud = iconv('utf-8', 'cp1251', $tagcloud);

?>
<script type="text/javascript" src="/include/swf/swfobject.js"></script>
<div id="tags" style="width:220px; height:220px; border:0px solid red;">
	<script type="text/javascript">
		var rnumber = Math.floor(Math.random()*9999999);
		var widget_so = new SWFObject("/include/swf/tagcloud.swf?r="+rnumber, "tagcloudflash", "220", "220", "9", "#ffffff");
		widget_so.addParam("allowScriptAccess", "always");
		widget_so.addVariable("tcolor", "0x333333");
		widget_so.addVariable("tspeed", "115");
		widget_so.addVariable("distr", "true");
		widget_so.addVariable("mode", "tags");
		widget_so.addVariable("tagcloud", "<tag><?=urlencode($tagcloud)?></tag>");
		widget_so.write("tags");
	</script>
</div>