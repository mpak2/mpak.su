<div>
	<? qw("CREATE TABLE IF NOT EXISTS `{$conf['db']['prefix']}{$arg['modpath']}_index` ( `id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) NOT NULL, `uid` int(11) NOT NULL, `hide` smallint(6) NOT NULL, `index_type_id` int(11) NOT NULL, `priority` float NOT NULL DEFAULT '0.8' COMMENT 'Приоритет ссылки в sitemap.xml', `name` varchar(255) NOT NULL, `location_id` int(11) NOT NULL COMMENT 'Врутреняя страница', `cat_id` int(11) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name_2` (`name`), KEY `index_type_id` (`index_type_id`), KEY `uid` (`uid`), KEY `cat_id` (`cat_id`), KEY `name` (`name`), KEY `hide` (`hide`), KEY `location_id` (`location_id`) ) ENGINE=InnoDB DEFAULT CHARSET=cp1251") ?>
	<? qw("CREATE TABLE IF NOT EXISTS `{$conf['db']['prefix']}{$arg['modpath']}_index_type` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `description` text NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251") ?>
	<? qw("CREATE TABLE IF NOT EXISTS `{$conf['db']['prefix']}{$arg['modpath']}_location` ( `id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) NOT NULL, `uid` int(11) NOT NULL, `hide` smallint(6) NOT NULL, `location_status_id` int(11) NOT NULL, `index_id` int(11) NOT NULL, `name` varchar(255) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name_2` (`name`), KEY `location_status_id` (`location_status_id`), KEY `name` (`name`), KEY `uid` (`uid`), KEY `hide` (`hide`) ) ENGINE=InnoDB DEFAULT CHARSET=cp1251") ?>
	<? qw("CREATE TABLE IF NOT EXISTS `{$conf['db']['prefix']}{$arg['modpath']}_location_status` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `description` text NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=cp1251") ?>

	<? qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_type` SET `id`=1, `name`='text/html' ON DUPLICATE KEY UPDATE `name`='text/html'") ?>
	<? qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_location_status` SET `id`=301, `name`='301', `description`='Moved' ON DUPLICATE KEY UPDATE `name`='301', `description`='Moved'") ?>
	
	<? mpsettings("seo_index_type", "Типы") ?>
	<? mpsettings("seo_location", "Переадресация") ?>
	<? mpsettings("seo_location_status", "Статус") ?>
	
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