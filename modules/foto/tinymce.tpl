<script>
	$(function(){
		$("#foto_list a").click(function(){
			alert(123);
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