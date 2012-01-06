<script>
	$(function(){
		$(".<?=$arg['modpath']?>_fav").click(function(){
			fav = $(this).attr("fav") > 0 ? 0 : 1;
			src = $(this).find("img").attr("fav_"+fav);// alert(src);
			$(this).find("img").attr("src", src);
			$(this).attr("fav", fav).find("a").html(fav ? "Удалить из избранного" : "Добавить в избранное");
			$.post("/<?=$arg['modpath']?>:<?=$arg['fe']?>/null", {fav:fav}, function(data){
				if(isNaN(data)){ alert(data); }
			});
		});
	});
</script>
<div fav="<?=(int)$conf['tpl']['index']['fav']?>" class="<?=$arg['modpath']?>_fav">
	<img fav_1="/<?=$arg['modname']?>:img/null/img/favorites_delete.png" fav_0="/<?=$arg['modname']?>:img/null/img/favorites_add.png" src="/<?=$arg['modname']?>:img/null/img/favorites_<?=($conf['tpl']['index']['fav'] ? "delete" : "add")?>.png">
	<a href="javascript: return false;">
		<?=($conf['tpl']['index']['fav'] ? "Удалить из избранного" : "Добавить в избранное")?>
	</a>
</div>