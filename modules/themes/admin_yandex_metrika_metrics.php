<?
if(!is_numeric($n = date("N", ($offset = mpsettings("{$arg['modpath']}_admin_yandex_metrika_metrics=>offset")) ? strtotime("{$offset} day") : time()))){ mpre("Ошибка получения дня недели");
}elseif(!$date1 = date("Y-m-d", strtotime((-$n/*-6*/+1+($offset = get($_GET, "week")*7)). " days"))){ mpre("Дата начала периода");
}elseif(!$date2 = date("Y-m-d", strtotime((-$n + 7 + $offset). " days"))){ mpre("Дата окончания периода");
}elseif(!$tpl['yandex_metrika_period'] = fk("themes-yandex_metrika_period", $w = array("date1"=>$date1, "date2"=>$date2), $w)){ mpre("Список периодов");

}elseif(!array_key_exists("null",$_GET)){ // mpre("Аякс запрос");
}elseif(!get($_POST, "yandex_metrika_id")){ exit(mpre("Метрика не задана"));
}elseif(!$yandex_metrika_period = rb("themes-yandex_metrika_period", "id", $_POST['yandex_metrika_period_id'])){ exit(mpre("Период не найден"));
}elseif(!$yandex_metrika = rb("themes-yandex_metrika", "id", $_POST["yandex_metrika_id"])){ exit(mpre("Ошибка выборке метрики"));
}elseif(!$THEMES_YANDEX = rb("themes-yandex")){ mpre("Список приложений не найдено");
}elseif(!$themes_yandex = last($THEMES_YANDEX)){ mpre("Текущее приложение не найдено");
}elseif(!is_array($yandex_token = rb("themes-yandex_token", "id", $yandex_metrika["yandex_token_id"]))){ exit(mpre("Токен счетчика не найден"));
}elseif(!$yandex_token && (!$yandex_token = rb("themes-yandex_token", "id", $themes_yandex["yandex_token_id"]))){ exit(mpre("Токен приложений не найден"));
}elseif(!$mtid = ($yandex_metrika["mtid"] ?: $yandex_metrika["id"])){ mpre("Номер счетчика");
}elseif(!$href = "https://api-metrika.yandex.ru/stat/v1/data?preset=sources_summary&metrics=". implode(",", array("ym:s:users", "ym:s:visits", "ym:s:pageviews")). "&date1={$yandex_metrika_period['date1']}&date2={$yandex_metrika_period['date2']}&id={$mtid}&oauth_token={$yandex_token['name']}"){ mpre("Ошибка формирования ссылки");
//}elseif(!mpre($href)){
}elseif(!$data = mpde(mpcurl($href))){ exit(mpre("Ошибка при загрузки данных метрики"));
}elseif(!$json = json_decode($data, true)){ mpre("Ошибка получения json данных");
}elseif(!array_key_exists("data",$json)){ exit("[]"); mpre($yandex_metrika,$href,$json); 
}else{
	foreach($json['data'] as $d){// (mpre($d['metrics']));
		if(!$yandex_metrika_dimensions = call_user_func(function($d){
				if(!$yandex_metrika_dimensions = fk("themes-yandex_metrika_dimensions", $w = ["name"=>get($d, 'dimensions', 0, 'name'), "alias"=>get($d, 'dimensions', 0, 'id')], $w)){ mpre("Ошибка добавления измерения");
				}elseif(!get($d, 'dimensions', 1, 'id')){ return $yandex_metrika_dimensions; mpre("Нижестоящий элемент не указан");
				}elseif(!$_yandex_metrika_dimensions = fk("themes-yandex_metrika_dimensions", $w = ["yandex_metrika_dimensions_id"=>$yandex_metrika_dimensions['id'], "alias"=>get($d, 'dimensions', 1, 'id')], $w += ["name"=>get($d, 'dimensions', 1, 'name')])){ mpre("Ошибка добавления нижестоящего изменения");
				}else{ return $_yandex_metrika_dimensions; }
			}, $d)){ mpre("Ошибка нахождения текущего элемента");
		}elseif(!$yandex_metrika_metrics = fk("themes-yandex_metrika_metrics",
				$w = array("yandex_metrika_id"=>$yandex_metrika['id'], "yandex_metrika_dimensions_id"=>$yandex_metrika_dimensions['id'], "yandex_metrika_period_id"=>$yandex_metrika_period['id']),
				$w += array("users"=>$d['metrics'][0], "visits"=>$d['metrics'][1], "pageviews"=>$d['metrics'][2]), $w
			)){ mpre("Ошибка добавления метрики");
		}else{// mpre($yandex_metrika_dimensions, $d);
			$YANDEX_METRIKA_METRICS[$yandex_metrika_metrics['id']] = $yandex_metrika_metrics;
		}
	} $yandex_metrika_metrics = fk("themes-yandex_metrika_metrics",
		$w = array("yandex_metrika_id"=>$yandex_metrika['id'], "yandex_metrika_dimensions_id"=>0, "yandex_metrika_period_id"=>$yandex_metrika_period['id']),
		$w += array("users"=>$json['totals'][0], "visits"=>$json['totals'][1], "pageviews"=>$json['totals'][2]), $w
	); exit(json_encode($json));
}
