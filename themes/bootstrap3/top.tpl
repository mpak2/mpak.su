<ul class="nav navbar-nav"> 
	<? foreach(rb("{$conf['db']['prefix']}menu_index", "region_id", "index_id", "id", $param['menu'], 0) as $index): ?>
		<li>
			<a href="<?=$index['href']?>" title="<?=$index['description']?>">
				<?=$index['name']?>
			</a>		
		</li>
	<? endforeach; ?>
</ul>
<ul class="nav navbar-nav navbar-right"> 
	<? foreach(rb("{$conf['db']['prefix']}menu_index", "region_id", "index_id", "id", $param['menu'], 0) as $index): ?>
		<li>
			<a href="<?=$index['href']?>" title="<?=$index['description']?>">
				<?=$index['name']?>
			</a>		
		</li>
	<? endforeach; ?>
</ul>
