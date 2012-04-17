<div style="margin:10px;">
	<script>
		$(function(){
			$(".admin_float").load(function(){
				alert(123);
			});
		});
	</script>
	<div class="admin_float" modpath="<?=$arg['modpath']?>" fn="<?=$arg['fn']?>"></div>
</div>