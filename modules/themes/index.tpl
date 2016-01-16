<? if(get($_GET, 'id') && ($themes_index = rb("{$conf['db']['prefix']}themes_index", "id", $_GET['id']))): ?>
	<? exit(header("Location: http://{$themes_index['name']}")) ?>
<? endif; ?>
