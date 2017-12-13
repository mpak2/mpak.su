		<? if(!get($conf, 'settings', 'themes_params')):// mpre("Таблица параметров не найдена") ?>
		<? elseif(!$themes_params = rb("themes-params", "name", $p = "[Счетчик roistat.com]")):// mpre("Параметр не найден {$p}") ?>
		<? elseif(!$THEMES_PARAMS_INDEX = rb("themes-params_index", "params_id", "index_id", "id", $themes_params['id'], $conf['themes']['index']['id'])):// mpre("Изменений стилей для данного сайта не требуется") ?>
		<? elseif(!$themes_index = $conf['user']['sess']['themes_index']): mpre("Хост сайта не найден") ?>
		<? else: ?> 
			<script>
				<? foreach($THEMES_PARAMS_INDEX as $themes_params_index): ?> 
					(function(w, d, s, h, id){
						w.roistatProjectId = id; w.roistatHost = h;
						var p = d.location.protocol == "https:" ? "https://" : "http://";
						var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init";
						var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
					})(window, document, 'script', 'cloud.roistat.com', '<?=$themes_params_index["name"]?>');
				<? endforeach; ?> 
			</script>
		<? endif; ?> 
