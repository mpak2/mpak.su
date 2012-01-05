<? die; # Видео

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$sql =  "SELECT * FROM {$conf['db']['prefix']}video_files LIMIT 5";
$video = mpql(mpqw($sql));
//echo "<pre>"; print_r($video); echo "</pre>";
foreach($video as $k=>$v){
echo <<<EOF
	<div class="video_img"><img src="/video:img/{$v['id']}/w:70/h:70/null/img.jpg"></div>
	<div class="video_description"><a href="/video/{$v['id']}">{$v['description']}</a></div>
EOF;
}

?>