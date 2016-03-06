<div>

	<? inc("modules/{$arg['modpath']}/init.php") ?>
	<? qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_type` SET `id`=1, `name`='text/html' ON DUPLICATE KEY UPDATE `name`='text/html'") ?>
	<? qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_location_status` SET `id`=301, `name`='301', `description`='Moved' ON DUPLICATE KEY UPDATE `name`='301', `description`='Moved'") ?>
	
	<? foreach(rb("redirect") as $redirect_back): ?>
		<? if($redirect_type = rb("redirect_type", "id", $redirect_back['redirect_type_id'])): ?>
			<? if($location = fk("location", $w = array("name"=>$redirect_back['to']), $w)): ?>
				<? if($index = fk("index", $w = array("name"=>$redirect_back['from'], "location_id"=>$location['id']), $w += array("hide"=>($redirect_back['themes_index'] ? 1 : 0), "cat_id"=>$redirect_back['cat_id'], "index_type_id"=>$redirect_back['redirect_type_id']), $w)): ?>
					<? mpre($index_themes = fk("index_themes", $w = array("index_id"=>$index['id'], "themes_index"=>$redirect_back['themes_index']), $w += array("title"=>$redirect_back['title'], "description"=>$redirect_back['description'], "keywords"=>$redirect_back['keywords']), $w)); ?>
				<? endif; ?>
			<? endif; ?>
		<? elseif($redirect_status = rb("redirect_status", "id", $redirect_back['redirect_status_id'])): ?>
			<? if($index = fk("index", $w = array("name"=>$redirect_back['from']), $w += array("hide"=>($redirect_back['themes_index'] ? 1 : 0)), $w)): ?>
				<? if($location = fk("location", $w = array("name"=>$redirect_back['to'], "index_id"=>$index['id']), $w += array("location_status_id"=>$redirect_status['id']), $w)): ?>
					<? mpre($location_themes = fk("location_themes", $w = array("location_id"=>$location['id'], "themes_index"=>$redirect_back['themes_index']), $w, $w)); ?>
				<? endif; ?>
			<? endif; ?>
		<? endif; ?>
	<? endforeach; ?>
</div>
