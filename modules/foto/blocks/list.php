<? die; # СписокФото

if ($_GET['conf']){
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
} 
foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}foto_img ORDER BY id DESC LIMIT 6")) as $k=>$v){
echo <<<EOF
	<div class="foto_img"><img src="/foto:img/{$v['id']}/w:70/h:70/img.jpg"></div>
	<div class="foto_description"><a href="/foto">{$v['description']}</a></div>
EOF;
}

?>