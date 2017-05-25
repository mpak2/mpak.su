<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> Из за неработаюго метода .load() пока не можем перейти (Используется при обработки form.load в перехвате загрузки) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

<style>
	div.table {display:table; width:100%; vertical-align:top; border-collapse:collapse;}
	div.table > div {display:table-row;}
	div.table > div > span {display:table-cell; padding:3px; vertical-align:top;}
	div.table > div.th > span {background-color:#444; color:white;}
	
	.pre {/*position:absolute;*/ z-index:999; background-color:white; border-radius:10px; padding:5px; opacity:0.8; border:3px double red; font-size:12px; color:gray;}
	.pre legend { color:black; font-size:100%; /*top: 13px;*/ position: relative; }
	
	.pager a.active {color:#fe8e23;}
</style>

<? if(!get($conf, 'settings', 'themes_params')):// mpre("Параметры редактора тем не заданы") ?>
<? elseif(!$themes_params = rb("themes-params", "name", $w = "[Вывод ливтекста на сайте]")):// mpre("Параметр не найден {$w}") ?>
<? elseif(!$themes_params_index = rb("themes-params_index", "params_id", "index_id", $themes_params['id'], $conf['themes']['index']['id'])):// mpre("Значение хоста не найдено") ?>
<? elseif(get($themes_params_index, 'hide')):// mpre("Отображение выключено {$w}") ?>
<? else:// mpre($themes_params_index) ?>
	<!-- {literal} -->
	<script type='text/javascript'>
		window['li'+'v'+'eT'+'e'+'x'] = true,
		window['live'+'TexI'+'D'] = <?=$themes_params_index['name']?>,
		window['liveT'+'ex_ob'+'jec'+'t'] = true;
		(function() {
			var t = document['cre'+'a'+'teElem'+'e'+'nt']('script');
			t.type ='text/javascript';
			t.async = true;
			t.src = '//cs'+'15'+'.l'+'ivete'+'x.'+'ru'+'/js'+'/clie'+'nt.js';
			var c = document['getElemen'+'tsByTag'+'Na'+'me']('script')[0];
			if ( c ) c['p'+'ar'+'en'+'t'+'Nod'+'e']['i'+'nsertB'+'efore'](t, c);
			else document['docume'+'n'+'tElemen'+'t']['firs'+'t'+'Ch'+'ild']['app'+'en'+'dCh'+'ild'](t);
		})();
	</script>
	<!-- {/literal} -->
<? endif; ?>

<? if($callback = get($conf, 'themes', 'index', 'callback')): # Форма обратной связи eyenewton.ru ?>
	<script type="text/javascript" src="//eyenewton.ru/scripts/callback.min.js" charset="UTF-8"></script>
	<script type="text/javascript">/*<![CDATA[*/var newton_callback_id="<?=$callback?>";/*]]>*/</script>
<? endif; ?>

<? if($themes_index = get($conf, 'user', 'sess', 'themes_index')): ?> 
<? elseif(!get($themes_index, 'index_cat_id') || !($themes_cat = rb("{$conf['db']['prefix']}themes_index_cat", "id", $themes_index['index_cat_id']))): ?> 
<? else: ?>
	<? if($icon = get($themes_cat, "img")): # Фавикон ?> 
		<link rel="icon" type="image/png" href="/themes:img/<?=$themes_cat['id']?>/tn:index_cat/fn:img/w:65/h:65/null/img.png" />
	<? endif; ?> 
<? endif; ?> 

<? if(!get($conf, 'settings', 'themes_params')):// mpre("Таблица параметров не создана") ?>
<? elseif(!$themes_params = rb("themes-params", "name", $p = "[Код pozvonim.com]")):// mpre("Параметр не найден {$p}") ?>
<? elseif(!$themes_params_index = rb("themes-params_index", "params_id", "index_id", $themes_params['id'], "[0,NULL,{$conf['themes']['index']['id']}]")):// mpre("Значение параметра для сайта не найдено {$p}") ?>
<? else: ?>
	<script crossorigin="anonymous" async type="text/javascript" src="//api.pozvonim.com/widget/callback/v3/<?=$themes_params_index['name']?>/connect" id="check-code-pozvonim" charset="UTF-8"></script>
<? endif; ?> 

<? if($verification = get($themes_index, 'yandex_verification')): # Проверка вебмастера яндекса ?> 
	<meta name="yandex-verification" content="<?=$verification?>" />
<? endif; ?> 
<? if($verification = get($themes_index, 'google_verification')): # Проверка вебмастера гугл ?> 
	<meta name="google-site-verification" content="<?=$verification?>" />
<? endif; ?>

<? if($analytics = get($conf, 'themes', 'index', 'analytics')): ?>
	<!-- google-analytics -->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			
			ga('create', '<?=$analytics?>', 'auto');
			ga('send', 'pageview');
		</script>
	<!-- google-analytics end -->
<? endif; ?>

<? if(!get($conf, 'settings', 'themes_yandex_metrika')):// mpre("Раздел яндекс метрики не создан") ?>
<? elseif(!$themes_index = get($conf, 'themes', 'index')): mpre("Информация о хосте не найдена") ?>
<? elseif(get($conf, 'settings', 'themes_yandex_metrika_index') && (!$THEMES_YANDEX_METRIKA_INDEX = rb("themes-yandex_metrika_index", "index_id", "id", $themes_index['id']))): mpre("У сайта не найдено <a href='/themes:admin/r:themes-yandex_metrika_index?&where[index_id]={$themes_index['id']}'>устанволенных метрик</a>") ?>
<? elseif(get($conf, 'settings', 'themes_yandex_metrika') && (!$THEMES_YANDEX_METRIKA = rb("themes-yandex_metrika", "id", "id", rb($THEMES_YANDEX_METRIKA_INDEX, "yandex_metrika_id"))) &0): mpre("Счетчики установленные на сайте не найдены") ?>
<? elseif((!$THEMES_YANDEX_METRIKA_GOAL_METRIKA = (get($conf, 'settings', 'themes_yandex_metrika_goal_metrika') ? rb("themes-yandex_metrika_goal_metrika", "yandex_metrika_id", "id", $THEMES_YANDEX_METRIKA) : [])) &0): mpre("Исследования для сайта не установлены") ?>
<? elseif((!$THEMES_YANDEX_METRIKA_GOAL_ANALYSIS = (get($conf, 'settings', 'themes_yandex_metrika_goal_analysis') ? rb("themes-yandex_metrika_goal_analysis", "id", "id", rb($THEMES_YANDEX_METRIKA_GOAL_METRIKA, "yandex_metrika_goal_analysis_id")) : [])) &0): mpre("Ошибка составления списка исследований для сайта") ?>
<? elseif((!$THEMES_YANDEX_METRIKA_GOAL = (get($conf, 'settings', 'themes_yandex_metrika_goal') ? rb("themes-yandex_metrika_goal", "yandex_metrika_goal_analysis_id", "id", $THEMES_YANDEX_METRIKA_GOAL_ANALYSIS) : [])) &0): mpre("Цели яндекс метрики не найдены"); ?>
<? elseif((!$THEMES_YANDEX_METRIKA_GOAL_ELEMENT = (get($conf, 'settings', 'themes_yandex_metrika_goal_element') ? rb("themes-yandex_metrika_goal_element", "yandex_metrika_goal_id", "id", $THEMES_YANDEX_METRIKA_GOAL) : [])) &0): mpre("Элементы событий не найдены") ?>
<? else:// mpre($THEMES_YANDEX_METRIKA_GOAL_ANALYSIS, $THEMES_YANDEX_METRIKA_GOAL) ?>
	<!-- Yandex.Metrika counter -->
		<? foreach($THEMES_YANDEX_METRIKA_INDEX as $themes_yandex_metrika_index): ?> 
			<? if(!$themes_yandex_metrika = rb($THEMES_YANDEX_METRIKA, "id", $themes_yandex_metrika_index['yandex_metrika_id'])): mpre("Метрика связанная с хостом не найдена") ?>
			<?// elseif((!$_THEMES_YANDEX_METRIKA_GOAL = rb($THEMES_YANDEX_METRIKA_GOAL, "yandex_metrika_id", "id", "[{$themes_yandex_metrika['id']},0,NULL]")) &0): mpre("Цели для метрики не найдены") ?>
			<?// elseif((!$_THEMES_YANDEX_METRIKA_GOAL_ELEMENT = rb($THEMES_YANDEX_METRIKA_GOAL_ELEMENT, "yandex_metrika_goal_id", "id", $_THEMES_YANDEX_METRIKA_GOAL)) &0): mpre("Элементы для метрики не найдены") ?>
			<? elseif(!$mtid = (get($themes_yandex_metrika, 'mtid') ?: $themes_yandex_metrika['id'])): mpre("Ошибка нахождения номера счетчика") ?>
			<? /*elseif(!array_map(function($_themes_yandex_metrika_goal) use($mtid, $_THEMES_YANDEX_METRIKA_GOAL_ELEMENT){ ?>
				<? }, $_THEMES_YANDEX_METRIKA_GOAL)): mpre("Ошибка установки событий")*/ ?>
			<? else: ?>
				<script type="text/javascript">
					/*<![CDATA[*/
					(function (d, w, c) {
						(w[c] = w[c] || []).push(function() {
							try {
								eval("w.yaCounter<?=$mtid?> = new Ya.Metrika({id:<?=$mtid?>, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true});");
							} catch(e) { }
						});
					
						var n = d.getElementsByTagName("script")[0],
							s = d.createElement("script"),
							f = function () { n.parentNode.insertBefore(s, n); };
						s.type = "text/javascript";
						s.async = true;
						s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
					
						if (w.opera == "[object Opera]") {
							d.addEventListener("DOMContentLoaded", f, false);
						} else { f(); }
					})(document, window, "yandex_metrika_callbacks");
					/*]]>*/
				</script>
			<? endif; ?> 

			
			<? foreach($THEMES_YANDEX_METRIKA_GOAL as $themes_yandex_metrika_goal): ?>
				<? if(!$_THEMES_YANDEX_METRIKA_GOAL_ELEMENT = rb($THEMES_YANDEX_METRIKA_GOAL_ELEMENT, "yandex_metrika_goal_id", "id", $themes_yandex_metrika_goal['id'])):// mpre("Элементы для целей не надены"); ?>
				<? elseif(!$themes_yandex_metrika_goal_analysis = rb($THEMES_YANDEX_METRIKA_GOAL_ANALYSIS, "id", $themes_yandex_metrika_goal['yandex_metrika_goal_analysis_id'])): mpre("Ошибка получения анализа цели") ?>
				<? elseif(!$themes_yandex_metrika_goal_metrika = rb($THEMES_YANDEX_METRIKA_GOAL_METRIKA, "yandex_metrika_goal_analysis_id", $themes_yandex_metrika_goal_analysis['id'])): mpre("Ошибка получения связи анализа с метрикой") ?>
				<? elseif(!$themes_yandex_metrika = rb($THEMES_YANDEX_METRIKA, "id", $themes_yandex_metrika_goal_metrika['yandex_metrika_id'])): mpre("Счетчик не найден") ?>
				<? elseif(!$mtid = (get($themes_yandex_metrika, 'mtid') ?: $themes_yandex_metrika['id'])): mpre("Ошибка нахождения номера счетчика") ?>
				<? else:// mpre($themes_yandex_metrika_goal, $_THEMES_YANDEX_METRIKA_GOAL_ELEMENT) ?>
					<? foreach($_THEMES_YANDEX_METRIKA_GOAL_ELEMENT as $themes_yandex_metrika_goal_element): ?>
						<script sync>
							(function($, script){
								$(document).on("<?=$themes_yandex_metrika_goal_element['event']?>", "<?=$themes_yandex_metrika_goal_element['selector']?>", function(e){
									console.log("Событие яндекс");
									if(!(counter = eval("window.yaCounter<?=$mtid?>"))){ console.error("Ошибка установки счетчика");
									}else{// console.info("Событие "+goal.alias, counter.reachGoal(goal = "GET_FORM"));
										counter.reachGoal(alias = "<?=$themes_yandex_metrika_goal['alias']?>");
										console.info("Yandex.Metrika.<?=$mtid?> ", alias, "Селектор:", "«<?=$themes_yandex_metrika_goal_element['selector']?>»", " событие:", "«<?=$themes_yandex_metrika_goal_element['event']?>»");
									}
								}).on("init", function(){
									console.info("Исследования:", "«<?=$themes_yandex_metrika_goal_analysis['name']?>»", "Счетчик:", "«<?=$mtid?>»", "Яндекс событие:", "«<?=$themes_yandex_metrika_goal['alias']?>»", "Селектор:", "«<?=$themes_yandex_metrika_goal_element['selector']?>»", "JS событие:", "«<?=$themes_yandex_metrika_goal_element['event']?>»", "Количество:", $("<?=$themes_yandex_metrika_goal_element['selector']?>").length);
								}).ready( function(e){ $(script).parent().trigger("init"); } )
							})(jQuery, document.currentScript)
						</script>
					<? endforeach; ?>
				<? endif; ?>
			<? endforeach; ?>

			<? if(($tracking = get($themes_yandex_metrika_index, "tracking")) > 0): # Не выводим если стоит отрицательное число ?>
				<!-- PrimeGate CallTracking-->
					<script>
						(function () {
								var pg = document.createElement('script');
								var protocol = 'https:' == document.location.protocol ? 'https://' : 'http://';
								pg.src = protocol + 'js.primegate.io/primegate.min.js'; pg.setAttribute('async', 'true');
								document.documentElement.getElementsByTagName('head')[0].appendChild(pg);
								PrimeGate = {}; window.pg = []; components = ['init', 'track', 'identify'];
								for (var i in components){
									PrimeGate[components[i]] = (function(component){
										return function(){
											window.pg.push(component, [].slice.call(arguments, 0));
										}
									}(components[i]));
								}
						})();
						PrimeGate.init(<?=$tracking?>);
					</script>
				<!-- PrimeGate CallTracking-->
			<? endif; ?>
		<? endforeach; ?> 
	<!-- /Yandex.Metrika counter -->
<? endif; ?>

<? if(!array_search("Администратор", $conf['user']['gid'])): mpre("Раздел предназначен только администраторам") ?>
<? elseif(!$themes_index = get($conf, 'themes', 'index')):// mpre("Хост сайта не найден") ?>
<? elseif(($canonical = get($conf, 'settings', 'canonical')) &0): mpre("Канонический адрес не задан") ?>
<? elseif(($uri = get($canonical = get($conf, 'settings', 'canonical'), 'name') ? $canonical['name'] : $_SERVER['REQUEST_URI']) && (!$get = mpgt($uri)) &0): mpre("Параметры адреса не определены <b>{$uri}</b>") ?>
<? elseif(!$alias = first(array_keys((array)get($get, 'm'))). ":". first(get($get, 'm')). (($keys = array_keys(array_diff_key($get, array_flip(["m", "id"])))) ? "/". implode("/", $keys) : "")): mpre("Алиас сфоримрован ошибочно") ?>
<? elseif((!$seo_cat = rb("seo-cat", "id", get($canonical, 'cat_id'))) && (!$seo_cat = rb("seo-cat", "alias", (empty($alias) ? false : "[{$alias}]"))) &0): mpre("Категория не найдена") ?>
<? else:// mpre($seo_cat) ?>
		<div class="themes_header_seo_blocks" style="z-index:9999; border:1px solid #eee; border-radius:7px; position:fixed; background-color:rgba(255,255,255,0.7); color:black; padding:0 5px; left:10px; top:10px; width:auto;">
			<div class="table">
				<div>
					<span><a href="/admin" title="Перейти в админраздел"><img src="/themes/theme:zhiraf/null/i/logo.png"></a></span>
					<span>
						<div title="Категория ссылки">
							<? if($name = get($seo_cat, 'name')): ?>
								<a href="/seo:admin/r:seo-cat?&where[id]=<?=get($seo_cat, 'id')?>"><?=$name?></a>
							<? else: ?>Категория не задана<? endif; ?>
						</div>
						<div class="admin_content" title="Информация о странице"><?=get($canonical, 'name')?></div>
					</span>
				</div>
			</div>
			<script>
				$(function(){// Ссылка на редактирование заголовка страницы
					if("object" == typeof(index = $.parseJSON(canonical = '<?=strtr(json_encode($canonical, JSON_UNESCAPED_UNICODE), ["\\\""=>""])?>'))){// console.log("index", index);
						var themes_index = $.parseJSON('<?=strtr(json_encode($themes_index, JSON_UNESCAPED_UNICODE), ["\\\""=>""])?>');
						$(".themes_header_seo_blocks").on("click", ".admin_content", function(e){
							window.open("/seo:admin/r:seo-index_themes?&where[location_id]="+index.id+"&where[themes_index]="+themes_index.id);
						}).find(".admin_content").css("cursor", "pointer");
					}else{// console.log("canonical:", canonical);
						$(".themes_header_seo_blocks").on("ajax", function(e, modpath, table, get, post, complete, rollback){
							var href = "/"+modpath+":ajax/class:"+table; console.log("get:", get);
							$.each(get, function(key, val){ href += (key == "uri" ? "" : "/"+ key+ ":"+ val); });
							if(typeof(get["uri"]) != "undefined"){
									href = href + "/null/name:"+get["uri"];
							} console.log("href:", href);

							$.post(href, post, function(data){ if(typeof(complete) == "function"){
								complete.call(e.currentTarget, data);
							}}, "json").fail(function(error) {if(typeof(rollback) == "function"){
									rollback.call(e.currentTarget, error);
							} alert(error.responseText) });
						}).on("click", ".admin_content", function(e){
							if(!(href = prompt("Адрес страницы"))){ // alert("Отмена действия");
							}else if(href.substring(0, 1) != "/"){ alert("Адрес должен начинаться с правого слеша «/»");
							}else{ console.log("Выполнение");
								var title = "";
								if(h1 = $("h1").get(0)){
									var title = h1.innerHTML;
								}else{ console.log("Заголовок для сайта не найден"); }

								$(e.delegateTarget).trigger("ajax", ["seo", "index", {"uri":href}, {}, function(seo_index){
									console.log("seo_index:", seo_index);
									$(e.delegateTarget).trigger("ajax", ["seo", "location", {"uri":document.location.pathname}, {}, function(seo_location){
										console.log("seo_location:", seo_location);
										$(e.delegateTarget).trigger("ajax", ["seo", "index_themes", {themes_index:<?=get($conf, 'themes', 'index', 'id')?>, index_id:seo_index.id, location_id:seo_location.id}, {title:title}, function(index_themes){
											console.log("index_themes:", index_themes);
											$(e.delegateTarget).trigger("ajax", ["seo", "location_themes", {themes_index:<?=get($conf, 'themes', 'index', 'id')?>, index_id:seo_index.id, location_id:seo_location.id}, {}, function(location_themes){
												console.log("location_themes:", location_themes);
												document.location.href = href;
											}])
										}])
									}])
								}])
							}
						}).find(".admin_content").css("cursor", "pointer").text(canonical != "false" ? canonical : "Задать адрес");
					}
				})/*.on("click", "fieldset.pre", function(e){
					console.log(e.type, "pre");
				})*/.one("init", function(e){ // Перетаскивание админских элементов
					$.getScript("//code.jquery.com/ui/1.11.4/jquery-ui.js", function(){
						setTimeout(function(){ // Ожидаем загрузки всех элементов на страницу
							$("fieldset.pre").draggable({handle:"legend"}).css("position", "absolute").find("legend").css("cursor", "pointer");
						}, 1000);
					})
				})//.ready(function(e){ $(script).parent().trigger("init"); })
			</script>
	</div>
<? endif; ?>

<? if(get($conf, 'settings', 'themes_orders')): ?>
	<? inc("modules/themes/blocks/orders.tpl") ?>
<? endif; ?>

<? if($themes_scrolltop = get($conf, 'settings', 'themes_scrolltop')): ?>
	<script sync>
		(function($, script){
			$(script).parent().one("init", function(e){
				$(document).data("themes_scrolltop", 1);
				$("<button"+">").addClass("themes_scrolltop").text("<?=$themes_scrolltop?>").css({"position":"fixed", "top":"90%", "right":"3%", "display":"none"}).appendTo("body");
				$(document).on("click", "button.themes_scrolltop", function(e){
					$(e.delegateTarget).scrollTop(0);
				}).on("scroll", function(e){
					var hide = $(e.delegateTarget).data("themes_scrolltop");
					if(($(e.delegateTarget).scrollTop() > 300) && hide){
						$(e.delegateTarget).find(".themes_scrolltop").show();
						$(e.delegateTarget).data("themes_scrolltop", 0);
					}else if(($(e.delegateTarget).scrollTop() < 300) && !hide){
						$(e.delegateTarget).find(".themes_scrolltop").hide();
						$(e.delegateTarget).data("themes_scrolltop", 1);
					}
				})
			}).ready(function(e){ $(script).parent().trigger("init"); })
		})(jQuery, document.currentScript)
	</script>
<? endif; ?>

<? if(!get($conf, 'settings', 'themes_params')):// mpre("Таблица параметров не найдена") ?>
<? elseif(!$themes_params = rb("themes-params", "name", $p = "[Код роистата]")):// mpre("Код риостата не найден") ?>
<? elseif(!$themes_params_index = rb("themes-params_index", "params_id", "index_id", $themes_params['id'], $w = "[". get($conf, 'themes', 'index', 'id'). ",0,NULL]")):// mpre("Значение параметра для сайта не найдено {$p}", $w) ?>
<? else: ?>
	<script>
		(function(w, d, s, h, id) {
				w.roistatProjectId = id; w.roistatHost = h;
				var p = d.location.protocol == "https:" ? "https://" : "http://";
				var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init";
				var js = d.createElement(s); js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
		})(window, document, 'script', 'cloud.roistat.com', '<?=$themes_params_index["name"]?>');
	</script>
<? endif; ?>

<? if(!get($conf, 'settings', 'themes_params')):// mpre("Таблица параметров не найдена") ?>
<? elseif(!$themes_params = rb("themes-params", "name", $p = "[Изменение стилей для шаблона]")):// mpre("Параметр не найден {$p}") ?>
<? elseif(!$THEMES_PARAMS_INDEX = rb("themes-params_index", "params_id", "index_id", "id", $themes_params['id'], $conf['themes']['index']['id'])):// mpre("Изменений стилей для данного сайта не требуется") ?>
<? else:// mpre($THEMES_PARAMS_INDEX) ?>
	<style>
		<? foreach(rb($THEMES_PARAMS_INDEX, "hide", "id", 0) as $themes_params_index): ?>
			<?=$themes_params_index['selector']?> {<?=$themes_params_index['name']?>:<?=$themes_params_index['value']?>;/* content:"Код в заголовоке" */}
		<? endforeach; ?>
	</style>
<? endif; ?>

<? if(!$themes_getScript = get($conf, 'settings', 'themes_getScript')):// mpre("Яваскрипт для загрузки не указан") ?>
<? else:// mpre("Работа калтрекинга", $themes_getScript) ?>
	<script sync>
		(function($, script){
			$(script).parent().one("init", function(e){
				$.getScript("<?=$themes_getScript?>", function( data, textStatus, jqxhr ) {
//					console.log(data); // Data returned
//					console.log(textStatus); // Success
//					console.log(jqxhr.status); // 200
//					console.log("tracking:", data);
				})
			}).ready(function(e){ $(script).parent().trigger("init"); })
		})(jQuery, document.currentScript)
	</script>
<? endif; ?>
