<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> Из за неработаюго метода .load() пока не можем перейти (Используется при обработки form.load в перехвате загрузки) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

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

<? if(get($conf, 'settings', 'themes_yandex_metrika')): ?>
	<!-- Yandex.Metrika counter -->
		<? foreach(rb("themes-yandex_metrika_index", "index_id", "id", get($conf, 'themes', 'index', 'id')) as $themes_yandex_metrika_index): ?> 
			<? if($themes_yandex_metrika = rb("themes-yandex_metrika", "id", $themes_yandex_metrika_index['yandex_metrika_id'])): ?>
				<script sync type="text/javascript">
					/*<![CDATA[*/
					(function (d, w, c) {
						(w[c] = w[c] || []).push(function() {
							try {
								eval("w.yaCounter<?=get($themes_yandex_metrika, 'id')?> = new Ya.Metrika({id:<?=get($themes_yandex_metrika, 'id')?>, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true});");
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

<? if(!array_search("Администратор", $conf['user']['gid'])):// mpre("Раздел предназначен только администраторам") ?>
<? elseif(!$themes_index = get($conf, 'themes', 'index')):// mpre("Хост сайта не найден") ?>
<? elseif(($canonical = get($conf, 'settings', 'canonical')) &0): mpre("Канонический адрес не задан") ?>
<? elseif(($seo_cat = rb("seo-cat", "id", get($canonical, 'cat_id'))) &0): mpre("Категория не найдена") ?>
<? else:// mpre($canonical, $seo_cat) ?>
		<div class="themes_header_seo_blocks" style="z-index:9999; border:1px solid #eee; border-radius:7px; position:fixed; background-color:rgba(255,255,255,0.7); color:black; padding:0 5px; left:10px; top:10px; width:auto;">
			<div class="table">
				<div>
					<span><a href="/admin" title="Перейти в админраздел"><img src="/themes/theme:zhiraf/null/i/logo.png"></a></span>
					<span>
						<div title="Категория ссылки"><a href="/seo:admin/r:seo-cat?&where[id]=<?=get($seo_cat, 'id')?>"><?=get($seo_cat, 'name')?></a></div>
						<div class="admin_content" title="Информация о странице"></div>
					</span>
				</div>
			</div>
			<script>
				$(function(){// Ссылка на редактирование заголовка страницы
					if("object" == typeof(index = $.parseJSON(canonical = '<?=json_encode($canonical)?>'))){// console.log("index", index);
						var themes_index = $.parseJSON('<?=json_encode($themes_index)?>');
						$(".themes_header_seo_blocks").on("click", ".admin_content", function(e){
							window.open("/seo:admin/r:seo-index_themes?&where[location_id]="+index.id+"&where[themes_index]="+themes_index.id);
						}).find(".admin_content").css("cursor", "pointer").text(index.name);
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
<? elseif(!$themes_params_index = rb("themes-params_index", "params_id", "index_id", $themes_params['id'], $w = "[". get($conf, 'themes', 'index', 'id'). ",0,NULL]")): mpre("Значение параметра для сайта не найдено {$p}", $w) ?>
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
