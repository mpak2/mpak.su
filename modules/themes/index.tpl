<? if($themes_index = rb("{$conf['db']['prefix']}themes_index", "id", $_GET['id'])): ?>
	<? mpre($themes_index) ?>
	<? exit(header("Location: http://{$themes_index['name']}")) ?>
<? endif; ?>
