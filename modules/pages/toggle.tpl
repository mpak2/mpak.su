<script>
	$(function(){
		$(".link").click(function(){
			$(this).parents("[page_id]").find(".txt").slideToggle();
		});
	});
</script>
<div id="pages">
	<? foreach($conf['tpl']['pages'] as $k=>$v): ?>
		<div page_id="<?=$v['id']?>">
			<a class="link" href="javascript: return false;"><h2><?=$v['name']?></h2></a>
			<div><?=$v['description']?></div>
			<div class="txt" style="display:none;"><?=$v['text']?></div>
		</div>
	<? endforeach; ?>
</div>