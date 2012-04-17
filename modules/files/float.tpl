<script>
	$(function(){
		$(".file_<?=$arg['blocknum']?> > div > span").live("click", function(){
			if($(this).html() == '+'){
				el = $(this).parents(".file_<?=$arg['blocknum']?>").find("div:first").html();
				$("<div>").html(el).appendTo(".file_<?=$arg['blocknum']?>");
				$(".file_<?=$arg['blocknum']?> > div").not(":first").find("span").html("-");
			}else{
				$(this).parent().remove();
			}
		});
	});
</script>
<div class="files_float">
	<div class="file_<?=$arg['blocknum']?>" style="float:left; width:400px;">
		<div style="overflow:hidden;">
			<input type="file" name="doc[]">
			<span style="display:inner-block; background-color:#aaa; padding:3px 25px; border-radius:3px; cursor:pointer; color:white; font-weight:bold;">+</span>
		</div>
	</div>
</div>