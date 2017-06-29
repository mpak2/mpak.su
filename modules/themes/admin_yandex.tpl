<? if($yandex = rb("yandex", "id", get($_GET, 'id'))): ?>
	<div class="themes yandex" yandex_id="<?=$yandex['id']?>">
		<style>
			.themes.yandex .active {background-color:#ddd;}
			.table > div:hover {background-color:#ddd;}
			.themes.yandex span.inc, .themes.yandex span.dec{ font-weight:bold; display:none; }
			.themes.yandex span.inc { color:green; }
			.themes.yandex span.dec { color:red; }
			.themes.yandex span.inc:before { content:"+";  }
		</style>
		<script sync>
			(function($, script){
				$(script).parent().on("mouseover", "a.reg", function(e){
					if(e.ctrlKey){
						$(e.currentTarget).trigger("click");
					}
				}).on("click", ".reg", function(e){
					var api = $(e.currentTarget).attr("api");
					var yandex_id = $(e.currentTarget).parents("[yandex_id]").attr("yandex_id");
					var index_id = $(e.currentTarget).parents("[index_id]").attr("index_id");
					$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/"+yandex_id+"/null", {index_id:index_id, api:api}, function(response){
						$(e.currentTarget).find("strong").unwrap().css("color", "green");
					}, 'json').fail(function(response){
						$(e.currentTarget).find("strong").unwrap().css("color", "red");
//						alert(response.responseText)
						console.error(response);
					})
/*						if(isNaN(response)){
						}else{
//							document.location.reload(true);
						}*/
					$(e.currentTarget).find("strong").removeClass("reg").css("color", "black");
				}).on("upgrade", function(e, data){// console.log("upgrade", data);
					$.ajax({
						type: "POST",
						url: "/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$yandex['id']?>/null",
						data:data,// async: false,
						dataType: 'json',
						success : function(response){
							if((data.api == "tops") && (length = response.length)){
								console.log("tops:", length);
								$(e.delegateTarget).find("[index_id="+data.index_id+"] .tops span.inc").text(length).show();
							}else if(data.api == "stats"){
								if(count = response['index-count']){
									var b = $(e.delegateTarget).find(xpath = "[index_id="+data.index_id+"] .index_count b").text()|0;
									if((cnt = count - b) > 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .index_count span.inc").text(cnt).show();
									}else if(cnt < 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .index_count span.inc").text(cnt).removeClass("inc").addClass("dec").show();
									}else{ console.log("b:", b, "index_count:", count); }
								}else{ console.error("Количество индексов не задано", response); }

								if(count = response['internal-links-count']){
									var b = $(e.delegateTarget).find(xpath = "[index_id="+data.index_id+"] .internal_links_count b").text()|0;
									if((cnt = count - b) > 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .internal_links_count span.inc").text(cnt).show();
									}else if(cnt < 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .internal_links_count span.inc").text(cnt).removeClass("inc").addClass("dec").show();
									}else{ console.log("b:", b, "internal_links_count:", count); }
								}else{ console.error("Количество ссылок не задано", response); }

								if(count = response['url-errors']){
									var b = $(e.delegateTarget).find(xpath = "[index_id="+data.index_id+"] .url_errors b").text()|0;
									if((cnt = count - b) > 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .url_errors span.inc").text(cnt).show();
									}else if(cnt < 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .url_errors span.inc").text(cnt).removeClass("inc").addClass("dec").show();
									}else{ console.log("b:", b, "url_errors:", count); }
								}else{ console.error("Количество ошибок не задано", response); }
							} console.log(data, response);
						},
						error:function(response){
							console.log("response:", response);
//							console.log("response.responseText:", response.responseText);
							alert(response.responseText);
						}
					});
				}).on("click", "a.upgrade", function(e){
					(function(div){
						var nn = 0;
						var func = arguments;// console.log("div:", div, "finc:", func);

						if((next = $(div).next()).length){
							setTimeout(function(){
								func.callee(next);
							}, 4000)
						} var index_id = $(div).addClass("active").attr("index_id");
						$.each({tops:"Запросы", stats:"Статистика", indexed:"Индекс", texts:"Тексты"}, function(api, name){// , error:'Ошибки', index:'Индекс'
							setTimeout(function(){// console.log(api, name);
								$(e.delegateTarget).trigger("upgrade", {index_id:index_id, api:api});
								$(div).find(".name .status").text(name);
							}, nn++*1000)
						}); setTimeout(function(){
							$(div).removeClass("active").find(".name .status").text("");

							var top = $(div).offset().top;
							var scroll = $(document).scrollTop();
							var height = $(window).height();

							if((res = Math.abs(top-scroll-height/2)) < 100){
								$(document).scrollTop(top-height/2);
							}else{
//								console.log("res:", res, "top:", top, "scroll:", scroll, "height:", height);
							}
						}, nn*1000)
					})($(e.delegateTarget).find("[index_id]:first"));
				}).on("click", ".yandex_token a", function(e){
					var yandex_token_id = $(e.currentTarget).attr("yandex_token_id");
					var yandex_id = $(e.currentTarget).parents("[yandex_id]").attr("yandex_id");
					$(e.currentTarget).contents().unwrap().wrap("<strong>");
					$.ajax({
						type: "POST",
						url: "/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$yandex['id']?>/null",
						data:{api:"yandex_token", yandex_id:yandex_id, yandex_token_id:yandex_token_id},// async: false,
						dataType: 'json',
						success:function(response){
							console.log("response:", response);
//							$(e.currentTarget).css({color:"green"});
								document.location.reload(true);
						},
						dataType:'json',
						error:function(error){
							console.error(error.responseText);
						}
					})
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
		<span style="float:right;">
			<div>
				<span class="yandex_token">
					<? foreach(rb("yandex_token") as $yandex_token): ?>
						<a yandex_token_id="<?=$yandex_token['id']?>" href="javascript:" style="font-weight:<?=($yandex_token['id'] == $yandex['yandex_token_id'] ? "bold" : "inherid")?>;">
							<?=(get($yandex_token, 'login') ?: $yandex_token['name'])?>
						</a>
							<span title="Количество сайтов в вебмастере"><?=count(rb("yandex_webmaster", "yandex_token_id", "id", $yandex_token['id']))?></span> /
							<span title="Количество сайтов в метрике"><?=count(rb("yandex_metrika", "yandex_token_id", "id", $yandex_token['id']))?></span>
					<? endforeach; ?>
				</span>
				<a class="upgrade" href="javascript:void(0)">Обновить</a>
				<a target="blank" href="https://oauth.yandex.ru/authorize?response_type=token&force_confirm=yes&client_id=<?=$yandex['code']?>">Получить токен</a>
			</div>
		</span>
		<h1><?=$yandex['name']?></h1>
		<? if(get($conf, 'settings', 'themes_index_cat')): ?>
			<select>
				<? foreach(rb("index_cat") as $index_cat): ?>
					<option value="<?=$index_cat['id']?>"><?=$index_cat['name']?></option>
				<? endforeach; ?>
			</select>
		<? endif; ?>
		<? if($INDEX = rb("index", (get($_GET, 'limit') ?: 100), "index_cat_id", "id", (get($_GET, 'where', "index_cat_id") ?: true))): ?>
			<p><?=$tpl['pager']?></p>
			<div class="table">
				<div class="th">
					<span>Ид</span>
					<span>Имя</span>
					<span>ТИЦ</span>
					<span>Вебмастер</span>
					<span>Метрика</span>
					<span>Загружено</span>
					<span>Поиск</span>
					<span>Индекс</span>
					<span>Запр.</span>
					<span>Текст</span>
					<span>Ошиб.</span>
					<span>Внешн.</span>
					<span>вебм.</span>
					<span>метр.</span>
				</div>
				<? foreach($INDEX as $index): ?>
					<? ($yandex_webmaster = rb("yandex_webmaster", "index_id", $index['id'])) ?>
					<? ($yandex_metrika = rb("yandex_metrika", "index_id", $index['id'])) ?>
					<div index_id="<?=$index['id']?>">
						<span>
							<a href='/themes:admin/r:<?=$conf['db']['prefix']?>themes_index?&where[id]=<?=$index['id']?>'><?=$index['id']?></a>
						</span>
						<span class="name" style="position:relatite; color:<?=(get($index, 'index_id') ? "#ccc" : "black")?>;">
							<span class="status" style="position:absolute; right:0;"></span>
							<?=$index['name']?>
						</span>
						<span><?=get($yandex_webmaster, 'tcy')?></span>
						<span>
							<? if($yandex_webmaster): ?>
								<a target="blank" href="https://webmaster.yandex.ru/site/verification.xml?host=<?=$yandex_webmaster['id']?>"><?=$yandex_webmaster['id']?></a>
								<? if(($verification = get($yandex_webmaster, 'verification')) && ("VERIFIED" == $verification)): ?>
									<?=get($yandex_webmaster, 'crawling')?>
								<? else: ?>
									<a href="javascript:void(0)" class="reg" api="verify"><strong><?=$verification?></strong></a>
								<? endif; ?>
							<? endif; ?>
						</span>
						<span>
							<? if($yandex_metrika): ?>
								<a target="blank" href="https://metrika.yandex.ru/dashboard?id=<?=$yandex_metrika['id']?>"><?=$yandex_metrika['id']?></a>
								<?=$yandex_metrika['code_status']?>
							<? endif; ?>
						</span>
						<span><?=get($yandex_webmaster, 'url_count')?></span>
						<span class="index_count">
							<b><?=get($yandex_webmaster, 'index_count')?></b>
							<span class="inc">0</span>
						</span>
						<span>
							<? if($tpl['yandex_indexed'] = rb("yandex_indexed", "index_id", "id", $index['id'])): ?>
								<a href="/themes:admin/r:<?=$conf['db']['prefix']?>themes_yandex_indexed?&where[index_id]=<?=$index['id']?>"><?=count($tpl['yandex_indexed'])?></a>
							<? endif; ?>
						</span>
						<span class="tops">
							<a href="/themes:admin/r:<?=$conf['db']['prefix']?>themes_yandex_tops_index?&where[index_id]=<?=$index['id']?>">
								<?=(count(rb("yandex_tops_index", "index_id", "id", $index['id'])) ?: "")?>
							</a> <span class="inc">0</span>
						</span>
						<span>
							<? if($tpl['yandex_texts'] = rb("yandex_texts", "index_id", "id", $index['id'])): ?>
								<a href="/themes:admin/r:<?=$conf['db']['prefix']?>themes_yandex_texts?&where[index_id]=<?=$index['id']?>"><?=count($tpl['yandex_texts'])?></a>
							<? endif; ?>
						</span>
						<span class="url_errors">
							<b><?=get($yandex_webmaster, 'url_errors')?></b>
							<span class="inc">0</span>
						</span>
						<span class="internal_links_count">
							<b><?=get($yandex_webmaster, 'internal_links_count')?></b>
							<span class="inc">0</span>
						</span>
						<span>
							<? if(empty($yandex_webmaster) && !get($index, 'index_id')): ?>
								<a href="javascript:void(0)" class="reg" api="webmaster" href=""><strong>рег.</strong></a>
							<? endif; ?>
						</span>
						<span>
							<? if(empty($yandex_metrika) && !get($index, 'index_id')): ?>
								<a href="javascript:void(0)" class="reg" api="metrika" href=""><strong>рег.</strong></a>
							<? endif; ?>
						</span>
					</div>
				<? endforeach; ?>
			</div>
			<p><?=$tpl['pager']?></p>
		<? else: mpre("Список хостов пуст"); endif; ?>
	</div>
<? elseif(count($tpl["yandex"] = rb("yandex")) == 1): # Если одно приложение, сразу же на него перенаправляем ?>
	<? exit(header("Location: /{$arg['modname']}:{$arg['fn']}/". get(first($tpl["yandex"]), 'id'))) ?>
<? else: ?>
	<h1>Список приложений</h1>
	<ul>
		<? foreach($tpl["yandex"] as $yandex): ?>
			<li>
				<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$yandex['id']?>"><?=$yandex['name']?></a>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>
