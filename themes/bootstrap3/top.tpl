<ul class="nav">
	<? foreach(rb("{$conf['db']['prefix']}menu_index", "region_id", "index_id", "id", $param['menu'], 0) as $index): ?>
		<li>
			<a href="<?=$index['href']?>" title="<?=$index['description']?>">
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
