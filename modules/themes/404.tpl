<div class="error" style="text-align:center;">
	<? if($conf['modules']['pages']['access'] >= 4): ?>
		<span style="float:right;">
			<script>
				$(function(){
					$(".error a.newpage").on("click", function(){
						$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", {uri:"<?=$_SERVER['REQUEST_URI']?>"}, function(data){
							if(isNaN(data)){ alert(data) }else{
								alert("Создана новая страница на сайте");
								document.location.href = "<?=$tpl['location']?>";
							}
						})
					});
				})
			</script>
			<a class="newpage" href="javascript:">Создать страницу</a>
		</span>
	<? endif; ?>
	<div style="margin-top:10%;">ошибка <span style="font-size:404%;">404</span></div>
	<div style="margin-top:3%;">Страница, которую вы пытаетесь посмотреть тут нет. Попробуйте <a href="/">начать с начала</a>.</div>
	<img src="/<?=$arg['modname']?>:img/null/404.png">
</div>