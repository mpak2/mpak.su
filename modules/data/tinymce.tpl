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
			console.log(index_id);
			div = $("<img>").attr("src", "/<?=$arg['modname']?>:img/"+index_id+"/tn:index/fn:img/w:80/h:80/null/img.png")
				.wrap("<div>");
			console.log($(div).html());
		})
	})
</script>
<div class="data_index">
</div>
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