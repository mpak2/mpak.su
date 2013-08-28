<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("#data").iframePostForm({
			complete:function(data){
				
			}
		})
	})
</script>
<form id="data" action="/data:ajax/class:index">
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