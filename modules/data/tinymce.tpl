<script src="/include/jquery/jquery.iframe-post-form.js"></script>
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
			var img = $("<img>").attr("src", "/<?=$arg['modname']?>:img/"+index_id+"/tn:index/fn:img/w:80/h:80/null/img.png");
			var del = $("<a>").addClass("del").attr("href", "javascript:");
			var span = $("<span>").append(img).append(del);
			$(".data_index").append(span);
		});
		$.each(<?=json_encode($tpl['index'])?>, function(){
			$(".data_index").trigger("img", this.id);
		});
	})
</script>
<style>
	.data_index > span {display:inline-block; position:relative; min-height:80px; min-width:80px;}
	.data_index > span .del {position:absolute; top:5px; right:5px; width:13px; height:13px; background-image:url(/img/del.png); background-color:white;}
</style>
<div class="data_index"></div>
<form id="data_index" action="/data:ajax/class:index" enctype="multipart/form-data" method="post">
	<div class="table">
		<div>
			<span>Файл:</span>
			<span><input type="file" name="img"></span>
		</div>
		<div style="text-align:right;">
			<span>&nbsp;</span>
			<span><input type="submit" value="Добавить"></span>
		</div>
	</div>
</form>