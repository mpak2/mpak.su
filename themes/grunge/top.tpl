<script>
	$(document).ready(function (){// class="current_page_item"
		$('.navigation ul li a[href$='+$.url.attr('path')+']').parent().addClass('current_page_item');
	});
</script>
<div class="navigation">
	<ul>
		<? foreach($menu as $k=>$v): ?>
			<li><a href="<?=$v['link']?>"><?=$v['name']?></a></li>
		<? endforeach; ?>
	</ul>
	<div class="clearer">&nbsp;</div>
</div>
