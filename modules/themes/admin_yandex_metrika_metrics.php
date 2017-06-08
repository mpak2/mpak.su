<?

if(!$n = date("N", ($offset = mpsettings("{$arg['modpath']}_admin_yandex_metrika_metrics=>offset")) ? strtotime("{$offset} day") : time())){ mpre("Ошибка формирования даты");
}elseif(!$date1 = date("Y-m-d", strtotime((-$n+1+($offset = get($_GET, "week")*7)). " days"))){ mpre("Ошибка дата1");
}elseif(!$date2 = date("Y-m-d", strtotime((-$n + 7 + $offset). " days"))){ mpre("Ошибка дата2");
//}elseif(!mpre($date1, $date2)){
}elseif(!$tpl['yandex_metrika_period'] = fk("{$conf['db']['prefix']}themes_yandex_metrika_period", $w = array("date1"=>$date1, "date2"=>$date2), $w)){ mpre("Ошибка установки периода");

}elseif(!$_POST){// mpre("Послан пост запрос", $_POST);
}elseif(!get($_POST, "yandex_metrika_id")){ exit(mpre("Номер метрики не задан"));
}elseif(!$yandex_metrika_period = rb("themes-yandex_metrika_period", "id", $_POST['yandex_metrika_period_id'])){ exit(mpre("Период не найден"));
}elseif(!$yandex_metrika = rb("themes-yandex_metrika", "id", $_POST["yandex_metrika_id"])){ exit(mpre("Ошибка выборке метрики"));
}elseif(!$yandex_token = rb("themes-yandex_token", "id", rb("themes-yandex", "yandex_token_id"))){ exit(mpre("Токен не найден"));
}elseif(!$data = mpde(mpcurl("https://api-metrika.yandex.ru/management/v1/counter/{$yandex_metrika['id']}/goals?oauth_token={$yandex_token['name']}"))){ exit(mpre("Ошибка запроса списка целей"));
}elseif(!$json = json_decode($data, true)){ exit(mpre("JSON формат не сфоримрован"));

}elseif(!$data = mpde(mpcurl("https://api-metrika.yandex.ru/stat/v1/data?preset=sources_summary&metrics=". implode(",", array("ym:s:users", "ym:s:visits", "ym:s:pageviews")). "&date1={$yandex_metrika_period['date1']}&date2={$yandex_metrika_period['date2']}&id={$yandex_metrika['id']}&oauth_token={$yandex_token['name']}"))){ exit("Ошибка при загрузки данных метрики");
}elseif(!$json = json_decode($data, true)){ mpre("Ошибка получения json данных");
}else{ exit(json_encode($yandex_metrika));
/*	foreach($json['goals'] as $goals){// mpre($goals);
		if($yandex_metrika_goals = fk("yandex_metrika_goals", $w = array("yandex_metrika_id"=>$yandex_metrika['id'], "name"=>$goals['name'], "type"=>$goals['type']), $w += array("is_retargeting"=>$goals['is_retargeting']), $w)){
			foreach($goals['conditions'] as $conditions){
				$yandex_metrika_conditions = fk("yandex_metrika_conditions", $w = array("yandex_metrika_goals_id"=>$yandex_metrika_goals['id']), $w += array("type"=>$conditions['type'], "name"=>$conditions['url']), $w);
			}
		}else{ exit(mpre("Ошибка добавления цели")); }
	}*/

	foreach($json['data'] as $d){// (mpre($d['metrics']));
		if($yandex_metrika_dimensions = fk("{$conf['db']['prefix']}themes_yandex_metrika_dimensions", $w = array("name"=>$d['dimensions'][0]['name'], "alias"=>$d['dimensions'][0]['id']), $w)){
			$yandex_metrika_metrics = fk("{$conf['db']['prefix']}themes_yandex_metrika_metrics",
				$w = array("yandex_metrika_id"=>$yandex_metrika['id'], "yandex_metrika_dimensions_id"=>$yandex_metrika_dimensions['id'], "yandex_metrika_period_id"=>$yandex_metrika_period['id']),
				$w += array("users"=>$d['metrics'][0], "visits"=>$d['metrics'][1], "pageviews"=>$d['metrics'][2]), $w
			);
		}else{ mpre("Ошибка добавления измерения"); }
	} $yandex_metrika_metrics = fk("{$conf['db']['prefix']}themes_yandex_metrika_metrics",
		$w = array("yandex_metrika_id"=>$yandex_metrika['id'], "yandex_metrika_dimensions_id"=>0, "yandex_metrika_period_id"=>$yandex_metrika_period['id']),
		$w += array("users"=>$json['totals'][0], "visits"=>$json['totals'][1], "pageviews"=>$json['totals'][2]), $w
	); exit(json_encode($json));
}
