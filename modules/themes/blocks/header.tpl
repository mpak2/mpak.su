<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

<style>
	div.table {display:table; width:100%; vertical-align:top;}
	div.table > div {display:table-row;}
	div.table > div > span {display:table-cell; padding:3px; vertical-align:top;}
	div.table > div.th > span {background-color:#444; color:white;}
	
	.pre {/*position:absolute;*/ z-index:999; background-color:white; border-radius:10px; padding:5px; opacity:0.8; border:3px double red; font-size:100%;}
	.pre legend { color:black; font-size:100%; top: 13px; position: relative; }
	
	.pager a.active {color:#fe8e23;}
</style>

<? if($livet = get($conf, 'themes', 'index', 'livet')): ?>
	<!-- {literal} -->
	<script type='text/javascript'>
		window['li'+'v'+'eT'+'e'+'x'] = true,
		window['live'+'TexI'+'D'] = <?=$livet?>,
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
	<? if($pozvonim = get($themes_index, 'pozvonim')): ?> 
		<script crossorigin="anonymous" async type="text/javascript" src="//api.pozvonim.com/widget/callback/v3/<?=$pozvonim?>/connect" id="check-code-pozvonim" charset="UTF-8"></script>
	<? endif; ?> 
	<? if($verification = get($themes_index, 'yandex_verification')): # Проверка вебмастера яндекса ?> 
		<meta name='yandex-verification' content='<?=$verification?>' />
	<? endif; ?> 
	<? if($verification = get($themes_index, 'google_verification')): # Проверка вебмастера гугл ?> 
		<meta name="google-site-verification" content="<?=$verification?>" />
	<? endif; ?> 
	<? if(get($themes_index, 'index_cat_id') && ($themes_cat = rb("{$conf['db']['prefix']}themes_index_cat", "id", $themes_index['index_cat_id']))): ?> 
		<? if($icon = get($themes_cat, "img")): # Фавикон ?> 
			<link rel="icon" type="image/png" href="/themes:img/<?=$themes_cat['id']?>/tn:index_cat/fn:img/w:65/h:65/null/img.png" />
		<? endif; ?> 
	<? endif; ?> 
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
		<? foreach(rb("{$conf['db']['prefix']}themes_yandex_metrika_index", "index_id", "id", get($conf, 'themes', 'index', 'id')) as $themes_yandex_metrika_index): ?> 
			<? if($themes_yandex_metrika = rb("{$conf['db']['prefix']}themes_yandex_metrika", "id", $themes_yandex_metrika_index['yandex_metrika_id'])): ?>
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
			<? if($tracking = get($themes_yandex_metrika_index, "tracking")): ?>
				<!-- PrimeGate CallTracking-->
					<script>
						setTimeout(function(){
							var headID = document.getElementsByTagName("head")[0]; 
							var newScript = document.createElement('script');
							newScript.type = 'text/javascript';
							newScript.src = 'http://js.primecontext.ru/primecontext.min.js';
							newScript.onload = function(){
								var id = PrimeContext.init("<?=$tracking?>", 0, '.calltr', 'span', false);
								ga('set', 'dimension1', id);
								ga('send', 'pageview');
							}; headID.appendChild(newScript);
						}, 100)
					</script>
				<!-- PrimeGate CallTracking-->
			<? endif; ?>
		<? endforeach; ?> 
	<!-- /Yandex.Metrika counter -->
<? endif; ?>

<? if(array_search("Администратор", $conf['user']['gid'])): ?> 
	<? if($themes_index = get($conf, 'themes', 'index')): ?>
		<script>
			(function($, script){
				$(script).parent().one("DOMNodeInserted", function(e){ // Ссылка на редактирование заголовка страницы
					$("<"+"div>").addClass("themes_header_seo_blocks").css({"z-index":999, opacity:0.3, cursor:"pointer", border:"1px solid gray", position:"fixed", background:"white", color:"black", padding:"0 5px", left:"10px", top:"10px"}).appendTo("body");
					if("object" == typeof(index = $.parseJSON(canonical = '<?=json_encode(get($conf, "settings", "canonical"))?>'))){// console.log("index", index);
						var themes_index = $.parseJSON('<?=json_encode($themes_index)?>');
						$(".themes_header_seo_blocks").on("click", function(e){
							window.open("/seo:admin/r:seo-index_themes?&where[location_id]="+index.id+"&where[themes_index]="+themes_index.id);
						}).text(index.name);
					}else{
						console.log("canonical:", canonical);
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
						}).on("click", function(e){
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
						}).text(canonical != "false" ? canonical : "Задать адрес");
					}
				}).one("DOMNodeInserted", function(e){ // Перетаскивание админских элементов
					$.getScript("//code.jquery.com/ui/1.11.4/jquery-ui.js", function(){
						$("fieldset.pre").draggable({handle:"legend"}).css("position", "absolute").find("legend").css("cursor", "pointer");
					})
				}).on("click", "fieldset.pre", function(e){
					console.log(e.type, "pre");
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
	<? endif; ?>
<? endif; ?>

<? if(get($conf, 'settings', 'themes_orders')): ?>
	<? inc("modules/themes/blocks/orders.tpl") ?>
<? endif; ?>
