<div class="admin_yandex_metrika_metrics">
	<style>
		.admin_yandex_metrika_metrics .table .table > div:hover {background-color:#eee;}
		.admin_yandex_metrika_metrics .table .table > div > span {text-align:center;}
		.admin_yandex_metrika_metrics .table .table > div > span.active {background-color:#eee;}
		.admin_yandex_metrika_metrics .table .table > div.active {background-color:#aaa;}
		.admin_yandex_metrika_metrics .table .table > div.active > span.active {background-color:#888;}
		.admin_yandex_metrika_metrics .table .table > div:hover > span.active:hover {background-color:#bbb;}
		.admin_yandex_metrika_metrics .table .changes {color:green; font-weight:bold;}
		.admin_yandex_metrika_metrics .table .th span span {color:#ccc;}
	</style>
	<script sync>
		(function($, script){
			$(script).parent().one("DOMNodeInserted", function(e){ // Загрузка родительского элемента
				
			}).on("click", "a,update", function(e){
				var yandex_metrika_id = $(e.currentTarget).parents("[yandex_metrika_id]").attr("yandex_metrika_id");
				var yandex_metrika_period_id = $(e.currentTarget).parents("[yandex_metrika_period_id]").attr("yandex_metrika_period_id");
				$(e.currentTarget).parents("[yandex_metrika_id]").addClass("active");

				var top = $(e.currentTarget).offset().top;
				var scroll = $(document).scrollTop();
				var height = $(window).height();

				if((res = Math.abs(top-scroll-height/2)) < 100){
					$(document).scrollTop(top-height/2);
				}else{ console.log("res:", res); }
				
					$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/null", {yandex_metrika_id:yandex_metrika_id, yandex_metrika_period_id:yandex_metrika_period_id}, function(data){
						var users = $(e.currentTarget).parents("[yandex_metrika_id]").find(".active .users span.count").text()|0;
						$(e.currentTarget).parents("[yandex_metrika_id]").find(".active .users span.count").text(users);
						var changes = (users == json['totals'][0]) ? "" : "+"+(parseInt(json['totals'][0]) - parseInt(users));
						$(e.currentTarget).parents("[yandex_metrika_id]").find(".active .users span.changes").text(changes);

						var visits = $(e.currentTarget).parents("[yandex_metrika_id]").find(".active .visits span.count").text()|0;
						$(e.currentTarget).parents("[yandex_metrika_id]").find(".active .visits span.count").text(visits);
						var changes = (visits == json['totals'][1]) ? "" : "+"+(parseInt(json['totals'][1]) - parseInt(visits));
						$(e.currentTarget).parents("[yandex_metrika_id]").find(".active .visits span.changes").text(changes);

						var pageviews = $(e.currentTarget).parents("[yandex_metrika_id]").find(".active .pageviews span.count").text()|0;
						$(e.currentTarget).parents("[yandex_metrika_id]").find(".active .pageviews span.count").text(pageviews);
						var changes = (pageviews == json['totals'][2]) ? "" : "+"+(parseInt(json['totals'][2]) - parseInt(pageviews));
						$(e.currentTarget).parents("[yandex_metrika_id]").find(".active .pageviews span.changes").text(changes);

						$(e.currentTarget).parents("[yandex_metrika_id]").removeClass("active");
					}, "json").fail(function(error){
						alert(error.responseText);
					})
			}).on("click", "a.upgrade", function(){
				(function(a){
					$(a).trigger("click");
					var func = arguments;
					if(next = $(a).parents("[yandex_metrika_id]").next().find("a.update")){
						setTimeout(function(){
							func.callee(next);
						}, 1000)
					}
				})($(".admin_yandex_metrika_metrics .table a.update:first"))
			})/*.on("click", ".table > div.th > span", function(e){
				var index = $(e.currentTarget).index();
				if(index > 0){
					$(e.delegateTarget).find(".table > div > span:nth-child("+(index+1)+")").hide();
				}
			})*/
		})(jQuery, document.scripts[document.scripts.length-1])
	</script>
	<div yandex_metrika_period_id="<?=$tpl['yandex_metrika_period']['id']?>">
		<span style="float:right; line-height:25px;">
			<? for($i=0; $i>-10; $i--): ?>
				<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?><?=($i ? "/week:{$i}" : "")?>" style="font-weight:<?=($i == get($_GET, "week") ? "bold" : "normal")?>;"><?=$i?></a>
			<? endfor; ?>
			<?=$tpl['yandex_metrika_period']['date1']?> - <?=$tpl['yandex_metrika_period']['date2']?>
			<a onClick="javascript:return false;" class="upgrade">Обновить все</a>
		</span>
		<?// if($tpl['yandex_metrika_index']) ?>
		<? if($tpl['yandex_metrika'] = rb("themes-yandex_metrika")): ?>
			<h1 style="margin-right:100px;">Метрики</h1>
			<? if($tpl['yandex_metrika_period:all'] = rb("themes-yandex_metrika_period", 6)): ?>
				<div class="table">
					<div>
						<span style="width:50%;">
							<div class="table">
								<div class="th">
									<span>Счетчик</span>
									<span>Сайт</span>
									<? foreach($tpl['yandex_metrika_period:all'] as $yandex_metrika_period): ?>
										<span><?=$yandex_metrika_period['date1']?></span>
									<? endforeach; ?>
								</div>
								<? foreach(rb("themes-index") as $index): ?>
									<? if($index['index_id']):// mpre("Сайт является зеркалом", $index) ?>
									<? elseif(preg_match("#\d+\.\d+\.\d+\.\d+#", $index['name'])):// mpre("Хост с адресом в имени", $index) ?>
									<? elseif("deny" == $index['theme']):// mpre("На сайте устанволена тема заглушка", $index) ?>
									<? elseif($yandex_metrika = rb("themes-yandex_metrika", "index_id", $index['id'])): ?>
										<? ($yandex_metrika_metrics = rb("themes-yandex_metrika_metrics", "yandex_metrika_period_id", "yandex_metrika_id", "yandex_metrika_dimensions_id", $tpl['yandex_metrika_period']['id'], $yandex_metrika['id'], 0)) ?>
										<div yandex_metrika_id="<?=$yandex_metrika['id']?>">
											<span>
												<a class="update" onClick="javascript:return false;" href="/themes:admin/r:mp_themes_yandex_metrika?&where[id]=<?=$yandex_metrika['id']?>"><?=$yandex_metrika['id']?></a>
											</span>
											<span>
												<? if($index = rb("themes-index", "id", $yandex_metrika['index_id'])): ?>
													<?=$index['name']?>
												<? endif; ?>
											</span>
											<? foreach($tpl['yandex_metrika_period:all'] as $yandex_metrika_period): ?>
												<span class="<?=($tpl['yandex_metrika_period']['id'] == $yandex_metrika_period['id'] ? "active" : "")?>">
													<? if($yandex_metrika_metrics = rb("themes-yandex_metrika_metrics", "yandex_metrika_period_id", "yandex_metrika_id", "yandex_metrika_dimensions_id", $yandex_metrika_period['id'], $yandex_metrika['id'], 0)): ?>
														<?// mpre("Значения метрики", $yandex_metrika_metrics) ?>
													<? endif; ?>
													<span class="users" title="Посетители">
														<span class="count"><?=get($yandex_metrika_metrics, 'users')?></span>
														<span class="changes"></span>
													</span> /
													<span class="visits" title="Визиты">
														<span class="count"><?=get($yandex_metrika_metrics, 'visits')?></span>
														<span class="changes"></span>
													</span> /
													<span class="pageviews" title="Просмотры">
														<span class="count"><?=get($yandex_metrika_metrics, 'pageviews')?></span>
														<span class="changes"></span>
													</span>
												</span>
											<? endforeach; ?>
										</div>
									<? endif; ?>
								<? endforeach; ?>
								<div class="th">
									<span></span>
									<span>По всем хостам:</span>
									<? foreach($tpl['yandex_metrika_period:all'] as $yandex_metrika_period): ?>
										<span>
											<? if($tpl['yandex_metrika_metrics'] = rb("themes-yandex_metrika_metrics", "yandex_metrika_period_id", "yandex_metrika_dimensions_id", "id", $yandex_metrika_period['id'], 0)): ?>
												<span title="Посетители"><?=array_sum(array_column($tpl['yandex_metrika_metrics'], "users"))?></span> /
												<span title="Визиты"><?=array_sum(array_column($tpl['yandex_metrika_metrics'], "visits"))?></span> /
												<span title="Просмотры"><?=array_sum(array_column($tpl['yandex_metrika_metrics'], "pageviews"))?></span>
											<? endif; ?>
										</span>
									<? endforeach; ?>
								</div>
							</div>
						</span>
					</div>
				</div>
			<? endif; ?>
		<? endif; ?>
	</div>
</div>
