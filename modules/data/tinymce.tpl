<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<!-- [settings:foto_lightbox] -->
<script>
	$(function(){
		$("#data_index").iframePostForm({
			complete:function(data){
				if(isNaN(data)){ alert(data) }else{
					$(".data_index").trigger("img", data);
				}
			}
		});
		$(".data_index").on("img", function(event, index_id){
			var img = $("<img>").attr("src", "/<?=$arg['modname']?>:img/"+index_id+"/tn:index/fn:img/w:70/h:70/null/img.png");
			var a = $("<a>").attr("target", "blank").attr("href", "/<?=$arg['modname']?>:img/"+index_id+"/tn:index/fn:img/w:800/h:600/null/img.png").append(img)/*.lightBox()*/;
			var del = $("<a>").addClass("del").attr("href", "javascript:");
			var span = $("<span>").attr("index_id", index_id).append(a).append(del);
			$(".data_index").append(span);
		}).on("click", "a.del", function(){
			if(confirm("Удалить изображение?")){
				var index_id = $(this).parents("[index_id]").attr("index_id");
				console.log("index_id", index_id);
				$.post("/data:ajax/class:index", {id:-index_id}, $.proxy(function(data){
					if(isNaN(data)){ alert(data) }else{
						$(this).parents("[index_id]").remove();
					}
				}, this));
			}
		});
/*		$.each(<?=json_encode($tpl['index'])?>, function(){
			$(".data_index").trigger("img", this.id);
		});*/
	})
</script>
<style>
	.data_index > span {display:inline-block; position:relative; min-height:80px; min-width:80px; vertical-align:bottom; text-align:right;}
	.data_index > span .del {position:absolute; top:5px; right:5px; width:13px; height:13px; background-image:url(/img/del.png); background-color:white;}
</style>
<div class="data_index" style="overflow:hidden;">
	<? foreach(rb("index") as $index): ?>
		<? if($index['img']): ?>
			<div style="width:70px; height:70px; float:left; padding:4px; margin:2px; border:1px solid gray; text-align:center;">
				<img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:60/h:60/null/img.png" style="border:1px solid #aaa; padding:2px">
			</div>
		<? endif; ?>
	<? endforeach; ?>
</div>
<form id="data_index" action="/data:ajax/class:index" enctype="multipart/form-data" method="post" style="margin-top:20px;">
	<span>Файл:</span>
	<span><input type="file" name="img"></span>
	<span><input type="submit" value="Добавить"></span>
</form>
