	<div>
		<? if(empty($_FILES)): ?>
			<style>
				#upload {overflow:hidden;}
				#upload > div {float:left; margin:3px;}
				#upload div.del {position:absolute; height:25px; width:25px; background-image:url(/img/delete.png); background-repeat:no-repeat; background-position:2px 2px; cursor:pointer;}
			</style>
			<script src="/include/jquery/jquery.iframe-post-form.js"></script>
			<script>
				$(function(){
					$("form#img").iframePostForm({
						complete:function(data){
							if(html = $("<div>").html(data).find("#upload").html()){
								$("#upload").append(html);
								$("form#img").find("input[type=file]").val("");
							}else{
								alert(data);
							}
						}
					});
				});
				$("#upload .del").live("click", function(){
					img_id = $(this).parents("[img_id]").attr("img_id");// alert(img_id);
					$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/tn:<?=$_POST['tn']?>/null", {del:img_id}, function(data){
						if(isNaN(data)){ alert(data) }else{
							$("#upload div[img_id="+img_id+"]").remove();
						}
					});
				});
			</script>
		<? endif; ?>
		<div id="upload">
			<? foreach($tpl['img'] as $k=>$v): ?>
				<div img_id="<?=$v['id']?>">
					<div class="del"></div>
					<img src="/<?=$tpl['arg'][0]?>:img/<?=$v['id']?>/tn:<?=implode("_", array_slice($tpl['arg'], 1))?>/fn:img/w:60/h:60/c:1/null/img.jpg">
				</div>
			<? endforeach; ?>
		</div>
		<form id="img" method="post" action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/null" enctype="multipart/form-data">
			<input type="hidden" name="tn" value="<?=$_POST['tn']?>">
			<input type="file" name="img[]">
			<input type="submit" value="Загрузить">
		</form>
	</div>