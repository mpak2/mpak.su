<h1>Распознавание текста</h1>
<div id="ocr">
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$("#ocr form").iframePostForm({
				complete:function(data){
					if(isNaN(data)){ alert(data); }else{
						$("#ocr img").attr("src", "/ocr:img/"+data+"/tn:index/w:600/h:500/null/img.jpg");
					}
				}
			});
		});
	</script>
	<form method="post" enctype="multipart/form-data" action="/ocr/null">
		<input type="file" name="img">
		<input type="submit">
	</form>
	<div>
		<h2>Распознанный текст</h2>
		<div class="text"></div>
		<h2>Первоначальное изобравжение</h2>
		<img src="#" style="border:1px solid gray;">
	</div>
</div>