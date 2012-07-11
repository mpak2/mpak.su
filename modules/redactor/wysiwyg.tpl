<script src="/include/jquery/redactor/js/redactor/redactor.js"></script>
<link rel="stylesheet" href="/include/jquery/redactor/js/redactor/css/redactor.css" />
<script>
	$(function(){
		$(".redactor_content[name='<?=$conf['settings']['redactor_name']?>']").redactor({
			imageUpload:"/redactor:upload/null",
			imageGetJson: "/redactor:images/null",
//			autoresize:true,
//			resize:true,
//			fullscreen:true,
		});
	});
</script>
<form>
	<textarea class="redactor_content" name="<?=$conf['settings']['redactor_name']?>" style="height:300px;">
		<?=$conf['settings']['redactor_text']?>
	</textarea>
</form>