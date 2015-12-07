<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<!-- [settings:foto_lightbox] -->
<script>
	$(function(){
		$(".data_index").on("click", "a.del", function(){
			if(confirm("Удалить изображение?")){
				var index_id = $(this).parents("[index_id]").attr("index_id");
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
<form id="data_index" action="/data:ajax/class:index/null" enctype="multipart/form-data" method="post" style="margin-top:20px;">
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script sync>
		(function($, script){
			$(script).parent().one("init", function(e){
				setTimeout(function(){
					$(e.delegateTarget).iframePostForm({
						complete:function(data){
							try{
								if(json = JSON.parse(data)){
									console.log("json:", json);
//									alert("Информация сохранена");
									document.location.reload(true);
								}
							}catch(e){
								if(isNaN(data)){ alert(data) }else{
									console.log("date:", data)
								}
							}
						}
					});
				}, 100)
			}).trigger("init")
		})(jQuery, document.scripts[document.scripts.length-1])
	</script>
	<span>Файл:</span>
	<span><input type="hidden" name="name"></span>
	<span><input type="file" name="img"></span>
	<span><input type="submit" value="Добавить"></span>
</form>
