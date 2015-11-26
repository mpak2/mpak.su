<ul class="nav">
	<script>
		(function($, script){
			$(script).parent().one("init", function(e){
				setTimeout(function(){
					$(e.delegateTarget).find("a[href='"+decodeURIComponent(location.pathname)+"']").addClass("active");
				}, 100)
			}).trigger("init")
		})(jQuery, document.scripts[document.scripts.length-1])
	</script>
	<? foreach(rb("{$conf['db']['prefix']}menu_index", "region_id", "id", $param['menu']) as $index): ?>
		<li class="nav__item">
			<a class="nav__link" href="<?=$index['href']?>"><?=$index['name']?></a>
		</li>
	<? endforeach; ?>
</ul>
