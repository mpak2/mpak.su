<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("#data_index").iframePostForm({
			complete:function(data){
				if(isNaN(data)){ alert(data) }else{
					
				}
			}
		})
	})
</script>
<form id="data_index" action="/data:ajax/class:index" enctype="multipart/form-data">
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