<?

if($yandex = rb("yandex", "id", get($_GET, 'id'))){
	if($yandex_token = rb("yandex_token", "id", $yandex["yandex_token_id"])){
		if(array_key_exists("null", $_GET)){
			if($index = rb("index", "id", $_REQUEST['index_id'])){ sleep(1); # Обновления данных
				if("tops" == $_REQUEST['api']){
					if($yandex_webmaster = rb("yandex_webmaster", "index_id", $index['id'])){
						if($tops = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts/'. last(explode('/', $yandex_webmaster['href'])). '/tops/', false, stream_context_create(array('http' => array('method'  => 'GET','header'  => "Authorization: OAuth ". $yandex_token['name'],))))){
							if($xml = json_decode(json_encode(new SimpleXMLElement($tops)), true)){
								if($top = get($xml, 'top-queries', 'top-shows', 'top-info')){
									foreach($top as $info){// mpre($info);
										if($yandex_tops = fk("yandex_tops", $w = array("name"=>$info['query']), $w)){
											$yandex_tops_index = fk("yandex_tops_index", $w = array("index_id"=>$index['id'], "yandex_tops_id"=>$yandex_tops['id']), $w += ($info + array('view'=>$info['count'])), $w += array('up'=>time()));
										}
									}
								} if($top = get($xml, 'top-queries', 'top-clicks', 'top-info')){
									foreach($top as $info){// mpre($info);
										if($name = get($info, 'query')){
											if($yandex_tops = fk("yandex_tops", $w = array("name"=>$name), $w)){
												$yandex_tops_index = fk("yandex_tops_index", $w = array("index_id"=>$index['id'], "yandex_tops_id"=>$yandex_tops['id']), $w += ($info + array('clicks'=>$info['count'])), $w += array('up'=>time()));
											}
										}else{ /*mpre($info);*/ }
									}
								}
							}
						}else{ mpre("Ошибка загрузки данных"); }
					} exit(json_encode($index));
				}elseif("stats" == $_REQUEST['api']){
					if($yandex_webmaster = rb("yandex_webmaster", "index_id", $index['id'])){
						if($stats = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts/'. last(explode('/', $yandex_webmaster['href'])). '/stats/', false, stream_context_create(array('http' => array('method'  => 'GET','header'  => "Authorization: OAuth ". $yandex_token['name'],))))){
							if($xml = json_decode(json_encode(new SimpleXMLElement($stats)), true)){
								$yandex_webmaster = fk("yandex_webmaster", array("id"=>$yandex_webmaster['id']), $w = array_filter(array_intersect_key($xml, $yandex_webmaster), function($v){ return !is_array($v); }), $w += array("id"=>$xml['@attributes']['id']));
							}
						}
					} exit(json_encode($index));
				}elseif("webmaster" == $_REQUEST['api']){ # Регистрация в вебмастере
					$tpl['webmaster'] = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts', false, stream_context_create(array('http' =>
						($param = array(
							'method'  => 'POST',
							'content' => ($content = "<host><name>{$index['name']}</name></host>"),
							'header'  => array(
								"Authorization: OAuth ". $yandex_token['name'],
								"Content-Type: application/x-www-form-urlencoded",
								"Content-Length: ". strlen($content),
							),
						))
					)));// mpre($tpl['data'], $url, $param);
				}elseif("metrika" == $_REQUEST['api']){ # Регистрация в метрике
					$tpl['metrika'] = file_get_contents($url = "https://api-metrika.yandex.ru/counters", false, stream_context_create(array('http' =>
						($param = array(
							'method'  => 'POST',
							'content' => ($content = json_encode(array(
								'counter'=>array(
									'name'=>$index['name'],
									'site'=>$index['name'],
								),
							))),
							'header'  => array(
								"Authorization: OAuth ". $yandex_token['name'],
								"Content-Type: application/json",
//								"Content-Type: application/json",
								"Content-Length: ". strlen($content),
							),
						))
					)));// mpre($tpl['metrika'], $url, $param);
				}else{ mpre($index); }
			} exit(0);
		}else{
			if($metrika = file_get_contents($url = 'https://api-metrika.yandex.ru/counters.json', false, stream_context_create(array('http'=>array( 'method'=>'GET', "Content-Type: application/json", 'header'=>"Authorization: OAuth {$yandex_token['name']}", ))))){
				if($json = json_decode($metrika, true)){
					foreach($json['counters'] as $counter){
						if($index = rb("index", "name", "[{$counter['site']}]")){
							if($yandex_metrika = fk("yandex_metrika", array("id"=>$counter['id']), $counter += array("index_id"=>$index['id']), $counter)){
								$yandex_metrika_index = fk("yandex_metrika_index", $w = array("index_id"=>$index['id'], "yandex_metrika_id"=>$yandex_metrika['id']), $w);
							}
						}
					}
				}
			}else{ mpre("Ошибка загрузки данных"); }
		
		
			$tpl['webmaster'] = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts', false, stream_context_create(array('http' =>
				($param = array( 'method'  => 'GET', 'header'  => "Authorization: OAuth {$yandex_token['name']}", ))
			)));// mpre($tpl['data'], $url, $param);

			if($webmaster = get($tpl, 'webmaster')){
				if($xml = json_decode(json_encode(new SimpleXMLElement($webmaster)), true)){
					foreach($xml['host'] as $host){// mpre($host);
						if($index = rb("index", "name", "[{$host['name']}]")){
							$yandex_webmaster = fk("yandex_webmaster", $w = array("href"=>$host['@attributes']['href']), $w += array('verification'=>get($host, 'verification', '@attributes', 'state'), 'crawling'=>get($host, 'crawling', '@attributes', 'state'), "index_id"=>$index['id'])+$host, $w);
						}
					}
				}
			}else{ mpre("Ошибка загрузки данных"); }
		}
	}else{ mpre("Не назначен токен"); }
}

