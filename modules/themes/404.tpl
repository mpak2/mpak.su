<div class="error" style="text-align:center;">
	<? if(call_user_func(function($tpl) use($conf, $arg){ # Вывод элементов для создания новой страницы с текущим адресом ?>
			<? if(get($conf, 'modules', first(explode(":", get($tpl, 'link'))), 'admin_access') < 4): mpre("Прав доступа не достаточно для создания страницы") ?>
			<? elseif('/phpinfo.php' == $_SERVER['REQUEST_URI']): phpinfo(); ?>
			<? else: ?>
				<span style="float:right;">
					<script>
						(function($, script){
							$(script).parent().one("init", function(e){
								setTimeout(function(){
									$(".error a.newpage").on("click", function(){
										$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/null", {uri:"<?=$_SERVER['REQUEST_URI']?>"}, function(data){
											document.location.reload(true);
										}, "json").fail(function(error){ alert(error.responseText); })
									});
								}, 100)
							}).trigger("init")
						})(jQuery, document.scripts[document.scripts.length-1])
					</script>
					<a class="newpage" href="javascript:">Создать страницу</a>
				</span>
			<? endif; ?>
		<? }, $tpl)): mpre("ОШИБКА вывода доп элементов для быстрого создания страниц") ?>
	<? elseif(!$gt = mpgt($_SERVER['REQUEST_URI'])): mpre("ОШИБКА получения ГЕТ параметров адреса") ?>
	<? elseif(!$m = get($gt, 'm')): pre("ОШИБКА получения компонентов адреса") ?>
	<? elseif(!$mod = first(array_keys($m))): pre("ОШИБКА получения модуля из адреса") ?>
	<? elseif(!$fn = (first(array_values($m)) ?: "index")): pre("ОШИБКА получения страницы из адреса") ?>
	<? else: mpre("Данный адрес `{$_SERVER['REQUEST_URI']}`", "Раздел `{$mod}` Файл `{$fn}`", "Путь от корня сайта до php файла <br /><b>modules/{$mod}/{$fn}.php</b>", "Путь от корня сайта до файла шаблона <br /><b>modules/{$mod}/{$fn}.tpl</b>", "Входящие GET параметры страницы", array_diff_key($_GET, array_flip(['m']))) ?>
		<div style="margin-top:10%;">ошибка <span style="font-size:404%;">404</span></div>
		<div style="margin-top:3%;">Страница, которую вы пытаетесь посмотреть тут нет. Попробуйте <a href="/">начать с начала</a>.</div>
		<img src="/<?=$arg['modpath']?>:img/w:600/h:600/null/404.png">
	<? endif; ?>
</div>
