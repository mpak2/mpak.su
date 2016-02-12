<? if(get($_GET, 'id') && ($themes_index = rb("{$conf['db']['prefix']}themes_index", "id", $_GET['id']))): ?>
	<? exit(header("Location: http://{$themes_index['name']}")) ?>
<? elseif(!array_key_exists("null", $_GET)): ?>
	<? if($conf['user']['uid'] <= 0): ?>
		<? exit(header("Location: /users:login")); ?>
	<? else: ?>
		<ul>
			<? foreach(rb("index") as $index): ?>
				<li style="float:left; margin-left:20px;"><a target="blank" href="http://<?=$index['name']?>"><?=$index['name']?></a></li>
			<? endforeach; ?>
		</ul>
	<? endif; ?>
<? endif; ?>
