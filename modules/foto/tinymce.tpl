<script>
	$(function(){
		function ins(){
			text = $("iframe#elm1_ifr").html(); alert(text);
		} 

		$("#foto_list a").click(function(){
			alert(tinymce);
		});
	});
</script>
<ul id="foto_list" style="list-style-type:none;">
	<? foreach($tpl['cat'] as $k=>$v): ?>
		<li>
			<span style="float:right;">
				<a href="javascript:return false;">Разместить</a>
			</span>
			<?=$v['name']?>
		</li>
	<? endforeach; ?>
</ul>