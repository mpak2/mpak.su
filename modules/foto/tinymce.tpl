<script>
	$(function(){
		$("#foto_list a").click(function(){
			var ta = $('#qwerty'),
				p = ta[0].selectionStart,
				text = ta.val();
			if(p != undefined)
				ta.val(text.slice(0, p) + $(this).val() + text.slice(p));
			else{
				ta.trigger('focus');
				range = document.selection.createRange();
				range.text = $(this).val();
			}
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