<script>
/*	$(function(){
		$("#backup").click(function(){
			$.post("/<?=$arg['modname']?>:<?=$arg['fe']?>", {backup:<?=time()?>}, function(data){
				document.location.href = "/<?=$arg['modname']?>:<?=$arg['fe']?>/backup:". <?=time()?>;
			});
		});
	});*/
</script>
<div style="margin:10px;">
	<h1>Резервные копии</h1>
	<div id="backup" style="margin-top:10px;">
		Резервную копию базы данных:
		<a href="/<?=$arg['modname']?>:<?=$arg['fe']?>/backup:<?=time()?>/null">
			Создать
		</a>
	</div>
	<div>
	</div>
</div>