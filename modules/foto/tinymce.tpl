<script>
	$(function(){
		var raw = tinyMCEPopup.editor.selection.getContent({format:'raw'});
		var text = tinyMCE.activeEditor.getContent();

		if($(text).find("div#gallery").length > 0){
			alert(true);
		}else{
			gallery = $("<div>").attr("id", "gallery").text("123");
			tinyMCEPopup.editor.selection.setContent($(gallery).html());
		}
	});
</script>
<ul id="foto_list" style="list-style-type:none;">
	<? foreach($tpl['cat'] as $k=>$v): ?>
		<li>
			<span style="float:right;">
				<a href="javascript:return false;">Разместить</a>
			</span>
			<?=$v['name']?> <?=$v['cnt']?> фото
		</li>
	<? endforeach; ?>
</ul>