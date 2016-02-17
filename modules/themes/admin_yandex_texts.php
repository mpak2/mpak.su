<?

if(array_key_exists("null", $_GET) && $_POST){
	if($pages = rb("pages", "id", $_POST['pages_id'])){
		if($tpl['pages_index'] = rb("pages_index", "pages_id", "id", $pages['id'])){
			if((count($tpl['pages_index']) == 1) && ($pages_index = first($tpl['pages_index']))){
				if($yandex = first(rb("yandex"))){
					if($yandex_token = rb("yandex_token", "id", $yandex['yandex_token_id'])){
						if($yandex_webmaster = rb("yandex_webmaster", "index_id", $pages_index['index_id'])){
							$content = strip_tags($pages['text']);
							foreach(rb("pages_features", "pages_id", "id", $pages['id']) as $pages_features){
								$content .= "\n\n". strip_tags($pages_features['text']);
							} if($data = file_get_contents($url = "https://webmaster.yandex.ru/api/v2/hosts/{$yandex_webmaster['id']}/original-texts/", false, stream_context_create(array('http' =>
								($param = array(
									'method'  => 'POST',
									'content' => ($content = urlencode("<original-text><content>". $content. "</content></original-text>")),
									'header'  => array(
										"Authorization: OAuth ". $yandex_token['name'],
										"Content-Length: ". strlen($content),
										"Content-Type: application/x-www-form-urlencoded",
									),
								))
							)))){
								if($xml = json_decode(json_encode($x = new SimpleXMLElement($data)), true)){
									$yandex_texts = fk("yandex_texts", $w = array("name"=>$xml['id']), $w += array("index_id"=>$pages_index['index_id'], "pages_id"=>$pages['id'], "href"=>$xml['link']['@attributes']['href']), $w);
									exit(mpre($yandex_texts));
								}else{ exit(mpre("Ошибка xml данных", $param, $data)); }
							}else{ exit(mpre("Ошибка регистрации текста", $param)); }
						}else{ exit(mpre("Метрика не найдена")); }
					}else{ exit(mpre("Токен доступа к приложению не найден")); }
				}else{ exit(mpre("Приложение не найдено")); }
			}else{ exit(mpre("Регистрируемый текст должен быть прикреплен к одному сайту")); }
		}else{ exit(mpre("Не определено прикрепление статьи к сайту")); }
	}else{ exit(mpre("Ошибка запроса к статье")); }
}
