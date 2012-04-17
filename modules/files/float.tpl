<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("form.files_float").iframePostForm({
			complete:function(data){
				alert(data);
			}
		});
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
<form class="files_float" method="post" action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/null" enctype="multipart/form-data">
	<div class="files_float" style="width:400px;">
		<div class="file_<?=$arg['blocknum']?>">
			<div style="overflow:hidden;">
				<input type="file" name="img[]">
				<span style="display:inner-block; background-color:#aaa; padding:3px 25px; border-radius:3px; cursor:pointer; color:white; font-weight:bold;">+</span>
			</div>
		</div>
		<div style="margin:5px;"><input type="submit" value="Загрузить"></div>
	</div>
</form>