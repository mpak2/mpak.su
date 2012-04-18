<div style="margin:10px;" modpath="<?=$arg['modpath']?>" fn="<?=$arg['fn']?>">
	<script>
		$(function(){
			attrs = $(".admin_float").parent();
		});
		text = $("<div>text</div>").css("background-color", "yellow").clone();
		alert(text);
	</script>
	<div class="admin_float" modpath="<?=$arg['modpath']?>" fn="<?=$arg['fn']?>"></div>
</div>