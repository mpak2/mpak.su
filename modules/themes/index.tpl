<? if(!get($_GET, 'id')):// mpre("Идентификатор не задан") ?>
<? elseif($themes_index = rb("{$conf['db']['prefix']}themes_index", "id", $_GET['id'])): ?>
	<? exit(header("Location: http://{$themes_index['name']}")) ?>
<? elseif(!array_key_exists("null", $_GET)): ?>
	<? if($conf['user']['uid'] <= 0): ?>
		<? exit(header("Location: /users:login")); ?>
	<? else: ?>
		<? if(mpsettings("themes_index_cat")): ?>
			<? foreach(rb("index_cat") as $index_cat): ?>
				<div>
					<h2><?=$index_cat['name']?></h2>
					<ul style="padding:0;">
						<? foreach(rb("index", "index_cat_id", "id", $index_cat['id']) as $index): ?>
							<li style="display:inline-block; margin-left:20px;"><a target="blank" href="http://<?=$index['name']?>"><?=$index['name']?></a></li>
						<? endforeach; ?>
					</ul>
				</div>
			<? endforeach; ?>
		<? else: ?>
			<ul style="padding:0;">
				<? foreach(rb("index") as $index): ?>
					<li style="display:inline-block; margin-left:20px;"><a target="blank" href="http://<?=$index['name']?>"><?=$index['name']?></a></li>
				<? endforeach; ?>
			</ul>
		<? endif; ?>
	<? endif; ?>
<? endif; ?>
