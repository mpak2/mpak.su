
<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> Из за неработаюго метода .load() пока не можем перейти (Используется при обработки form.load в перехвате загрузки) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<style>
	div.table {display:table; width:100%; vertical-align:top; border-collapse:collapse;}
	div.table > div {display:table-row;}
	div.table > div > span {display:table-cell; padding:3px; vertical-align:top;}
	div.table > div.th > span {background-color:#444; color:white;}

	div.table.border { border-collapse:collapse; }
	div.table.border > div > span { border:1px solid gray; }
	div.table.border > div.th > span { border:0; }
	.pager a.active {color:#fe8e23;}

	.right { text-align:right; }
	.center { text-align:center; }
	.left { text-align:left; }
</style>

<? if(!get($conf, 'settings', 'themes_params')):// mpre("Параметры редактора тем не заданы") ?>
<? elseif(!$themes_params = rb("themes-params", "name", $w = "[Google Tag Manager]")):// mpre("Параметр не найден {$w}") ?>
<? elseif(!$themes_params_index = rb("themes-params_index", "params_id", "index_id", $themes_params['id'], $conf['themes']['index']['id'])):// mpre("Значение хоста не найдено") ?>
<? elseif(get($themes_params_index, 'hide')):// mpre("Отображение выключено {$w}") ?>
<? else:// mpre($themes_params_index) ?>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-PMNVT8K');</script>
	<!-- End Google Tag Manager -->
<? endif; ?>

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

<? if(!$themes_index = get($conf, 'themes', 'index')):// mpre("Ошибка расчета текущего хоста") ?>
<? elseif(!array_key_exists('callback', $themes_index)):// mpre("Параметр обратного вызова не задан в свойствах сайта") ?>
<? elseif(!$callback = get($themes_index, 'callback')): mpre("Форма обратной связи eyenewton.ru <a href='/themes:admin/r:themes-index?&where[id]={$themes_index['id']}'>не задана</a>") ?>
<? else: ?>
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
<? elseif(!$themes_params_index = rb("themes-params_index", "params_id", "index_id", $themes_params['id'], "[0,NULL,{$conf['themes']['index']['id']}]")): mpre("Значение параметра для сайта не найдено <a href='/themes:admin/r:themes-params_index?&where[params_id]={$themes_params['id']}&where[index_id]={$conf['themes']['index']['id']}'>{$themes_params['name']}</a>") ?>
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

<?// elseif((!$THEMES_YANDEX_METRIKA_GOAL_ANALYSIS = (get($conf, 'settings', 'themes_yandex_metrika_goal_analysis') ? rb("themes-yandex_metrika_goal_analysis", "id", "id", rb($THEMES_YANDEX_METRIKA_GOAL_METRIKA, "yandex_metrika_goal_analysis_id")) : [])) &0): mpre("Ошибка составления списка исследований для сайта") ?>
<?// elseif((!$THEMES_YANDEX_METRIKA_GOAL = (get($conf, 'settings', 'themes_yandex_metrika_goal') ? rb("themes-yandex_metrika_goal", "yandex_metrika_goal_analysis_id", "id", $THEMES_YANDEX_METRIKA_GOAL_ANALYSIS) : [])) &0): mpre("Цели яндекс метрики не найдены"); ?>
<?// elseif((!$THEMES_YANDEX_METRIKA_GOAL_ELEMENT = (get($conf, 'settings', 'themes_yandex_metrika_goal_element') ? rb("themes-yandex_metrika_goal_element", "yandex_metrika_goal_id", "id", $THEMES_YANDEX_METRIKA_GOAL) : [])) &0): mpre("Элементы событий не найдены") ?>
<? elseif(!is_array($THEMES_YANDEX_METRIKA_GOAL_METRIKA = get($conf, 'settings', 'themes_yandex_metrika_goal_metrika') ? rb("themes-yandex_metrika_goal_metrika", "index_id", "id", $themes_index['id']) : [])): mpre("Исследования для сайта не установлены") ?>
<? elseif(!is_array($THEMES_YANDEX_METRIKA_GOAL_GROUP = get($conf, 'settings', 'themes_yandex_metrika_goal_group') ? rb("themes-yandex_metrika_goal_group", "id", "id", rb($THEMES_YANDEX_METRIKA_GOAL_METRIKA, 'yandex_metrika_goal_group_id')) : [])): mpre("Ошибка выборки группы целей") ?>
<? elseif(!is_array($THEMES_YANDEX_METRIKA_GOAL = get($conf, 'settings', 'themes_yandex_metrika_goal') ? rb("themes-yandex_metrika_goal", "yandex_metrika_goal_group_id", "id", $THEMES_YANDEX_METRIKA_GOAL_GROUP) : [])): mpre("Ошибка выборки группы целей") ?>
<? else:// mpre($THEMES_YANDEX_METRIKA_GOAL) ?>
	<!-- Yandex.Metrika counter -->
		<? foreach($THEMES_YANDEX_METRIKA_INDEX as $themes_yandex_metrika_index): ?> 
			<? if(!$themes_yandex_metrika = rb($THEMES_YANDEX_METRIKA, "id", $themes_yandex_metrika_index['yandex_metrika_id'])): mpre("Метрика связанная с хостом не найдена") ?>
			<? elseif(!$mtid = (get($themes_yandex_metrika, 'mtid') ?: $themes_yandex_metrika['id'])): mpre("Ошибка нахождения номера счетчика") ?>
				<?// }, $_THEMES_YANDEX_METRIKA_GOAL)): mpre("Ошибка установки событий")*/ ?>
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
		<? endforeach; ?> 
	<!-- /Yandex.Metrika counter -->

	<? foreach($THEMES_YANDEX_METRIKA as $themes_yandex_metrika): ?>
		<? if(!is_numeric($mtid = (get($themes_yandex_metrika, 'mtid') ?: $themes_yandex_metrika['id']))): mpre("Ошибка определения кода счетчика") ?>
		<? else:// mpre($mtid) ?>
			<? foreach($THEMES_YANDEX_METRIKA_GOAL as $themes_yandex_metrika_goal): ?>
				<script sync>
					(function($, script){
						$(document).on("<?=$themes_yandex_metrika_goal['event']?>", "<?=$themes_yandex_metrika_goal['selector']?>", function(e){
							var counter = {};
							// var yaCounterXXXXXX = new Ya.Metrika({id: XXXXXX})  /*eval(cnt = "window.yaCounter<?=$mtid?>")*/
							if(!window.Ya){ console.error("Яндекс метрика `window.Ya` на сайте не установлена");
							}else if(!(counter = new Ya.Metrika({id:"<?=$mtid?>"}))){ console.error("Ошибка выборки счетчика метрики `"+ cnt+ "`");
							}else{// console.info("Событие "+goal.alias, counter.reachGoal(goal = "GET_FORM"));
								counter.reachGoal(alias = "<?=$themes_yandex_metrika_goal['alias']?>", {"selector":"<?=$themes_yandex_metrika_goal['selector']?>", "event":"<?=$themes_yandex_metrika_goal['event']?>"}, function(){
									console.info("Событие: Яндекс.счетчик", "<?=$mtid?>", "«<?=$themes_yandex_metrika_goal['name']?>»", alias, "Селектор:", "«<?=$themes_yandex_metrika_goal['selector']?>»", " событие:", "«<?=$themes_yandex_metrika_goal['event']?>»");
								});
							}
						}).one("goal", function(e){
							console.info("Исследование: Яндекс.счетчик", "<?=$mtid?>", "«<?=$themes_yandex_metrika_goal['name']?>»", "Яндекс событие:", "«<?=$themes_yandex_metrika_goal['alias']?>»", "Селектор:", "$('<?=$themes_yandex_metrika_goal['selector']?>')", "JS событие:", "«<?=$themes_yandex_metrika_goal['event']?>»", "Количество:", $("<?=$themes_yandex_metrika_goal['selector']?>").length);
						}).ready( function(e){ setTimeout(function(){ $(script).parent().trigger("goal");}, 1000); } )
					})(jQuery, document.currentScript)
				</script>
			<? endforeach; ?>
		<? endif; ?>
	<? endforeach; ?>
<? endif; ?>

<? if(!array_search("Администратор", $conf['user']['gid'])): mpre("Раздел предназначен только администраторам") ?>
<? elseif(($themes_index = get($conf, 'themes', 'index')) &&0):// mpre("Хост сайта не найден") ?>
<? elseif(($canonical = get($conf, 'settings', 'canonical')) &&0): mpre("Канонический адрес не задан") ?>
<? elseif(!$alias = seo_alias($canonical)): mpre("ОШИБКА получения алиаса категории адреса") ?>
<? elseif((!$seo_cat = rb("seo-cat", "id", get($canonical, 'cat_id'))) && (!$seo_cat = rb("seo-cat", "alias", (empty($alias) ? false : "[{$alias}]"))) &0): mpre("Категория не найдена") ?>
<?// elseif(!is_string($index = is_array($canonical) ? get($canonical, 'name') : $canonical)): mpre("ОШИБКА получения адреса страницы") ?>
<? elseif(!is_string($uri = call_user_func(function($canonical){
		if(is_array($canonical)){ return get($canonical, 'name');
		}elseif(is_string($canonical)){ return $canonical;
		}else{ return $_SERVER['REQUEST_URI']; }
	}, $canonical))): mpre("ОШИБКА расчета адреса страницы") ?>
<? elseif(!is_array($seo_location = ($uri ? rb("seo-location", "name", "[{$uri}]") : []))): mpre("ОШИБКА нахождения внутреннего адреса `{$index}`") ?>
<? elseif(!is_array($themes_index = get($conf, 'themes', 'index') ?: [])): mpre("ОШИБКА выборки хоста сайта") ?>
<? elseif(!is_array($seo_index_themes = (($themes_index && $seo_location) ? rb("seo-index_themes", "themes_index", "location_id", $themes_index['id'], $seo_location['id']) : []))): mpre("Адрес мультисайт режима не найден"); ?>
<? elseif(!is_array($seo_index = call_user_func(function($seo_location) use($conf, $themes_index, $seo_index_themes){
		if(!$seo_index_themes){ return $seo_index_themes;
		}elseif(!$themes_index = get($conf, 'themes', 'index')){// mpre("Односайтовый режим");
			if(!$seo_index = rb('seo-index', 'location_id', get($seo_location, 'id'))){ return []; mpre("ОШИБКА выборки внешнего адреса страницы");
			}else{ return $seo_index; }
		}elseif(!$seo_index = rb('seo-index', 'id', $seo_index_themes['index_id'])){ return []; mpre("Внешний адрес мультисайт режима не найден");
		}else{ return $seo_index; }
	}, $seo_location))): mpre("ОШИБКА нахождения внешнего адреса") ?>
<? else:// mpre($alias, $seo_cat) ?>
		<div class="themes_header_seo_blocks" style="z-index:999999; border:1px solid #eee; border-radius:7px; position:fixed; background-color:rgba(255,255,255,0.7); color:black; padding:0 5px; left:10px; top:10px; width:auto;">
			<div class="table">
				<div>
					<span><a href="/admin" title="Перейти в админраздел"><img src="/themes/theme:zhiraf/null/i/logo.png"></a></span>
					<span class="admin_links">
						<div title="Категория ссылки">
							<? if($name = get($seo_cat, 'name')): ?>
								<a href="/seo:admin/r:seo-cat?&where[id]=<?=get($seo_cat, 'id')?>"><?=$name?></a>
							<? else: ?>Категория не задана<? endif; ?>
						</div>
						<div class="admin_content" title="Информация о странице">
							<? if($seo_index_themes): ?>
								<a href="/seo:admin/r:seo-index_themes?&where[id]=<?=$seo_index_themes['id']?>"><?=$seo_location['name']?></a>
							<? elseif(!$seo_index): ?>
								<span><?=$uri?></span>
							<? else: ?>
								<a href="/seo:admin/r:seo-index?&where[id]=<?=$seo_index['id']?>"><?=$seo_location['name']?></a>
							<? endif; ?>
						</div>
					</span>
				</div>
			</div>
			<style>
				.pre {/*position:absolute;*/ z-index:999; background-color:white; border-radius:10px; padding:5px; opacity:0.8; border:3px double red; font-size:12px; color:gray;}
				.pre legend { color:black; font-size:100%; /*top: 13px;*/ position: relative; }
				.pre a.del { float:right; position:absolute; top:13px; right:7px; }
				.admin_links { line-height:22px; }
			</style>
			<script>
				(function($, script){
					$(script).parent().one("init", function(e){ // Перетаскивание админских элементов
						$.getScript("//code.jquery.com/ui/1.11.4/jquery-ui.js", function(){
							$(".themes_header_seo_blocks").draggable({handle:".table"}).css("position", "absolute").css(".table", "pointer");
							setTimeout(function(){ // Ожидаем загрузки всех элементов на страницу
								$("fieldset.pre").draggable({handle:"legend"}).css("position", "absolute").find("legend").css("cursor", "pointer");
							}, 1000);
						})
					}).ready(function(e){ $(script).parent().trigger("init"); })
				})(jQuery, document.currentScript)
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
				$("<button"+">").addClass("themes_scrolltop").text("<?=$themes_scrolltop?>").css({"position":"fixed", "top":"80%", "right":"3%", "display":"none"}).appendTo("body");
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
<? elseif(!$themes_params = rb("themes-params", "name", $p = "[Изменение стилей для шаблона]")):// mpre("Параметр не найден {$p}") ?>
<? elseif(!$THEMES_PARAMS_INDEX = rb("themes-params_index", "params_id", "index_id", "id", $themes_params['id'], $conf['themes']['index']['id'])):// mpre("Изменений стилей для данного сайта не требуется") ?>
<? else:// mpre($THEMES_PARAMS_INDEX) ?>
	<style>
		/* <? foreach(rb($THEMES_PARAMS_INDEX, "hide", "id", 0) as $themes_params_index): ?> */ 
			<?=$themes_params_index['selector']?> {<?=$themes_params_index['name']?>:<?=$themes_params_index['value']?>;/* content:"Код в заголовоке" */}
		/* <? endforeach; ?> */ 
	</style>
<? endif; ?>

<? /*if(!$themes_getScript = get($conf, 'settings', 'themes_getScript')):// mpre("Яваскрипт для загрузки не указан") ?>
<? elseif(!$COOKIE = array_diff_key($_COOKIE, array_flip(['roistat_phone_script_data']))): mpre("Ошибка формирования массива значений") ?>
<? elseif(!$themes_getScript = "{$themes_getScript}?"): mpre("Адрес скрипта плюс данные куки");// http_build_query($COOKIE) ?>
<? else:// mpre() ?>
	<script sync>
		(function($, script){
			$(script).parent().one("init", function(e){
				setTimeout(function(){
					$.post("<?=$themes_getScript?>", [], function(data){ // $.parseJSON('<?//=json_encode(["COOKIE"=>$COOKIE])?>')
					}, "script").done(function(data){// console.log("tracking:", data);
					}).fail(function(error){
						console.error("tracking:", error);
					});
				}, 1000)
			}).ready(function(e){ $(script).parent().trigger("init"); })
		})(jQuery, document.currentScript)
	</script>
<? endif;*/ ?>

<? if(!get($conf, 'settings', 'themes_params')):// mpre("Таблица параметров не найдена") ?>
<? elseif(!$themes_params = rb("themes-params", "name", $p = "[Hotjar Tracking Code]")):// mpre("Параметр не найден {$p}") ?>
<? elseif(!$themes_index = $conf['themes']['index']): mpre("Ошибка установки хоста сайта") ?>
<? elseif(!$THEMES_PARAMS_INDEX = rb("themes-params_index", "params_id", "index_id", "id", $themes_params['id'], "[{$themes_index['id']},0,NULL]")):// mpre("Изменений стилей для данного сайта не требуется") ?>
<? else:// mpre($themes_params) ?>
	<? foreach($THEMES_PARAMS_INDEX as $themes_params_index): ?>
		<!-- Hotjar Tracking Code for http://<?=$themes_index['name']?> -->
		<script>
			(function(h,o,t,j,a,r){
				h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
				h._hjSettings={hjid:"<?=$themes_params_index['name']?>",hjsv:5};
				a=o.getElementsByTagName('head')[0];
				r=o.createElement('script');r.async=1;
				r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
				a.appendChild(r);
			})(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
		</script>
	<? endforeach; ?>
<? endif; ?>

<? if(!get($conf, 'settings', 'seo_data')):# Таблица микроразметки не создана ?>
<? elseif(!$themes_index = $conf['themes']['index']): mpre("Хост не установлен") ?>
<? elseif(!is_array($seo_data = rb('seo-data', 'themes-index', $themes_index['id']))): mpre("Хост не найден") ?>
<? elseif(!is_array($seo_data_href = rb('seo-data_href', 'name', "[{$_SERVER['REQUEST_URI']}]"))): mpre("Ошибка выборки списка адресов сайта") ?>
<? elseif(!$SEO_DATA_VALUES = rb('seo-data_values', 'data_id', 'data_href_id', 'id', "[". get($seo_data, 'id'). ",0,NULL]", "[". get($seo_data_href, 'id'). ",0,NULL]")):// mpre("Ошибка выборки данных микроразметки") ?>
<?// elseif(mpre($SEO_DATA_VALUES)): ?>
<? elseif(!$SEO_DATA_TAG = rb('seo-data_tag', 'id', 'id', rb($SEO_DATA_VALUES, 'data_tag_id'))): mpre("Ошибка выборки тегов значений") ?>
<? elseif(!is_array($_SEO_DATA_TAG = array_filter(array_map(function($seo_data_tag){// mpre($seo_data_tag);
		return ($seo_data_tag['data_tag_id'] ? $seo_data_tag : null);
	}, $SEO_DATA_TAG)))): mpre("Ошибка получения вложенных элементов") ?>
<? elseif(!$json = call_user_func(function($SEO_DATA_VALUES) use($SEO_DATA_TAG, $_SEO_DATA_TAG){ # Массив ключей и значений
		if(!$SEO_DATA_TAG_[$n = 'Корневые'] = array_filter(array_map(function($seo_data_tag){
				return ($seo_data_tag['data_tag_id'] ? null : $seo_data_tag);
			}, $SEO_DATA_TAG))){ mpre("Ошибка формирования списка `{$n}`");
		}elseif(!$tags = array_column($SEO_DATA_TAG_['Корневые'], 'alias', 'id')){ mpre("Ошибка формирования списка тегов");
		}elseif(!$values = array_replace($tags, array_intersect_key(array_column($SEO_DATA_VALUES, 'name', 'data_tag_id'), $tags))){ mpre("Ошибка формирования списка значений");
		}elseif(!is_array($rep = call_user_func(function($SEO_DATA_TAG) use($SEO_DATA_VALUES){
				if(!$_SEO_DATA_TAG = rb($SEO_DATA_TAG, 'data_tag_id', 'id')){ return []; mpre("Ошибка получения тегов данных");
				}elseif(!is_array($_SEO_DATA_TAG = array_map(function($_DATA_TAG) use($SEO_DATA_VALUES){
						if(!$tags = array_column($_DATA_TAG, 'alias', 'id')){ mpre("Ошибка формирования списка тегов");
						}elseif(!is_array($default = array_filter(array_column($_DATA_TAG, 'value', 'id')))){ mpre("Ошибка выборки значений по умолчанию");
						}elseif(!$values = array_replace($tags, array_column($SEO_DATA_VALUES, 'name', 'data_tag_id'))){ mpre("Ошибка формирования списка значений");
						}elseif(!$value = array_replace($default, array_filter($values))){ mpre("Ошибка убирания лишних элементов");
						}elseif(!is_array($rep = array_combine(array_intersect_key($tags, $value), array_intersect_key($value, $tags)))){ mpre("Ошибка формирования json массива");
						}else{// mpre($rep);
							return $rep;
						}
					}, $_SEO_DATA_TAG))){ mpre("Ошибка установки значения в тегах");
				}else{ return $_SEO_DATA_TAG; }
			}, $_SEO_DATA_TAG))){ mpre("Ошибка формирования многоуровневой замены");
		}elseif(!$values = array_replace($values, $rep)){ mpre("Ошибка установки значений корневых элементов");
		}elseif(!$json = array_combine($tags, array_intersect_key($values, $tags))){ mpre("Ошибка формирования json массива");
		}else{// mpre($rep, $json);
			return $json;
		}
	}, $SEO_DATA_VALUES)): mpre("Ошибка формирования структуры тегов") ?>
<? elseif(($seo_data['hide'] === "0") && call_user_func(function($json) use($seo_data){
		mpre("Структура метаинформация отображается так как это включено в <a href='/seo:admin/r:seo-data?&where[id]={$seo_data['id']}'>свойствах сайта</a>", $json);
	}, $json)): mpre("Ошибка отображение структуры тегов") ?>
<? else:// mpre($json) ?>
	<script type="application/ld+json">
		<?=json_encode($json, JSON_UNESCAPED_UNICODE)?>
	</script>
<? endif; ?>

<!-- плайер -->
<? if(!get($conf, 'settings', 'themes_v6player')):# Таблица микроразметки не создана ?>
<? else:// mpre($seo_alias) ?>
	<script sync>
		(function($, script){
			$(script).parent().one("init", function(e){
				var height = $(e.delegateTarget).height();
				$(".content-section").css("height", "calc(100%-"+ height+ "px-40px)");
			}).ready(function(e){ $(script).parent().trigger("init"); })
		})(jQuery, document.currentScript)
	</script>

		<div id="v6alert" class="modal" tabindex="-1" role="dialog">
			<script>
				function v6alert(html, title){
					var v6alert = $('#v6alert');
					$(v6alert).find(".modal-body").html(html);
					$(v6alert).find(".modal-title").text(title ? title : "Уведомление");
					$(v6alert).modal('show');
				}
				function v6link(href){
//					$("<div>").css({"position":"fixed", "width":"1150px"}).load(href).appendTo("")
				}
			</script>
			<div class="modal-dialog modal-lg text-center" role="document" style="z-index:100000; top:120px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="min-height:95px;">
						<p>{text}</p>
					</div>
				</div>
			</div>
		</div>

	<script src="//cdn.006.spb.ru/private/player/video/player.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.006.spb.ru/private/player/video/player.css">
	<script sync>
		(function($, script){
			$(script).parent().on("video", function(e, src, params){// console.log("header", e.currentTarget);
				params = (typeof(params) == "undefined" ? {} : params);
				$("<div></div>").addClass("video_outer").css({"width":"100%", "height":"100%", "z-index":950})/*.hide()*/.appendTo("body");
				var div = $("<div></div>").addClass("videobox").hide().css({"border":"1px solid #bbb", "width":"60%", "min-height":"50%", "padding":"10px", "position":"fixed", "background-color":"white", "top":"10%", "left":"20%", "margin":"0 auto", "z-index":1000}).appendTo(".video_outer");

				$("<a></a>").addClass("fancybox-item").addClass("fancybox-close").appendTo(".videobox");
				$("<video>").attr("id", "videoPlayer").attr("src", "/"+src).attr("autoplay", true).appendTo(".videobox");
				
 				params.width = div.width();
				params.fullScreen = true;
//				params.combine = {src: 'линк на видео', startTime: '11:00', endTime: '41:40'};
				
				$(div).show().find('video').videoplayer(params, false);
//				var bg = $("<div>").addClass("bg").css({"position":"fixed", "left":0, "top":0, "background-color":"rgba(0, 0, 0, .7)", "width":"100%", "height":"100%", "z-index":999}).appendTo("body").find(".video_outer");
			}).on("click", "[video]", function(e){
				if(src = $(e.currentTarget).attr("video").replace("content/", "//erlyvideo.v6.spb.ru/BATTLE/slalomv1/")){
					var startTime = $(e.currentTarget).attr("startTime");
					var endTime = $(e.currentTarget).attr("endTime");
					var params = ((startTime && endTime) ? {startTime:startTime, endTime:endTime} : {});
					$(e.delegateTarget).trigger("video", [src, params]);
				}else{// v6alert("Видео не доступно"); /* Видео еще не добавлено */
				}
			}).on("dblclick", "[dblvideo]", function(e){
				if(src = $(e.currentTarget).attr("dblvideo").replace("content/", "//erlyvideo.v6.spb.ru/BATTLE/slalomv1/")){
					var startTime = $(e.currentTarget).attr("startTime");
					var endTime = $(e.currentTarget).attr("endTime");
					var params = ((startTime && endTime) ? {startTime:startTime, endTime:endTime} : {});
//					console.log("startTime:", startTime, "endTime:", endTime, "params:", params);
					$(e.delegateTarget).trigger("video", [src, params]);
				}else{// v6alert("Видео не доступно"); /* Видео еще не добавлено */
				}
			}).on("click", "a[load]", function(e){// alert("load");
				if(!(load = $(e.currentTarget).attr("load"))){ console.log("ОШИБКА элемент загрузки не установлен");
				}else if(!(href = $(e.currentTarget).attr("_href"))){ console.log("ОШИБКА адрес страницы загрузки не определен");
				}else if(!(html = $("<div>").load(href, function(request){
						var html = $(request).find(load).get(0).innerHTML;
						$(load).html(html);
						return false;
					}))){ console.log("ОШИБКА выборки содержимого указанного элемента", href, load);
				}else if(history.pushState(null, null, href)){ console.log("ОШИБКА установки адреса страницы в браузере "+ href);
				}else{ console.log("Загрузка", load, href);
					window.addEventListener('popstate', function(e){
						window.location.reload(true);
					});
//					e.stopPropagation();
				}
			}).on("click", ".fancybox-close, .bg", function(e){
				$(e.currentTarget).closest(".videobox", e.delegateTarget).remove();
				$(".video_outer").remove();
				$(".bg").remove();
			}).on("init", function(){
			}).ready(function(e){ $(script).parent().trigger("init"); })
		})(jQuery, document.currentScript)
	</script>
<? endif; ?>
