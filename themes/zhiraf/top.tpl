<ul class="nav">
	<style>
		ul.nav > li ul {display:none;}
		ul.nav > li:hover ul {display:block;}
	</style>
	<script>
		(function($, script){
			$(script).parent().one("init", function(e){
				setTimeout(function(){
					$(e.delegateTarget).find("a[href='"+decodeURIComponent(location.pathname)+"']").addClass("active");
				}, 100)
			}).trigger("init")
		})(jQuery, document.scripts[document.scripts.length-1])
	</script>
	<? foreach(rb("{$conf['db']['prefix']}menu_index", "region_id", "index_id", "id", $param['menu'], 0) as $index): # Пункты меню с нулевым родительским элементом ?>
		<li>
			<a class="nav__link" href="<?=$index['href']?>" title="<?=$index['description']?>">
				<?=$index['name']?>
			</a>
			<? if($INDEX = rb("menu-index", "index_id", "id", $index['id'])): # Получение списка меню родитель которого равен выбранному выше пункту  ?>
				<ul>
					<? foreach($INDEX as $index): # Перебор всех выбранных вложенных элементов ?>
						<li><a href="<?=$index['href']?>" title="<?=$index['description']?>"><?=$index['name']?></a></li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</li>
	<? endforeach; ?>
</ul>
