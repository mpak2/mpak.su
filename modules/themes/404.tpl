<div class="error" style="text-align:center;">
	<? if($conf['modules'][ first(explode(":", $tpl['link'])) ]['access'] >= 4): ?>
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
	<div style="margin-top:10%;">ошибка <span style="font-size:404%;">404</span></div>
	<div style="margin-top:3%;">Страница, которую вы пытаетесь посмотреть тут нет. Попробуйте <a href="/">начать с начала</a>.</div>
	<img src="/<?=$arg['modpath']?>:img/null/404.png">
</div>
