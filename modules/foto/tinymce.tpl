<script>
	$(function(){
		var cat = <?=json_encode($tpl['img'])?>;
		var raw = tinyMCEPopup.editor.selection.getContent({format:'raw'});
		var text = tinyMCE.activeEditor.getContent();

		$("#foto_list span a").click(function(){
			cat_id = $(this).parents("[cat_id]").attr("cat_id");// alert(cat_id);
			if($(text).find("div#gallery").length > 0){
				alert(true);
			}else{
				gallery = $("<div>");
				$.each(cat[cat_id], function(key, val){
					img = $("<img>").attr("src", "/foto:img/"+val.id+"/w:50/h:50/null/img.jpg");
					a = $("<a>").attr("href", "/foto:img/"+val.id+"/w:600/h:500/null/img.jpg").css("padding", "3px").html(img);
					$(gallery).append(a);
				});
				tinyMCEPopup.editor.selection.setContent($(gallery).appendTo("<div>").attr("id", "gallery").parent().html());
			}
		});
	});
</script>
<ul id="foto_list" style="list-style-type:none;">
	<? foreach($tpl['cat'] as $k=>$v): ?>
		<li cat_id="<?=$v['id']?>">
			<span style="float:right;">
				<a href="javascript:return false;">Разместить</a>
			</span>
			<?=$v['name']?> <?=$v['cnt']?> фото
		</li>
	<? endforeach; ?>
</ul>