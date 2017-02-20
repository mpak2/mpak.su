<? if(!$menu_region = rb("menu-region", "name", $w = "[Админменю]")): mpre("Меню не найдено {$w}") ?>
<? elseif(!$MENU_INDEX = rb("index", "region_id", "id", $menu_region['id'])): mpre("Пункты меню <a href='/menu:admin/r:menu-region?&where[id]={$menu_region['id']}'>не найдены</a>"); ?>
<? else:// mpre($menu_region) ?>
	<ul class="menu">
		<style>
			ul.menu > li ul {display:none;}
			ul.menu > li:hover ul {display:block;}
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
		<? foreach(rb($MENU_INDEX, "index_id", "id", "[0,NULL]") as $menu_index): # Пункты меню с нулевым родительским элементом ?>
			<li>
				<a class="nav__link" href="<?=$menu_index['href']?>" title="<?=$menu_index['description']?>">
					<?=$menu_index['name']?>
				</a>
				<? if($INDEX = rb($MENU_INDEX, "index_id", "id", $menu_index['id'])): # Получение списка меню родитель которого равен выбранному выше пункту  ?>
					<ul>
						<? foreach($INDEX as $index): # Перебор всех выбранных вложенных элементов ?>
							<li><a href="<?=$index['href']?>" title="<?=$index['description']?>"><?=$index['name']?></a></li>
						<? endforeach; ?>
					</ul>
				<? endif; ?>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>