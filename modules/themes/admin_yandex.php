<?

if(!$_POST){// mpre("Информационная страница");
}elseif(!array_key_exists("null", $_GET)){ mpre("Установлено отображение шаблона");
}elseif(!$yandex = rb("yandex", "id", get($_GET, 'id'))){ mpre("Приложение не найдено");
}elseif(!$yandex_token = rb("yandex_token", "id", $yandex["yandex_token_id"])){ mpre("Токен дефолтного аккаунта не установлен");
}elseif(!$index = rb("index", "id", get($_REQUEST, 'index_id'))){// mpre("Сайт не найден");
}elseif("webmaster" == $_REQUEST['api']){ # Регистрация в вебмастере
	if($data = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts', false, stream_context_create(array('http' =>
		($param = array(
			'method'  => 'POST',
			'content' => ($content = "<host><name>{$index['name']}</name></host>"),
			'header'  => array(
				"Authorization: OAuth ". $yandex_token['name'],
				"Content-Type: application/x-www-form-urlencoded",
				"Content-Length: ". strlen($content),
			),
		))
	)))){
		foreach($http_response_header as $nn=>$headers){
			if(first($h = explode(":", $headers)) == "Location"){
				if($id = last(explode("/", $headers))){
					exit(json_encode($yandex_webmaster = fk("yandex_webmaster", $w = ['href'=>last($h)], $w += ['id'=>$id, 'index_id'=>$index['id'], 'name'=>$index['name'], 'yandex_token_id'=>$yandex_token['id'], 'verification'=>'NEVER_VERIFIED'], $w)));
				}
			}
		} exit($data);
	}else{ exit("Ошибка регистрации вебмастера"); }
}elseif("metrika" == $_REQUEST['api']){ # Регистрация в метрике
	if(!$data = file_get_contents($url = "https://api-metrika.yandex.ru/counters", false, stream_context_create(array('http' =>
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
	)))){ mpre("Ошибка загрузки данных с метрики");
	}elseif(!$xml = json_decode(json_encode(new SimpleXMLElement($data)), true)){ mpre("Ошибка парсинга результата регистрации метрики", $data);
	}elseif(!$yandex_metrika = fk("yandex_metrika", $w = ['id'=>$xml['counter']['id'], 'index_id'=>$index['id'], 'yandex_token_id'=>$yandex_token['id']], $w , $w)){ mpre("Ошибка сохранения рузультатов регистрации");
	}else{
		exit(json_encode($yandex_metrika));
	}
}elseif(!$yandex_webmaster = rb("yandex_webmaster", "index_id", $index['id'])){// mpre("Вебмастер не найден");
	if(!$metrika = file_get_contents($url = 'https://api-metrika.yandex.ru/counters.json?per_page=10000', false, stream_context_create(array('http'=>array( 'method'=>'GET', "Content-Type: application/json", 'header'=>"Authorization: OAuth {$yandex_token['name']}", ))))){ mpre("Ошибка запроса к метрике яндекс");
		}elseif(!$json = json_decode($metrika, true)){ mpre("Ошибка формирования жсон данных");
		}else{
			foreach($json['counters'] as $counter){
				if($index = rb("index", "name", "[{$counter['site']}]")){
					if($yandex_metrika = fk("yandex_metrika", array("id"=>$counter['id']), $counter += array("index_id"=>$index['id'], "yandex_token_id"=>$yandex_token['id']), $counter)){
						$yandex_metrika_index = fk("yandex_metrika_index", $w = array("index_id"=>$index['id'], "yandex_metrika_id"=>$yandex_metrika['id']), $w);
					}
				}
			}

			if(!$data = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts', false, stream_context_create(
					array('http'=>($param = array('method'=>'GET', 'header'=>"Authorization: OAuth {$yandex_token['name']}",)))
				))){ mpre("Ошибка загрузки данных");
			}elseif(!$xml = json_decode(json_encode(new SimpleXMLElement($data)), true)){ mpre("Ошибка json декодирования полученных из вебмастера данных");
			}else{
				foreach($xml['host'] as $host){
					if($index = rb("index", "name", "[{$host['name']}]")){
						$yandex_webmaster = fk("yandex_webmaster", $w = array('id'=>last(explode("/", $host['@attributes']['href']))), $w += array('yandex_token_id'=>$yandex_token['id'], "href"=>last(explode(":", $host['@attributes']['href'])), 'verification'=>get($host, 'verification', '@attributes', 'state'), 'crawling'=>get($host, 'crawling', '@attributes', 'state'), "index_id"=>$index['id'])+$host, $w);
					}
				}
			}

		} exit(json_encode($yandex_token));
}elseif(!$yandex_token = rb("yandex_token", "id", $yandex_webmaster["yandex_token_id"])){ mpre("Токен не установлен");
}else{// exit(mpre($yandex_token));
	if("verify" == $_REQUEST['api']){ # Проверка принадлежности хоста
		if($yandex_webmaster = rb("yandex_webmaster", "index_id", $index['id'])){
			if($data = file_get_contents($url = "https://webmaster.yandex.ru/api/v2/hosts/{$yandex_webmaster['id']}/verify", false, stream_context_create(array('http' =>
				($param = array(
					'method'  => 'GET',
					'content' => ($content = "<host><type>META_TAG</type></host>"),
					'header'  => array(
						"Authorization: OAuth ". $yandex_token['name'],
						"Content-Type: application/x-www-form-urlencoded",
						"Content-Length: ". strlen($content),
					),	
				))
			)))){
				if($xml = json_decode(json_encode(new SimpleXMLElement($data)), true)){
					if($index = fk("index", array("name"=>$xml['name']), null, array("yandex_verification"=>$xml['verification']['uin']))){

						if($data = file_get_contents($url = "https://webmaster.yandex.ru/api/v2/hosts/{$yandex_webmaster['id']}/verify", false, stream_context_create(array('http' =>
							($param = array(
								'method'  => 'PUT',
								'content' => ($content = "<host><type>META_TAG</type></host>"),
								'header'  => array(
									"Authorization: OAuth ". $yandex_token['name'],
									"Content-Type: application/x-www-form-urlencoded",
									"Content-Length: ". strlen($content),
								),	
							))
						)))){ exit(mpre("Ошибка загрузки данных")); }else{
							exit(json_encode($yandex_webmaster = fk("yandex_webmaster", ['id'=>$yandex_webmaster['id']], null, ['verification'=>''])));
						}// exit(mpre("Ошибка проверки сайта", $url, $param));
					}else{ exit(mpre("Ошибка установки кода подтверждения")); }
				}else{ exit(mpre("Ошика xml парсинга резальтата запроса кода проверки")); }
			}else{ exit(mpre("Ошибка запроса кода проверки", $param)); }
		}
	}elseif("tops" == $_REQUEST['api']){
		if(!$tops = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts/'. last(explode('/', $yandex_webmaster['href'])). '/tops/', false, stream_context_create(array('http' => array('method'  => 'GET','header'  => "Authorization: OAuth ". $yandex_token['name'],))))){ mpre("Ошибка загрузки данных");
		}elseif(!$xml = json_decode(json_encode(new SimpleXMLElement($tops)), true)){ mpre("Ошибка парсинга данных");
		}else{
			if($shows = get($xml, 'top-queries', 'top-shows', 'top-info')){// mpre($shows);
				if(get($shows, 'query') && ($info = $shows)){
					if($yandex_tops = fk("yandex_tops", $w = array("name"=>get($info, 'query')), $w)){
						$yandex_tops_index = fk("yandex_tops_index", $w = array("index_id"=>$index['id'], "yandex_tops_id"=>$yandex_tops['id']), $w += ($info + array('view'=>$info['count'])), $w += array('up'=>time()));
					} $result = array($shows);
				}else{
					foreach($shows as $info){// mpre($info);
						if($yandex_tops = fk("yandex_tops", $w = array("name"=>get($info, 'query')), $w)){
							$yandex_tops_index = fk("yandex_tops_index", $w = array("index_id"=>$index['id'], "yandex_tops_id"=>$yandex_tops['id']), $w += ($info + array('view'=>$info['count'])), $w += array('up'=>time()));
						}
					} $result = $shows;
				}
			} if($clicks = get($xml, 'top-queries', 'top-clicks', 'top-info')){
				foreach($clicks as $info){// mpre($info);
					if($name = get($info, 'query')){
						if($yandex_tops = fk("yandex_tops", $w = array("name"=>$name), $w)){
							$yandex_tops_index = fk("yandex_tops_index", $w = array("index_id"=>$index['id'], "yandex_tops_id"=>$yandex_tops['id']), $w += ($info + array('clicks'=>$info['count'], 'conversion'=>number_format($info['count']/$yandex_tops_index['view'], 5))), $w += array('up'=>time()));
						}
					}else{ /*mpre($info);*/ }
				}
			}
		} exit(json_encode(empty($result) ? array() : $result));
	}elseif("stats" == $_REQUEST['api']){
		if(!$data = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts/'. last(explode('/', $yandex_webmaster['href'])). '/stats/', false, stream_context_create(array('http' => array('method'  => 'GET','header'  => "Authorization: OAuth ". $yandex_token['name'],))))){ mpre("Ошибка загрузки данных");
		}elseif(!$xml = json_decode(json_encode(new SimpleXMLElement($data)), true)){ mpre("Ошибка парсигна данных");
		}elseif(!$yandex_webmaster = fk("yandex_webmaster", ["id"=>$yandex_webmaster['id']], null, ['url_count'=>$xml['url-count'], 'index_count'=>$xml['index-count'], 'internal_links_count'=>$xml['internal-links-count'], 'links_count'=>$xml['links-count'], 'url_errors'=>$xml['url-errors']])){ mpre("Ошибка сохранения статистики");
		}else{// mpre($xml);
			exit(json_encode(empty($xml) ? [] : $xml));
		}
	}elseif("indexed" == $_REQUEST['api']){
		if(!$data = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts/'. last(explode('/', $yandex_webmaster['href'])). '/indexed/', false, stream_context_create(array('http' => array('method'  => 'GET','header'  => "Authorization: OAuth ". $yandex_token['name'],))))){ mpre("Ошибка загрузки данных");
		}elseif(!$xml = json_decode(json_encode(new SimpleXMLElement($data)), true)){ mpre("Ошибка парсинга данных");
		}elseif(!$indexed = get($xml, 'last-week-index-urls', 'url')){// mpre("Ошибка определения индексов");
		}else{
			foreach($indexed as $url){
				$yandex_indexed = fk("yandex_indexed", $w = array("name"=>$url, "index_id"=>$index['id']), $w+=array("up"=>time()), $w);
			}
		} exit(json_encode(empty($indexed) ? [] : $indexed));
	}elseif("texts" == $_REQUEST['api']){
		if(!$data = file_get_contents($url = 'https://webmaster.yandex.ru/api/v2/hosts/'. $yandex_webmaster['id']. '/original-texts/', false, stream_context_create(array('http' => array('method'  => 'GET','header'  => "Authorization: OAuth ". $yandex_token['name'],))))){ mpre("Ошибка загрузки данных");
		}elseif(!$xml = json_decode(json_encode($x = new SimpleXMLElement($data)), true)){ mpre("Ошибка парсинга данных");
		}else{
			if(($texts = get($xml, 'original-text')) && array_key_exists(0, $texts)){
				foreach($texts as $text){
					$yandex_texts = fk("yandex_texts", $w = array("name"=>$text['id']), $w+=array('index_id'=>$index['id'], 'href'=>get($text, 'link', '@attributes', 'href'), "up"=>time(), "text"=>$text['content']), $w);
				}
			}elseif($text = $texts){
				$yandex_texts = fk("yandex_texts", $w = array("name"=>$text['id']), $w+=array('index_id'=>$index['id'], 'href'=>get($text, 'link', '@attributes', 'href'), "up"=>time(), "text"=>$text['content']), $w);
			}
		} exit(json_encode(empty($texts) ? [] : $texts));
	} exit();
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
} if(mpsettings($t = "{$arg['modpath']}_yandex_metrika", "Метрика") && !tables($table = "{$conf['db']['prefix']}{$t}")){
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
		`url_count` int(11) NOT NULL COMMENT 'Проиндексированных страниц',
		`index_count` int(11) NOT NULL COMMENT 'Страниц в поиске',
		`url-errors` int(11) NOT NULL COMMENT 'Ошибочные ссылки',
		`internal-links_count` int(11) NOT NULL COMMENT 'Внешние ссылки',
		`verification` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Проверка',
		`crawling` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Индексирование',
		PRIMARY KEY (`id`),
		KEY `url_count` (`url_count`),
		KEY `index_count` (`index_count`),
		KEY `url-errors` (`url-errors`),
		KEY `internal-links_count` (`internal_links_count`)
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
		`tracking` int(11) NOT NULL COMMENT 'Номер подмены номера',
		`index_id` int(11) NOT NULL,
		`yandex_metrika_id` int(11) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "{$arg['modpath']}_yandex_indexed", "Индекс") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `{$table}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`up` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`index_id` int(11) NOT NULL,
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		PRIMARY KEY (`id`),
		KEY `uid` (`uid`),
		KEY `name` (`name`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "{$arg['modpath']}_yandex_texts", "Тексты") && !tables($table = "{$conf['db']['prefix']}{$t}")){
	qw("CREATE TABLE `mp_themes_yandex_texts` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`time` int(11) NOT NULL,
		`up` int(11) NOT NULL,
		`uid` int(11) NOT NULL,
		`index_id` int(11) NOT NULL,
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`href` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`text` text COLLATE utf8_unicode_ci NOT NULL,
		PRIMARY KEY (`id`),
		KEY `up` (`up`),
		KEY `index_id` (`index_id`),
		KEY `name` (`name`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "themes_yandex_metrika_metrics", "Метрики") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `up` int(11) NOT NULL,
  `yandex_metrika_id` int(11) NOT NULL,
  `yandex_metrika_dimensions_id` int(11) NOT NULL,
  `yandex_metrika_period_id` int(11) NOT NULL,
  `visits` int(11) NOT NULL COMMENT 'Визитов',
  `users` int(11) NOT NULL COMMENT 'Пользователей',
  `pageviews` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `yandex_metrika_dimensions_id` (`yandex_metrika_dimensions_id`),
  KEY `yandex_metrika_period_id` (`yandex_metrika_period_id`),
  CONSTRAINT `mp_themes_yandex_metrika_metrics_ibfk_1` FOREIGN KEY (`yandex_metrika_period_id`) REFERENCES `mp_themes_yandex_metrika_period` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
}  if(mpsettings($t = "themes_yandex_metrika_period", "Период") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `date1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date1` (`date1`),
  KEY `date2` (`date2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "themes_yandex_metrika_dimensions", "Измерения") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
}