if(mpsettings($t = "{$arg['modpath']}_yandex", "Яндекс") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `{$table}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`yandex_token_id` int(11) NOT NULL,
		`secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		PRIMARY KEY (`id`),
		KEY `yandex_token_id` (`yandex_token_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "{$arg['modpath']}_yandex_metrika", "Яндекс") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `{$table}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`owner_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Владелец сайта',
		`index_id` int(11) NOT NULL,
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`site` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`code_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		PRIMARY KEY (`id`),
		KEY `index_id` (`index_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "{$arg['modpath']}_yandex_token", "Токен") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `{$table}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`yandex_id` int(11) NOT NULL,
		`expires_in` int(11) NOT NULL COMMENT 'время жизни токена в секундах',
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'OAuth-токен',
		PRIMARY KEY (`id`),
		KEY `yandex_id` (`yandex_id`),
		KEY `uid` (`uid`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "{$arg['modpath']}_yandex_tops", "Запросы") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `{$table}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`up` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `name` (`name`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "{$arg['modpath']}_yandex_webmaster", "Вебмастер") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `{$table}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`index_id` int(11) NOT NULL,
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`href` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`tcy` int(11) NOT NULL COMMENT 'Индекс цирирования яндекс',
		`url-count` int(11) NOT NULL COMMENT 'Проиндексированных страниц',
		`index-count` int(11) NOT NULL COMMENT 'Страниц в поиске',
		`url-errors` int(11) NOT NULL COMMENT 'Ошибочные ссылки',
		`internal-links-count` int(11) NOT NULL COMMENT 'Внешние ссылки',
		`verification` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Проверка',
		`crawling` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Индексирование',
		PRIMARY KEY (`id`),
		KEY `url-count` (`url-count`),
		KEY `index-count` (`index-count`),
		KEY `url-errors` (`url-errors`),
		KEY `internal-links-count` (`internal-links-count`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "{$arg['modpath']}_yandex_tops_index", "Индексация") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `mp_themes_yandex_tops_index` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`up` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`position` int(11) NOT NULL COMMENT 'Позиция в поисковой выдаче',
		`view` int(11) NOT NULL COMMENT 'Количество просмотров',
		`clicks` int(11) NOT NULL COMMENT 'Количество кликов',
		`index_id` int(11) NOT NULL,
		`yandex_tops_id` int(11) NOT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `index_id_2` (`index_id`,`yandex_tops_id`),
		KEY `index_id` (`index_id`),
		KEY `yandex_tops_id` (`yandex_tops_id`),
		KEY `up` (`up`),
		KEY `position` (`position`),
		KEY `view` (`view`),
		KEY `clicks` (`clicks`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "{$arg['modpath']}_yandex_metrika_index", "Счетчики") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `{$table}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`index_id` int(11) NOT NULL,
		`yandex_metrika_id` int(11) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
}
