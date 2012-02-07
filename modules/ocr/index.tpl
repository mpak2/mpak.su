<h1>Распознавание текста</h1>
<div id="ocr">
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$("#ocr form").iframePostForm({
				complete:function(data){
					alert(data);
				}
			});
		});
	</script>
	<form method="post" enctype="multipart/form-data" action="/ocr/null">
		<input type="file" name="img">
		<input type="submit">
	</form>
</div>