<? if($yandex = rb("yandex", "id", get($_GET, 'id'))): ?>
	<div class="themes yandex" yandex_id="<?=$yandex['id']?>">
		<style>
			.themes.yandex .active {background-color:#ddd;}
			.table > div:hover {background-color:#ddd;}
			.themes.yandex span.inc {color:green; font-weight:bold; display:none; }
			.themes.yandex span.inc:before { content:"+";  }
		</style>
		<script sync>
			(function($, script){
				$(script).parent().on("click", ".reg", function(e){
					var api = $(e.currentTarget).attr("api");
					var yandex_id = $(e.currentTarget).parents("[yandex_id]").attr("yandex_id");
					var index_id = $(e.currentTarget).parents("[index_id]").attr("index_id");
					$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/"+yandex_id+"/null", {index_id:index_id, api:api}, function(response){
						if(isNaN(response)){ alert(response) }else{
							document.location.reload(true);
						}
					})
					$(e.currentTarget).find("strong").unwrap();
				}).on("upgrade", function(e, data){
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
									var b = $(e.delegateTarget).find(xpath = "[index_id="+data.index_id+"] .index-count b").text()|0;
									if((cnt = count - b) != 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .index-count span.inc").text(cnt).show();
									}else{ console.log("b:", b, "index_count:", count); }
								}else{ console.error("stats", response); }

								if(count = response['internal-links-count']){
									var b = $(e.delegateTarget).find(xpath = "[index_id="+data.index_id+"] .internal-links-count b").text()|0;
									if((cnt = count - b) != 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .internal-links-count span.inc").text(cnt).show();
									}else{ console.log("b:", b, "internal-links-count:", count); }
								}else{ console.error("stats", response); }

								if(count = response['url-errors']){
									var b = $(e.delegateTarget).find(xpath = "[index_id="+data.index_id+"] .url-errors b").text()|0;
									if((cnt = count - b) != 0){
										$(e.delegateTarget).find("[index_id="+data.index_id+"] .url-errors span.inc").text(cnt).show();
									}else{ console.log("b:", b, "url-errors:", count); }
								}else{ console.error("stats", response); }
							} console.log(data, response);
						},
						error:function(response){
//							alert(response.responseText);
							console.error("error:", response.responseText);
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
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
		<span style="float:right;">
			<a class="upgrade" href="javascript:void(0)">Обновить</a>
			<a target="blank" href="https://oauth.yandex.ru/authorize?response_type=token&client_id=<?=$yandex['code']?>">Получить токен</a>
		</span>
		<h1><?=$yandex['name']?></h1>
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
				<?// foreach($tpl["yandex_webmaster"] as $yandex_webmaster): ?>
				<? foreach(rb("index") as $index): ?>
					<? ($yandex_webmaster = rb("yandex_webmaster", "index_id", $index['id'])) ?>
					<? ($yandex_metrika = rb("yandex_metrika", "index_id", $index['id'])) ?>
					<div index_id="<?=$index['id']?>">
						<span>
							<a href='/themes:admin/r:<?=$conf['db']['prefix']?>themes_index?&where[id]=<?=$index['id']?>'><?=$index['id']?></a>
						</span>
						<span class="name" style="color:<?=(get($index, 'index_id') ? "#ccc" : "black")?>;">
							<span class="status" style="float:right;"></span>
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
						<span><?=get($yandex_webmaster, 'url-count')?></span>
						<span class="index-count">
							<b><?=get($yandex_webmaster, 'index-count')?></b>
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
						<span class="url-errors">
							<b><?=get($yandex_webmaster, 'url-errors')?></b>
							<span class="inc">0</span>
						</span>
						<span class="internal-links-count">
							<b><?=get($yandex_webmaster, 'internal-links-count')?></b>
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
