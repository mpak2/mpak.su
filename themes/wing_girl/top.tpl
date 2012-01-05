<script type="text/javascript">
 	$(document).ready(function(){
 		$('ul li a[href$='+$.url.attr('path')+']').parent().addClass('current_page_item');
 	});
</script>
<ul>
	<? foreach($menu as $k=>$v): ?>
		<li class="page_item page-item-2"><a href="<?=$v['link']?>" title="<?=$v['name']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
