<?

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	$request_uri = array_pop(explode($_SERVER['HTTP_HOST'], $_SERVER['HTTP_REFERER']));
	if($conf['modules']['seo'] && ($seo = ql("SELECT * FROM {$conf['db']['prefix']}seo_redirect WHERE `from`=\"". mpquot($request_uri). "\"", 0))){
		$request_uri = $seo['to'];
	} /*mpre($request_uri);*/ $get = mpgt($request_uri == "/" ? $conf['settings']['start_mod'] : $request_uri);
}else{
	$get = mpgt($_SERVER['REQUEST_URI']);
}
list($modname, $filename) = each($get['m']);
$filename = ($filename ?: "index");
$tpl = file_exists($tf = mpopendir("/modules/". basename($conf['modules'][$modname]['folder']). "/{$filename}.tpl")) ? md5_file($tf) : "";
$php = file_exists($pf = mpopendir("/modules/". basename($conf['modules'][$modname]['folder']). "/{$filename}.php")) ? md5_file($pf) : "";
$md5_file = md5($tpl. $php);

if($_POST['css']){
	foreach($_POST['css'] as $css){
		if(!$css) continue;
		if(count($uri = explode($_SERVER['HTTP_HOST'], $css)) <= 1) continue; # Если в адресе нет имени сайта
		if(array_key_exists("null", $get = mpgt($uri[1]))){
			$md5 = file_exists($f = mpopendir("/themes/{$get['theme']}/{$get['']}")) ? md5_file($f) : "";
		}else{
			$md5 = file_exists($f = mpopendir(substr($uri[1], 1))) ? md5_file($f) : "";
		} $md5_file = md5($md5_file. $md5);
	}
} if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	exit($md5_file != $_POST['md5_file'] ? $md5_file : "");
}

?>
<script>
	$(function(){
		var count = 0;
		var md5_file = "";
		var css = [];
		$.each(document.styleSheets, function(){
			css.push(this.href);
		});
		setInterval(function(){
			$.post("/blocks/<?=$arg['blocknum']?>/null", {md5_file:md5_file, css:css}, function(data){
				if(isNaN(data)){
					if(md5_file == ""){
/*						css.forEach(function(key, val){
							console.log("css:", key);
						});*/
						console.log("Обновление mp5_file:", md5_file = data);
					}else{
						$("<div>").css({position:"fixed", "z-index":9999, padding:"15px", "background-color":"red", color:"white", left:"10px", top:"10px", "border-radius":"12px"}).text("Обновление страницы").appendTo("body");
						console.log("Перезагрузка mp5_file:", data);
						if(data.match(/^[\w\d]{32}$/)){ // Если формат возвращенных данных аяком равно корректному хешу md5
							document.location.reload(true);
						}
					}
				}else{ /*console.log("Ничего: ", data) Файлы не изменились ничего не делаем */ }
			});
		}, 2000);
	})
</script>
