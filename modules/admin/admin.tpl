<ul class="nl tabs">
	<style>
		ul.tabs li ul {position:absolute;z-index:2; display:none;}
		ul.tabs li:hover ul {
			display: block;
			width: inherit;
			border: 1px solid #DBDBDD;
			border-top: none;
			-webkit-box-shadow: 0px 14px 16px 0px rgba(50, 50, 50, 0.45);
			-moz-box-shadow: 0px 14px 16px 0px rgba(50, 50, 50, 0.45);
			box-shadow: 0px 14px 16px 0px rgba(50, 50, 50, 0.45);
		}
		ul.tabs li:hover ul li {background-color:white; z-index:999;}
		#content .tabs li.subact > a { background-color:#eaeaea;}
		/*#content .tabs li.subact a { background-color:#dedede; }*/
		ul.tabs > li.sub > a:after {content:"↵";};
	</style>
	<? foreach(get($tpl, 'menu') ?: array() as $k=>$ar): ?>
		<? if(!$r = get($tpl, 'tables', $k)): mpre("ОШИБКА получения полного имени таблицы") ?>
		<? //elseif(!$tab = substr($r, strlen($conf['db']['prefix']))): mpre("ОШИБКА расчета имени таблицы") ?>
		<? elseif(!$tab = strpos($r, '.') ? $r : substr($r, strlen($conf['db']['prefix']))): mpre("ОШИБКА получения короткого имени таблицы") ?>
		<? elseif(!is_string($tb = (strpos($r, '.') ? $tab : substr($tab, strlen("{$arg['modpath']}_"))))): //mpre("ОШИБКА получения короткого имени таблицы `{$r}`", gettype($tb)) ?>
		<? elseif(!$tabl = strpos($r, '.') ? $tab : "{$arg['modpath']}-{$tb}"): mpre("ОШИБКА получения имени таблицы в адресе") ?>
		<? elseif(!$href = "/{$arg['modpath']}:{$arg['fn']}/r:{$tabl}"): mpre("ОШИБКА формирования адреса перехода") ?>
		<? elseif(!$name = (get($conf, 'settings', $tab) ?: $tb)): mpre("ОШИБКА формирования имени вкладки") ?>
		<? elseif(!is_array($tables = array_intersect_key($tpl['tables'], array_flip($ar)))): mpre("ОШИБКА выборки списка нижестоящих таблиц") ?>
		<? elseif(!is_string($act = (($_GET['r'] == $r) ? "act" : ""))): mpre("ОШИБКА определения класса активности вкладки") ?>
		<? elseif(!is_string($subact = ((array_search(get($_GET, 'r'), $tables)) ? "subact" : ""))): mpre("ОШИБКА формирования класса активности вложенных таблиц") ?>
		<? elseif(!is_string($sub = ($tables ? "sub" : ""))): mpre("ОШИБКА определения класса вложенных таблиц") ?>
		<? else:// mpre($tab) ?>
			<li class="<?=$r?> <?=$act?> <?=$subact?> <?=$sub?>">
				<a href="<?=$href?>"><?=$name?></a>
				<ul>
					<? foreach($ar as $n=>$v): ?>
						<? if(!$r = $tpl['tables'][$v]): mpre("ОШИБКА получения полного имени вложенной таблицы") ?>
						<? //elseif(!$tab = substr($r, strlen($conf['db']['prefix']))): mpre("ОШИБКА формирования имени таблицы") ?>
						<? elseif(!$tab = strpos($r, '.') ? $r : substr($r, strlen($conf['db']['prefix']))): mpre("ОШИБКА получения короткого имени таблицы") ?>
						<? //elseif(!is_string($tb = (substr($r, strlen("{$conf['db']['prefix']}{$arg['modpath']}_")) ?: ""))): mpre("ОШИБКА получения короткого имени таблицы `{$r}`", gettype($tb)) ?>
						<? elseif(!is_string($tb = (strpos($r, '.') ? $tab : substr($tab, strlen("{$arg['modpath']}_"))))): mpre("ОШИБКА получения короткого имени таблицы `{$r}`", gettype($tb)) ?>
						<? elseif(!$tabl = strpos($r, '.') ? $tab : "{$arg['modpath']}-{$tb}"): mpre("ОШИБКА получения имени таблицы в адресе") ?>
						<? elseif(!$href = "/{$arg['modpath']}:{$arg['fn']}/r:{$tabl}"): mpre("ОШИБКА формирования адреса перехода") ?>
						<? elseif(!$name = (get($conf, 'settings', $tab) ?: $tb)): mpre("ОШИБКА формирования имени вкладки") ?>
						<? elseif(!is_string($subact = (($_GET['r'] == $r) ? "subact" : ""))): mpre("ОШИБКА определения класса активности вкладки") ?>
						<? else:// mpre($tb) ?>
							<li class="<?=$r?> <?=$subact?>" style="display:block; min-width:120px;">
								<a href="<?=$href?>"><?=$name?></a>
							</li>
						<? endif; ?>
					<? endforeach; ?>
				</ul>
			</li>
		<? endif; ?>
	<? endforeach; ?>
</ul>
<? if(!$table = get($_GET, 'r')): //mpre("Не указано имя таблицы") ?>
<? elseif(array_search($table, $tpl['tables']) === false): //mpre("Таблица не найдена в списке") ?>
<? else: ?>
	<style>
		.line_links ul.admin li {display:inline-block;}
		.line_links ul.admin li:before{ content:"• "; }
		.line_links li div.settings {
			display:none;
			position:absolute; 
			width:300px;
			right:-20px;
			text-align:left; 
			min-height:50px;
			padding-top: 5px;
			-webkit-box-shadow: 0px 10px 10px 0px rgba(0,0,0,0.1);
			-moz-box-shadow: 0px 10px 10px 0px rgba(0,0,0,0.1);
			box-shadow: 0px 10px 10px 0px rgba(0,0,0,0.1);
		}
		.line_links li:hover div.settings {display:block;}
		.line_links li div.settings .content{
			padding:10px; 
			border:1px solid #eee; 
			border-top:0;
			background-color:white; 
		}
		.line_links .settings ul li {display:list-item;}
		.line_links .settings ul li:before {content:none;}
		.line_links .settings .table>div>span:last-child {text-align:right;overflow: hidden;width: 183px;display: inline-block;}
	</style>
	<div class="table line_links">
		<div>
			<? if(get($tpl, 'title') && !get($_GET, "edit")): ?>
				<span style="width:60px; padding-left:20px;vertical-align:middle;">
					<? if(!is_string($tb = (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")) ?: ""))): mpre("ОШИБКА получения короткого имени таблицы `{$r}`", gettype($tb)) ?>
					<? elseif(!is_array($href_where = array_map(function($key){ return "where[{$key}]=". get($_GET, 'where', $key); }, array_keys(get($_GET, 'where') ?: [])))): mpre("ОШИБКА получения параметров условий фильтра") ?>
					<? elseif(!is_string($page = (get($_GET, 'p') ? "/p:{$_GET['p']}" : ""))): mpre("ОШИБКА формирования ссылки на страницу") ?>
					<? elseif(!is_bool($_edit = (get($_GET, 'where', 'id') ? true : false))): mpre("ОШИБКА получения флага редактирования") ?>
					<? elseif(!$href = "/{$arg['modpath']}:{$arg['fn']}/r:{$arg['modpath']}-{$tb}{$page}?edit". ($_edit ? "={$_GET['where']['id']}" : ""). "&". implode("&", $href_where)): mpre("ОШИБКА формирования ссылки на добавление") ?>
					<? elseif(!$name = ($_edit ? "Править" : "Добавить")): mpre("ОШИБКА выбора заговка кнопки") ?>
					<? else:// mpre($href) ?>
						<a href="<?=$href?>">
							<button type="button"><?=$name?></button>
						</a>
					<? endif; ?>
				</span>
			<? endif; ?>
			<? if(!get($tpl, 'edit')): ?>
				<span style="width:430px;"><?=$tpl['pager']?></span>
			<? endif; ?>
			<span style="padding-right:20px; text-align:right; overflow:visible; white-space:normal;">
				<? if(!$tb = implode("_", array_slice(explode("_", $_GET['r']), 1))): mpre("ОШИБКА формирования короткого имени таблицы") ?>
				<? elseif(!is_array($list = mpreaddir("/modules/{$arg['modpath']}", 1))): mpre("ОШИБКА получения списка файлов раздела") ?>
				<? elseif(!is_array($list = array_map(function($f){ return first(explode('.', $f)); }, $list))): mpre("ОШИБКА избавления от расширений") ?>
				<? elseif(!is_array($list = array_unique($list))): mpre("ОШИБКА удаления уникальных адресов") ?>
				<?// elseif(!$list = array_unique(array_map(function($f){ return first(explode('.', $f)); }, $mpreaddir))): mpre("ОШИБКА получения списка админстраниц") ?>
				<? else:// mpre($list) ?>
					<ul class="admin">
						<? foreach($list as $f):// mpre($n, $f) ?>
							<? if(strpos($f, "admin_") === false):// mpre("Имя файла не админ_") ?>
							<? elseif(!$fl = implode('_', array_slice(explode('_', $f), 1))): mpre("Ошибка формирования алиаса файла") ?>
							<? elseif(!($ft = implode('_', array_slice(explode('_', $tb), 1))) &0): mpre("Ошибка формирования внутреннего имени таблицы") ?>
							<? elseif(!$ft || (strpos($fl, $ft) !== 0)):// mpre("Имя не соответствует формату страницы") ?>
							<? elseif(!$href = "/{$arg['modpath']}:{$f}". (($id = get($_GET, 'where', 'id')) ? "/{$id}" : ""). ""): mpre("Ошибка формирования адреса страницы") ?>
							<?// elseif(!$dir = mpopendir($od = $fl)): mpre("Имя файла в файловой системе не найдено {$od}") ?>
							<? elseif(!($title = mpopendir("modules/{$arg['modpath']}/{$f}.tpl")) && !($title = mpopendir("modules/{$arg['modpath']}/{$f}.php"))): mpre("Имя файла в файловой системе не найдено"); ?>
							<? elseif(!$bold = (strpos($title, "phar://") === 0 ? "inherit" : "bold")): mpre("Ошибка вычисления толщины текста"); ?>
							<? elseif(!$af = "{$arg['modpath']}_{$f}"): mpre("ОШИБКА расчета короткого имени таблицы") ?>
							<? elseif(!is_string($st = (get($conf, 'settings', $af) ?: ""))): mpre("ОШИБКА получения имени страницы") ?>
							<? //elseif(!is_string($name = (($st = get($conf, 'settings', $af)) ?: $af))): mpre("Имя страница в свойствах раздела не установлено") ?>
							<? elseif(!$name = call_user_func(function($af) use($conf, $fl, $arg){
									if($name = get($conf, 'settings', $af)){ return $name;
									}elseif(!$_af = "{$arg['modpath']}_{$fl}"){ mpre("ОШИБКА формирования имени параметра таблицы");
									}elseif($name = get($conf, 'settings', $_af)){ return $name;
									}else{ return $af; }
								}, $af)): mpre("ОШИБКА получения имени вкладки") ?>
							<? elseif(!$color = ($st ? "gray" : "#bbb")): mpre("ОШИБКА получения цвета админссылки") ?>
							<? else:// mpre($st, $af, $fl) ?>
								<li title="<?=$title?>"><a href="<?=$href?>" style="font-weight:<?=$bold?>; color:<?=$color?>;"><?=$name?></a></li>
							<? endif; ?>
						<? endforeach; ?>
						<li><b><a href="/sql:admin_sql/r:<?=$_GET['r']?>">БД</a></b></li>
						<li class="settings" style="position:relative; z-index:10;">
							<div class="settings">
								<h2>Свойства</h2>
								<div class="content">
									<h4>Свойства таблицы</h4>
									<div class="table" style="width:100%;">
										<? foreach(["{$arg['modpath']}"=>["Список"=>"=>espisok", "Исключения"=>"_tpl_exceptions"], "{$tb}"=>["Название"=>"", "Заголовки"=>"=>title", "Сортировка"=>"=>order", "Счетчик"=>"=>ecounter"]] as $prx=>$params): ?>
											<? foreach($params as $name=>$uri): ?>
												<div>
													<span style="font-weight:<?=($prx == $arg['modpath'] ? "bold" : "inherit")?>;"><?=$name?></span>
													<span>
														<a target="blank" href="/settings:admin/r:mp_settings/?&where[modpath]=<?=$arg['modpath']?>&where[name]=<?=($st = $prx. $uri)?>">
															<?=(get($conf, 'settings', $st) ?: "Нет")?>
														</a>
													</span>
												</div>
											<? endforeach; ?>
										<? endforeach; ?>
									</div>
								</div>
							</div>
							<b style="z-index:10;"><a href="/settings:admin/r:<?=$conf['db']['prefix']?>settings?&where[modpath]=<?=$arg['modpath']?>">СВ</a></b>
						</li>
					</ul>
				<? endif; ?>
			</span>
		</div>
	</div>
<? endif; ?>
<div class="lines">
	<div class="inner">
		<? if(!$table = call_user_func(function() use($arg, $conf){
				if(!$table = get($_GET, 'r')){ mpre("Таблица не указана");
				}else if(strpos("-", $table)){ mpre("Короткий адрес таблицы");
				}else if(!$tab = implode("-", array_slice(explode("-", $table), 1))){ //mpre("ОШИБКА получения короткого имени таблицы");
				}else if(!$table = "{$conf["db"]["prefix"]}{$arg["modpath"]}_{$tab}"){ mpre("ОШИБКА составления полного имени таблицы");
				}else{ //mpre($_GET, $table, $tab);
				} return $table;
			})): mpre("Не указано имя таблицы для создания <a href='/{$arg['modpath']}:admin/r:{$arg['modpath']}-index'>Создать</a>"); ?>
		<? elseif(call_user_func(function() use($table, $conf, $tpl, $arg){ // Отображение кнопки добавить таблицу
				if(array_search($table, $tpl['tables']) !== false){ //mpre("Таблица уже создана");
				}else if($conf['modules']['sql']['admin_access'] <= 4){ mpre("Права доступа недостаточные для создания таблицы");
				}else{ ?>
					<div style="margin:10px;">
						<script>
							(function($, script){
								$(script).parent().on("click", "button.table", function(e){
									if(value = prompt("Название таблицы")){
										$.post("/sql:admin_sql/null", {table:"<?=$table?>"}, function(data){
											$.post("/settings:admin/r:mp_settings/null", {modpath:"<?=$arg['modpath']?>", name:"<?=substr($table, strlen($conf['db']['prefix']))?>", value:value, aid:4}, function(data){
												console.log("post:", data);
												document.location.reload(true);
											});
										}, "json").fail(function(error){
											console.log("error:", error);
											alert(error.responseText);
										});
									}
								})
							})(jQuery, document.scripts[document.scripts.length-1])
						</script> Таблица не найдена
						<button class="table">Создать</button>
					</div>
				<? }
			})): mpre("ОШИБКА отображения кнопки добавить таблицу") ?>
		<? else: ?>
			<style>
				.lines, .lines .inner {
					-moz-transform: scaleY(-1); /* Переворачивает элемент со скролом (чтобы поставить его сверху) */
					-webkit-transform: scaleY(-1); /* Переворачиваем внутренний элемент */
				}
				.lines { position:relative; }
				.lines .inner { /*position:absolute;*/ bottom:0; width:100%;}

				.table > .th > span:first-child,
				.table [line_id] >span:first-child{
						background-color:#fff;
						position: absolute;
						height: 18px;
						border-right:1px solid #eaeaea;
						position:relative;
						z-index: 2;
				}
				.table [line_id]:hover >span:first-child{background-color: #f4f4f4;}
				.table [line_id].active >span:first-child{background-color: #d4d4d4;}
				.table > div > span: {    background: #fff;border-right: 1px solid #efefef;}
				.table > div > span {border-collapse:collapse; padding:5px; vertical-align:middle;}
				.table > div > span:first-child {width:70px;}
				.table > div:hover {background-color:#f4f4f4;}
				.table > div.active {background-color:#d4d4d4;}
				.table > div >span:hover {background-color:#eee;}
				.lines .pager {margin:10px;}
				.lines .table div.th > span {font-weight:bold; background:url(i/gradients.gif) repeat-x 0 -57px; border-top: 1px solid #dbdbdd; border-bottom: 1px solid #dbdbdd; line-height: 27px; white-space:nowrap; color:gray; }
				.table .info {background-color:blue; color:white; border-radius:8px;   padding: 0 4px; width:12px; height:12px; text-align:center; cursor:pointer; font-weight:bolder;}
				.table input[type="text"], .table select {width:100%;}
				.table textarea {width:100%; height:150px;}
				.table a.edit {display:inline-block; background:url(i/mgif.gif); background-position:0 0; width:16px; height:16px;}
				.table .control a.del {display:inline-block; background:url(i/mgif.gif); background-position:0 -56px; width:16px; height:16px;}
				.table a.key {display:inline-block; background:url(i/mgif.gif); background-position:-2px -155px; width:16px; height:16px; opacity:0.3}
				.table a.ekey {display:inline-block; background:url(i/mgif.gif); background-position:-20px -155px; width:16px; height:16px; opacity:0.3}
				.table .th.top {position:absolute; z-index:10; top:0;}
				.info_comm{color: #a5a5a5;}
			</style>
			<script>
				$(function(){					
					var ch = $('input[type=checkbox][name="id"]');	
					var lastChecked = null;
					ch.on("click",function(e) {
						console.log(lastChecked);
						if(!lastChecked) {
							lastChecked = this;
							return;
						}
						if(e.shiftKey) {
							var start = ch.index(this);
							var end = ch.index(lastChecked);
							ch.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
						}
						lastChecked = this;
						console.log(lastChecked);
					});
				});
				(function($, script){
					$('#content >.lines').on('scroll', function () {console.log(44);
						$('.table > .th > span:first-child,.table [line_id] >span:first-child').css('left', $(this).scrollLeft());
					});
					
					$(script).parent().on("click", ".control a.del", function(del){
						var checkbox = $(del.currentTarget).parents("[line_id]").find("input[type=checkbox]");
						var checked = $(checkbox).is(":checked");
						console.log("checkbox:", checkbox, "checked:", checked);
						if(del.ctrlKey || checked || confirm("Удалить элемент?")){
							if(th = $(del.currentTarget).parents('.th').length){// alert("Масс удаление");
								var CHECKBOX = $(del.delegateTarget).find("[line_id] input[type=checkbox]:checked");
								$(del.currentTarget).parents(".th").find("input[type=checkbox]").prop("checked", false);
								$(CHECKBOX).each(function(n, checkbox){
									$(checkbox).parents("[line_id]").find(".control a.del").trigger("click", true);
								})
								console.log("CHECKBOX:", CHECKBOX);
							}else{
								var line_id = $(del.currentTarget).parents("[line_id]").attr("line_id");
								$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/"+line_id+"/null", {id:-line_id}, function(json){
									if(!$(del.currentTarget).is("[line_id]")){ alert('Запись с идентификатором не найдена');
									}else{// console.log("line_id", $(del.currentTarget));
										$(del.currentTarget).remove();
									}
								}, 'json').fail(function(error){
									alert(error.responseText);
								})
							}
						}
					}).on("dblclick", ".th input[type=checkbox]", function(e){
						var prev = "";
						var url = new URL(document.location.href);
						var unique = (url.searchParams.get("unique") ? url.searchParams.get("unique") : "name");
						$(e.currentTarget).parents(".lines").find('input[type=checkbox][name="id"]').each(function(n, checkbox){
							if(!(name = $(checkbox).parents("[line_id]").find("[field="+unique+"]").text())){ mpre("ОШИБКА получения имени текущего значения");
							}else if(name == prev){ console.log("Значения равны", name, prev);
							}else if(!(prev = name)){ console.error("ОШИБКА установки нового предыдущего значения");
							}else{
//								$(checkbox).parents("[line_id]").find("[field="+unique+"]").css("color", "gray");
								$(checkbox).prop("checked", ($(checkbox).prop("checked") ? false : true));
							}
						})
					}).on("click", ".th input[type=checkbox]", function(e){
						$(e.currentTarget).next().show();
						var checked = $(e.currentTarget).is(":checked");
						$(e.currentTarget).parents(".lines").find('input[type=checkbox][name="id"]').each(function(n, checkbox){
							$(checkbox).prop('checked', e.shiftKey ? checked : !$(checkbox).is(":checked")).show();
						})
					}).on("invert", function(e, request, one, two){
						if($(two).length == 0){
							document.location.reload(true);
						}else{
							var one_id = $(one).attr("line_id");
							var two_id = $(two).attr("line_id");
							$(one).find(".sort span").text(request[one_id].sort);
							$(two).find(".sort span").text(request[two_id].sort);

							$(one).after(after = $("<div>after</div>"));

							$(one).insertBefore(two).promise().done(function(){
								$(one).css("background-color", "#f4f4f4").promise().done(function(){
									setTimeout(function(){
										$(one).css("background-color", "inherit");
									}, 500);
								});
								$(two).insertBefore(after).promise().done(function(){
									$(two).css("background-color", "#f4f4f4").promise().done(function(){
										setTimeout(function(){
											$(two).css("background-color", "inherit");
										}, 500); $(after).remove();
									});
								});
							});
						}
					}).on("click", "a.inc", function(e){
						var line_id = $(e.currentTarget).parents("[line_id]").attr("line_id");
						$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/null?<? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>", (e.ctrlKey ? {first:line_id} : {inc:line_id}), function(request){
								console.log("request:", request);
								var one = $(e.currentTarget).parents("[line_id]");
								var two = $(one).prev("[line_id]");

								if(e.ctrlKey){
									document.location.reload(true);
								}else{ $(e.delegateTarget).trigger("invert", [request, one, two]); }
						}, "json").fail(function(error) {
							console.error("error:", error);
							alert(error.responseText);
						});
					}).on("click", "a.dec", function(e){
						var line_id = $(e.currentTarget).parents("[line_id]").attr("line_id");
						$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/null?<? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>", (e.ctrlKey ? {last:line_id} : {dec:line_id}), function(request){
								console.log("request:", request);
								var one = $(e.currentTarget).parents("[line_id]");
								var two = $(one).next("[line_id]");

								if(e.ctrlKey){
									document.location.reload(true);
								}else{ $(e.delegateTarget).trigger("invert", [request, one, two]); }
						}, "json").fail(function(error) {
							console.error("error:", error);
							alert(error.responseText);
						});
					}).on("click", "select", function(e){
						if(e.altKey){
							if($(e.currentTarget).is("[multiple]")){
								$(e.currentTarget).removeAttr("multiple");
								$(e.currentTarget).css({height:"", position:"inherit", top:""});
							}else{
								var name = $(e.currentTarget).attr("name");
								$(e.currentTarget).data("name", name);
								$(e.currentTarget).attr("name", name+"[]");

								var width = $(e.currentTarget).parent().width();
								$(e.currentTarget).parent().css("width", width+"px");
								$(e.currentTarget).attr("multiple", "multiple");
								$(e.currentTarget).parent().css({position:"relative"});
								var width = $(e.currentTarget).width();
								$(e.currentTarget).css({height:"200px", position:"absolute", top:"7px", width:width+"px"});
							}
						}// console.log("is:", $(e.currentTarget).is("[multiple]"), $(e));
					}).on('click', '[line_id]', function(e){
						$(e.currentTarget).toggleClass('active');
					}).on("click", "a[href_where]", function(e){
						if(!e.metaKey){// console.log("Мета клавиша отжата");
						}else if(!(href_where = $(e.currentTarget).attr("href_where"))){ console.log("ОШИБКА получения условной ссылки");
						}else if(!($(e.currentTarget).attr("href", href_where))){ console.log("ОШИБКА установки адреса условного перехода");
						}else{// console.log("where_href", href_where);
							return true;
						}
//						alert(href_where);
					}).one("init", function(e){
						setTimeout(function(){
							var th = $(e.delegateTarget).find(".table .th");
							$(th).parent().css("position", "relative");
							$(th).find(">span").each(function(n, span){
								var width = $(span).width();
								$(span).css("width", width+"px");
							})
							
							$(window).on("resize", function(e){
								$(document).trigger("scroll");
							}).on("scroll", document, function(e){
								var scroll = $(th).css({"width":"100%", "left":0}).offset().top;
								var top = $(e.delegateTarget).scrollTop();
								console.log("scroll:", scroll, "top:", top);
								if(top > scroll){
									$(".th.top").css("top", top-scroll)
									if(!$(".th.top").length){
										console.log("addClass:top");
										$(th).clone().addClass("top").appendTo($(th).parent());
									}
								}else if($(".th.top").length && (top < scroll)){ console.log("removeClass:top");
									$(".th.top").remove();
								}
							})
						}, 1000)
					}).ready(function(e){ 
						$(script).parent().trigger("init"); 
					});
				})(jQuery, document.currentScript)
			</script>
			<form action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?><?=(get($_GET, "edit") ? "/{$_GET['edit']}" : "")?>/null" method="post" enctype="multipart/form-data">
				<script sync>
					(function($, script){
						$(script).parent().one("init", function(e){
							var forms = $(e.delegateTarget).attr("target", "response_"+(timeStamp = e.timeStamp));
							$("<"+"iframe>").css("display", "none").attr("name", "response_"+timeStamp).appendTo(forms).on("load", function(e){
								var data = $(this).contents().find("body").html();

								try{if(json = JSON.parse(data)){
									console.log("json:", json);
									var button = $(e.delegateTarget).find("button[type=submit]:focus");
									if("Дублировать" == $(button).text()){
										document.location.href = '<?="/{$arg["modpath"]}:admin/r:{$_GET["r"]}". (get($_GET, "p") ? "/p:{$_GET["p"]}" : ""). "?&edit="?>'+ json.id+ '<?=(get($_GET, "where") ? "&". implode("&", array_map(function($key, $val){ return "where[{$key}]={$val}"; }, array_keys($where = $_GET["where"]), $where)) : "")?><?=(($limit = get($_GET, "limit")) ? "/limit:{$limit}" : "")?>';
									}else{
										document.location.href = '<?=$tpl['href']?>';
									}
								}}catch(e){if(isNaN(data)){ alert(data) }else{
									console.log(data);
								}}
							}).hide();
						}).ready(function(e){ $(script).parent().trigger("init"); })
					})(jQuery, document.currentScript)
				</script>
				
				<div class="table">
					<? if(!is_array($edit = (get($_GET, 'edit') ? rb($_GET['r'], "id", get($_GET, 'edit')) : []))): mpre("ОШИБКА выборки редактируемого элемента") ?>
					<? elseif(!is_string($disabled = ((get($_GET, 'edit') == get($edit, 'id')) ? "" : "disabled"))): mpre("Расчет неактивности элементов") ?>
					<? elseif(get($tpl, 'title') && array_key_exists("edit", $_GET) && ($edit || !get($_GET, 'edit'))):// mpre($disabled) ?>
						<div class="th">
							<span style="width:15%; text-align:right;">Поле</span>
							<span>Значение</span>
						</div>
						<? foreach($tpl['fields'] as $name=>$field):// mpre($name, $field) ?>
							<div>
								<span style="text-align:right;">
									<? if($comment = get($field, 'Comment')): ?>
										<span class="info" title="<?=$comment?>">?</span>
									<? endif; ?>
									<? if($etitle = get($tpl, 'etitle', $name)): ?>
										<span title="<?=$name?>"><?=$etitle?></span>
									<? elseif(substr($name, -3) == "_id"): ?>
										<span title="<?=$name?>"><?=(get($conf, 'settings', "{$arg['modpath']}_". substr($name, 0, -3)) ?: substr($name, 0, -3))?></span>
									<? else: ?>
										<?=htmlspecialchars($name)?>
									<? endif; ?>
								</span>
								<span>
									<? if($name == "id"): # Вертикальное отображение ?>
										<? if(!is_string($tb = (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")) ?: ""))): mpre("ОШИБКА получения короткого имени таблицы") ?>
										<? elseif(!is_string($where = (get($_GET,'where', 'id')) ? "&where[id]={$_GET['where']['id']}" : "")): mpre("Ошибка формирования ключа параметра") ?>
										<? elseif(!$href = "/{$arg['modpath']}:{$arg['fn']}/r:{$arg['modpath']}-{$tb}?{$where}"): mpre("ОШИБКА получения адреса редактирования записи") ?>
										<? elseif(get($_GET, 'where', "id")): ?>
											<a href="<?=$href?>"><?=$_GET['where']['id']?></a>
										<? else: ?>
										<span class="<?=$disabled?>">Номер записи назначаеся ситемой</span>
										<? endif; ?>
									<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#^img(\d*|_.+)?#iu",$name)): ?>
										<input type="file" name="<?=$name?>[]" multiple="true" <?=$disabled?>>
										<span class="info_comm">
											<a href="/<?=$arg['modpath']?>:img/<?=get($tpl, 'lines', get($tpl, 'edit', "id"))['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:<?=$name?>/w:109/h:109/null/img.png" target="_blank"><?=get($tpl,'edit',$name);?></a>
										</span>
									<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#^file(\d*|_.+)?#iu",$name)): ?>
										<input type="file" name="<?=$name?>[]" multiple="true" <?=$disabled?>>
										<span class="info_comm"><?=get($tpl,'edit',$name);?></span>
									<? elseif($name == "hide"): ?>
										<select name="hide" <?=$disabled?>>
											<? foreach(get($tpl, 'spisok', 'hide') as $k=>$v): ?>
												<option value="<?=$k?>" <?=((!get($tpl, 'edit') && (get($field, 'Default') == $k)) || ($k == get($tpl, 'edit', 'hide')) ? "selected" : "")?>><?=$v?></option>
											<? endforeach; ?>
										</select>
									<? elseif($name == "uid"): ?>
										<select name="<?=$name?>" <?=$disabled?>>
											<option value="NULL"></option>
											<? foreach(rb("{$conf['db']['prefix']}users") as $uid): ?>
												<option value="<?=$uid['id']?>" <?=((get($tpl, 'edit', $name) == $uid['id']) || (!get($tpl, "edit") && ($conf['user']['uid'] == $uid['id'])) || (get($_GET, 'where', 'uid') && $_GET['where']['uid'] == $uid['id']) ? "selected" : "")?>><?=$uid['name']?></option>
											<? endforeach; ?>
										</select>
									<? elseif($name == "gid"): ?>
										<select name="<?=$name?>" <?=$disabled?>>
											<option value="NULL"></option>
											<? foreach(rb("{$conf['db']['prefix']}users_grp") as $gid): ?>
												<option value="<?=$gid['id']?>" <?=((get($tpl, 'edit', $name) == $gid['id']) || (!get($tpl, "edit") && ($conf['user']['uid'] == $uid['id'])) ? "selected" : "")?>><?=$uid['name']?></option>
											<? endforeach; ?>
										</select>
									<? elseif($name == "mid"): ?>
										<select name="<?=$name?>" <?=$disabled?>>
											<option value="NULL"></option>
											<? foreach(rb("{$conf['db']['prefix']}modules_index") as $modules): ?>
												<option value="<?=$mid['id']?>" <?=((get($tpl, 'edit', $name) == $modules['id']) || (!get($tpl, "edit") && ($conf['user']['uid'] == $modules['id'])) ? "selected" : "")?>><?=$modules['name']?></option>
											<? endforeach; ?>
										</select>
									<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#(^|.+_)(time|last_time|reg_time|up|down)(\d+|_.+|$)#ui",$name,$match)): # Поле времени ?>									
										<? if(!is_numeric($time = call_user_func(function($name){// mpre(123);
												if(!array_key_exists('edit', $_GET)){ return 0;// mpre("Нет edit");
												}elseif($index = rb($_GET['r'], "id", $_GET['edit'])){ return (int)get($index, $name);// mpre("index");
												}else{ return time(); }
											}, $name))): mpre("ОШИБКА расчета текущего времени") ?>
										<? else: ?>
											<input type="text" name="<?=$name?>" value="<?=date("Y-m-d H:i:s", $time)?>" <?=$disabled?> placeholder="<?=($tpl['etitle'][get($match,2)] ?: $name)?>">
										<? endif; ?>
									<? elseif((substr($name, -3) == "_id") && (false === array_search(substr($name, 0, -3), explode(",", get($conf, 'settings', "{$arg['modpath']}_tpl_exceptions") ?: "")))): # Поле вторичного ключа связанной таблицы ?>
										<? if(!get($conf, 'settings', 'admin_datalist')): ?>
											<select name="<?=$name?>" style="width:100%;">
												<? if(strlen(get($tpl, 'edit', $name)) && !rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($name, 0, -3), "id", $tpl['edit'][$name])): ?> 
													<option><?=htmlspecialchars($tpl['edit'][$name])?></option>
												<? endif; ?> 
												<option value="NULL"></option>
												<? foreach(rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($name, 0, -3)) as $ln): ?> 
													<option value="<?=$ln['id']?>" <?=((get($tpl, 'edit', $name) == $ln['id']) || (!get($tpl, 'edit') && ($ln['id'] == (get($_GET, 'where', $name) ?: get($field, 'Default')))) ? "selected" : "")?>>
														<?=$ln['id']?>&nbsp;<?=$ln['name']?>
													</option>
												<? endforeach; ?> 
											</select>
										<? elseif(!$tab = substr($name, 0, -3)): mpre("ОШИБКА определения имени таблицы списка") ?>
										<? elseif(!is_array($LIST = rb("{$arg['modpath']}-{$tab}"))): mpre("ОШИБКА выборки списка для поля") ?>
										<? elseif((!$list_id = (get($edit, $name) ?: get($_GET, 'where', $name))) && !is_numeric($list_id) && !is_string($list_id) && !is_null($list_id)): mpre("ОШИБКА определения номера списка `{$name}`", get($tpl, 'edit'), $name, gettype($list_id)) ?>
										<? elseif(!is_array($list = rb($LIST, "id", $list_id))): mpre("ОШИБКА выборки связанной таблицы") ?>
										<? elseif((!$list_value = (get($list, 'name') ?: get($list, 'id'))) && !is_numeric($list_value) && !is_string($list_value) && !is_null($list_value)): mpre("ОШИБКА определения занчения списка", gettype($list_value)) ?>
										<? else:// mpre($edit, $name, $list) ?>
											<input type="text" name="<?=$name?>" value="<?=($list ? htmlspecialchars($list_value) : ($list_id ?: get($edit, $name)))?>" <?=$disabled?> list="<?=$name?>_list" style="background-color:#ddd;">
											<datalist id="<?=$name?>_list">
												<? foreach($LIST as $list): ?>
													<option value="<?=htmlspecialchars(array_key_exists('name', $list) ? get($list, 'name') : $list_id)?>"><?=$list['id']?></option>
												<? endforeach; ?>
											</datalist>
										<? endif; ?>
									<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#(^|.+_)(text)(\d+|_.+|$)#iu",$name)): ?>
										<? if(!$disabled): ?>
											<?=mpwysiwyg($name, get($tpl, 'edit', $name) ?: "")?>
										<? else: ?>
											<div style="width:100%; height:200px; border:1px solid #ccc;"><?=get($edit, $name)?></div>
										<? endif; ?>
									<?// elseif($tpl_espisok = get($tpl, 'espisok', $name)): ?>
									<?// elseif(array_key_exists($name, $tpl['espisok'])): ?>
									<? elseif(get($tpl, 'espisok') && array_key_exists($name, $tpl['espisok'])): ?>
										<select name="<?=$name?>" <?=$disabled?>>
											<? if(!get($tpl, 'edit', $name)):// mpre("Значение не задано") ?>
											<? elseif($val = get('espisok', $name, get($tpl, 'edit', $name))):// mpre("Значение найдено") ?>
												<option value="<?=$value?>"><?=$value?></option>
											<? else:// mpre("Значение не найдено") ?>
												<option value="<?=get($tpl, 'edit', $name)?>"><?=get($tpl, 'edit', $name)?></option>
											<? endif; ?>
											<option value="NULL"></option>
											<? foreach($tpl['espisok'][$name] as $espisok): ?>
												<option value="<?=$espisok['id']?>" <?=(((!get($tpl, 'edit') && (get($field, 'Default') == $espisok['id'])) || ($espisok['id'] == get($tpl, 'edit', $name)) || (array_key_exists('edit', $_GET) && (get($_GET, 'where', $name) == $espisok['id']))) ? "selected" : "")?>><?=$espisok['id']?> <?=get($espisok, 'name')?></option>
											<? endforeach; ?>
										</select>
									<? else: # Обычное текстовове поле. Если не одно условие не сработало ?>
										<? if(!is_string($value = call_user_func(function($edit) use($name, $field){
												if($edit && ($value = get($edit, $name))){ return $value;
												}elseif($value = get($field, 'Default')){ return $value;
												}elseif($value = get($_GET, 'where', $name)){ return $value;
												}else{// mpre(get($_GET, 'where', $name));
													return "";
												}
											}, get($tpl, 'edit')))): mpre("ОШИБКА расчета значения поля") ?>
										<? else: ?>
											<input type="text" name="<?=$name?>" value="<?=htmlspecialchars($value)?>" <?=$disabled?> placeholder="<?=(get($tpl, 'etitle', $name) ?: $name)?>">
										<? endif; ?>
									<? endif; ?>
								</span>
							</div>
						<? endforeach; ?>
						<div>
							<span></span>
							<span>
								<? if(!$disabled): ?>
									<button type="submit">Сохранить</button>
									<button type="submit" name="_id" value="NULL">Дублировать</button>
								<? else: ?>
									<!-- <a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=get($_GET, 'r')?>?&edit=<?=get($_GET, 'where', 'id')?>&where[id]=<?=get($_GET, 'where', 'id')?>">Редактировать</a> -->
								<? endif; ?>
							</span>
						</div>
					<? elseif(!$fields = get($tpl, 'fields')): mpre("ОШИБКА не указаны поля таблицы <b>{$table}</b>") ?>
					<? elseif(!$FIELDS = array_merge((array_key_exists('title', $tpl) ? array_intersect_key($tpl['fields'], array_flip($tpl['title'])) : $tpl['fields']), (get($tpl, 'counter') ?: array()), (get($tpl, 'ecounter') ?: array()))): mpre("ОШИБКА составления списка полей таблицы") ?>
					<? else: //mpre($tpl['fields']); // mpre($_GET, $tpl['edit']); # Горизонтальное отображение ?>
						<div class="th">
							<? foreach($FIELDS as $name=>$field):// mpre($name, $field) ?>
								<span>
									<? if(!is_array($href_where = array_map(function($key){ return "where[{$key}]=". get($_GET, 'where', $key); }, array_keys(get($_GET, 'where') ?: [])))): mpre("ОШИБКА получения параметров условий фильтра") ?>
									<? elseif(!$sort = "&order=". (get($_GET, "order") == $name ? "{$name}%20DESC" : $name )): mpre("ОШИБКА получения строки сортировки") ?>
									<? elseif(!$href_sort = "/{$arg['modpath']}:{$arg['fn']}/r:{$_GET['r']}?". ($href_where ? "&". implode("&", $href_where) : ""). $sort): mpre("ОШИБКА получения строки условий") ?>
									<? elseif(get($field, 'Comment')): ?>
										<span class="info" title="<?=$field['Comment']?>">?</span>
									<? endif; ?>

									<? if(substr($name, 0, 2) == "__"): ?>
										<span title="<?=substr($name, 2)?>">_<?=(get($conf, 'settings', substr($name, 2)) ?: substr($name, 2))?></span>
									<? elseif(substr($name, 0, 1) == "_"): ?>
										<span title="<?=substr($name, 1)?>"><?=(get($conf, 'settings', "{$arg['modpath']}_". substr($name, 1)) ?: substr($name, 1))?></span>
									<? elseif(substr($name, -3) == "_id"): ?>
										<a href="<?=$href_sort?>" title="<?=$name?>"><?=(get($conf, 'settings', "{$arg['modpath']}_". substr($name, 0, -3)) ?: substr($name, 0, -3))?></a>
									<? elseif(get($tpl, 'etitle')): ?>
										<a href="<?=$href_sort?>" title="<?=$name?>">
											<?=(get($tpl, 'etitle', $name) ?: $name)?>
										</a>
										<? if("id" == $name): ?>
											<span class="control">
												<input type="checkbox" title="Стандартно - инверсия; Shift - Повторение">
												<a class="del" href="javascript:" style="display:none;"></a>
											</span>
										<? endif; ?>
									<? else: ?>
										<a href="<?=$href_sort?>" title="<?=$name?>"><?=$name?></a>
									<? endif; ?>
								</span>
							<? endforeach; ?>
						</div>
						<? if(!get($_GET, "edit")): ?>
							<? foreach($tpl['lines'] as $lines): ?>
								<div line_id="<?=$lines['id']?>">
									<? foreach(array_merge((array_key_exists('title', $tpl) ? array_intersect_key($lines, array_flip($tpl['title'])) : $lines), get($tpl, 'counter') ?: array(), get($tpl, 'ecounter') ?: array()) as $k=>$v): ?>
										<span field="<?=$k?>">
											<? if(!$tb = implode("_", array_filter(explode("_", $k)))): mpre("ОШИБКА получения короткого имени таблицы") ?>
											<?// elseif(true): mpre($tb) ?>
											<? elseif(substr($k, 0, 2) == "__"): // $tpl['ecounter'] ?>
												<? if(!$tab = substr($k, 2)): mpre("Ошибка поиска названия таблицы в адресе") ?>
												<? elseif(!$m = first(explode('_', first(explode("-", $tab))))): mpre("Ошибка определения модуля таблицы для подсчета") ?>
												<? elseif(!$field = call_user_func(function($tab, $table) use($conf){
														if(!$FIELDS = fields($tab)){ mpre("Ошибка получения списка полей удаленной таблицы");
														}elseif(!$curtab = substr($table, strlen($conf['db']['prefix']))){ mpre("Ошибка формирования короткого имени текущей таблицы");
														}elseif(!$curex = explode('_', $curtab, 2)){ mpre("Ошибка получения составных частей текущей таблицы");
														}elseif(!$fields1 = "{$curex[0]}-{$curex[1]}"){ mpre("Ошибка формирования стандартного имени поля");
														}elseif(array_key_exists($fields1, $FIELDS)){ return $fields1; mpre("В таблица прописан стандартное название поля");
														}elseif(!$fields2 = "{$curex[0]}_{$curex[1]}"){ mpre("Ошибка формирования дополнительного имени поля");
														}elseif(array_key_exists($fields2, $FIELDS)){ return $fields2; mpre("В таблица используется дополнительное название поля");
														}else{ mpre("Свзанное поле счетчика [{$fields1},{$fields2}] с текущей таблицей `{$curtab}` в удаленной таблице `{$tab}` не найдено", $FIELDS); }
													}, $tab, $_GET['r'])): mpre("Ошибка нахождения имени поля") ?>
												<? elseif(!$href = "/{$m}:admin/r:{$arg['modpath']}-{$tb}?&where[{$field}]={$lines['id']}"): mpre("Ошибка генерации ссылки на связанную таблицу") ?>
												<? else:// mpre($tab) ?>
													<a href="<?=$href?>">
														<?=(($cnt = get($v, $lines['id'], 'cnt')) ? "{$cnt}&nbspшт" : "Нет")?>
													</a>
												<? endif; ?>
											<? elseif(substr($k, 0, 1) == "_"):// mpre($tab) ?>
												<a href="/<?=$arg['modpath']?>:admin/r:<?="{$arg['modpath']}-{$tb}?&where[". (($_GET['r'] == "{$conf['db']['prefix']}users") && ($k == "_mem") ? "uid" : substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={$lines['id']}"?>">
													<?=(($cnt = get($tpl, 'counter', $k, $lines['id'])) ? "{$cnt}&nbspшт" : "Нет")?>
												</a>
											<? elseif($k == "id"): ?>
												<? if(!is_string($tb = (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")) ?: ""))): mpre("ОШИБКА получения короткого имени таблицы") ?>
												<? elseif(!is_array($href_where = array_filter(array_map(function($key){ return ($key == "id" ? null : "where[{$key}]=". get($_GET, 'where', $key)); }, array_keys(get($_GET, 'where') ?: []))))): mpre("ОШИБКА получения параметров условий фильтра") ?>
												<? elseif(!$href = "/{$arg['modpath']}:{$arg['fn']}/r:{$arg['modpath']}-{$tb}?edit&where[id]={$v}"): mpre("ОШИБКА формирования адреса фильтра по записи") ?>
												<? elseif(!is_string($order = (get($_GET, 'order') ? "&order={$_GET['order']}" : ""))): mpre("ОШИБКА получения ссылки сортировки") ?>
												<? elseif(!is_string($p = (get($_GET, 'p') ? "&p={$_GET['p']}" : ""))): mpre("ОШИБКА получения ссылки на страницу") ?>
												<? elseif(!$href_edit = "/{$arg['modpath']}:{$arg['fn']}/r:{$_GET['r']}?&edit={$v}". ($href_where ? "&" : ""). implode("&", $href_where). "{$order}{$p}"): /* &where[id]={$v} */ mpre("ОШИБКА формирования ссылки для редактирования") ?>
												<? else:// mpre($href_edit) ?>
													<span class="control" style="white-space:nowrap;">
														<a class="del" href="javascript:"></a>
														<a class="edit" href="<?=$href_edit?>"></a>
														<a href="<?=$href?>"><?=$v?></a>
														<input type="checkbox" name="id" value="<?=$v?>" style="display:none;">
													</span>
												<? endif; ?>
											<? elseif(!preg_match("#_id$#ui",$k) AND preg_match("#^img(\d*|_.+)?#iu",$k)): ?>
												<div class="imgs" fn="<?=$k?>" style="position:relative; height:14px;">
													<script sync>
														(function($, script){
															$(script).parent().on("click", ".del", function(e){
																$.ajax({
																	type: 'POST',
																	url: "/telegram:admin/r:<?=$_GET['r']?>/<?=$lines["id"]?>/null",
																	data: {"img":""},
																	//dataType: 'json',
																}).done(function(json){
																	document.location.reload(true);
																}).fail(function(error){
																	alert(error.responseText);
																}); return false;
															}).ready(function(e){ $(script).parent().trigger("init"); })
														})(jQuery, document.currentScript)
													</script>
													<a class="del imgs <?=($lines[$k] ? "" : "disabled")?>" href="javascript:void(0)" title="Удалить изображение"><img src="/img/del.png"></a>
													<? if(!$small = "/{$arg['modpath']}:img/{$lines['id']}/tn:". substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "/fn:{$k}". ($lines[$k] ? "" : "/rand:". time()). "/w:109/h:109/null/img.png"): mpre("Ошибка формирования маленького изображения") ?>
													<? elseif(!$lines[$k]):// mpre("Нет изображения") ?>
														<img class="minPreview" src="<?=$small?>"  title="<?=$v?>">
													<? elseif(!$big = "/{$arg['modpath']}:img/{$lines['id']}/tn:". substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "/fn:{$k}". ($lines[$k] ? "" : "/rand:". time()). "/w:600/h:800/null/img.png"): mpre("Ошибка формирования большого изображения") ?>
													<? else: ?>
														<a target="blank" href="<?=$big?>">
															<img class="minPreview" src="<?=$small?>"  title="<?=$v?>">
														</a>
													<? endif; ?>
												</div>
											<? elseif(!preg_match("#_id$#ui",$k) AND preg_match("#^file(\d*|_.+)?#iu",$k)): ?>
												<div class="imgs" fn="<?=$k?>" style="position:relative; height:14px;">
													<a class="del <?=($lines[$k]?"":"disabled")?>" href="javascript:void(0)" title="Удалить файл"><img src="/img/del.png"></a>
													<a target="blank" href="/<?=$arg['modpath']?>:file/<?=$lines['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:file/null/<?=basename($lines[$k])?>" title="<?=$v?>">
														<?=$v?>
													</a>
												</div>
											<? elseif($k == "sort"): ?>
												<div class="sort" style="white-space:nowrap;">
													<a class="inc" href="javascript:void(0);" style="background:url(i/mgif.gif); background-position:-18px -90px; width: 10px; height: 14px; display:inline-block;"></a>
													<span><?=$v?></span>
													<a class="dec" href="javascript:void(0);" style="background:url(i/mgif.gif); background-position:0 -90px; width: 10px; height: 14px; display:inline-block;"></a>
												</div>
											<? elseif($k == "hide"): ?>											
												<span class="<?=($v?'info_comm':"");?>">
													<?=(get($tpl, 'spisok', 'hide', $v) ?: "")?>
												</span>
											<? elseif($k == "uid"): ?>
												<span style="white-space:nowrap;">
													<? if($uid = rb("{$conf['db']['prefix']}users", "id", (int)$v)): ?>
														<a class="key" href="/users:admin/r:<?=$conf['db']['prefix']?>users?&where[id]=<?=(int)$v?>" title="<?=$v?>"></a><?=$uid['name']?>
													<? elseif($v): ?>
														<span style="color:red;" title="<?=$v?>"><?=$v?></span>
													<? endif; ?>
												</span>
											<? elseif($k == "gid"): ?>
												<span style="white-space:nowrap;">
													<? if($gid = rb("{$conf['db']['prefix']}users_grp", "id", (int)$v)): ?>
														<a class="key" href="/users:admin/r:<?=$conf['db']['prefix']?>users?&where[id]=<?=(int)$v?>" title="<?=$v?>"></a><?=$gid['name']?>
													<? elseif($v): ?>
														<span style="color:red;" title="<?=$v?>"><?=$v?></span>
													<? endif; ?>
												</span>
											<? elseif($k == "mid"): ?>
												<span style="white-space:nowrap;">
													<? if($mid = rb("{$conf['db']['prefix']}modules_index", "id", (int)$v)): ?>
														<a class="key" href="/users:admin/r:<?=$conf['db']['prefix']?>users?&where[id]=<?=(int)$v?>" title="<?=$v?>"></a><?=$mid['name']?>
													<? elseif($v): ?>
														<span style="color:red;" title="<?=$v?>"><?=$v?></span>
													<? endif; ?>
												</span>
											<? elseif(!preg_match("#_id$#ui",$k) AND preg_match("#(^|.+_)(time|last_time|reg_time|up|down)(\d+|_.+|$)#ui",$k)): # Поле времени ?>
												<span style="white-space:nowrap;" title="<?=$v?>">
													<?=($v > 86400 ? date("Y-m-d H:i:s", $v) : $v)?>
												</span>
											<? elseif(substr($k, -3) == "_id"): ?>
												<? if(!$el = rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, -3), "id", $v)):// mpre("Элемент в смежной таблице не найден") ?>
													<span style="color:red;"><?=$v?></span>
												<? elseif(!is_string($tab = (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")) ?: ""))): mpre("ОШИБКА получения короткого имени таблицы") ?>
												<? elseif(!$_tb = substr($k, 0, -3)): mpre("ОШИБКА получения короткого имени таблицы") ?>
												<? elseif(!$href_key = "/{$arg['modpath']}:{$arg['fn']}/r:{$arg['modpath']}-{$_tb}?&where[id]={$v}"): mpre("ОШИБКА формирования адреса перехода по ключу") ?>
												<? elseif(!is_array($_where = array_filter(array_map(function($key) use($k){ return ($k == $key ? "" : "where[{$key}]=". get($_GET, 'where', $key)); }, array_keys(get($_GET, 'where') ?: []))))): mpre("ОШИБКА получения параметров условий фильтра") ?>
												<? elseif(!$href_where = "/{$arg['modpath']}:{$arg['fn']}/r:{$arg['modpath']}-{$tab}?". ($_where ? "&". implode("&", $_where) : ""). "&where[{$k}]={$v}"): mpre("ОШИБКА получения строки условий") ?>
												<? else:// mpre($href_where) ?>
													<span style="white-space:nowrap;">
														<a class="key" href="<?=$href_key?>" href_where="<?=$href_where?>" title="<?=$v?>">
														</a>&nbsp;<?=(isset($el['name']) ? htmlspecialchars($el['name']) : "<span style='color:#777'>[ ".get($el,'id')." ]</span>")?>
													</span>
												<? endif; ?>
											<? elseif(get($tpl, 'espisok', $k)): ?>
												<? if(empty($v)):// mpre("Значение ключа списка не указано") ?>
												<? elseif(!$href = "/". first(explode("-", first(explode("_", $k)))). ":admin/r:". (strpos("-", $k) ? $k : implode('-', explode("_", $k, 2))). "?&where[id]={$v}"): mpre("Ошибка расчета адреса ссылки") ?>
												<? elseif(!$e = get($tpl, 'espisok', $k, $v)):// mpre("Значение расширенного списка не найдено") ?>
													<span style="color:red;"><?=$v?></span>
												<? elseif(!$name = $name = get($e, "name")):// mpre("Ошибка получения имени внешнего списка") ?>
													<a class="ekey" href="<?=$href?>" title="<?=$v?>"></a>&nbsp;<span style="color:#777;">[ <?=$v?> ]</span>
												<? else: ?>
													<a class="ekey" href="<?=$href?>" title="<?=$v?>"></a>&nbsp;<?=$name?>
												<? endif; ?>
											<? elseif($k == "name"): ?>
												<a class="name" href="/<?=$arg['modpath']?>:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/<?=$lines['id']?>">
													<?=htmlspecialchars($v)?>
												</a>
											<? elseif($k == "href"): ?>
												<? if(!$href = $v): ?>
												<? elseif(!parse_url($href)): mpre("ОШИБКА парсинга адреса") ?>
													<?=htmlspecialchars(strip_tags($v))?>
												<? else: ?>
													<a href="<?=$href?>"><?=$href?></a>
												<? endif; ?>
											<? else: ?>
												<?=htmlspecialchars(strip_tags($v))?>
											<? endif; ?>
										</span>
									<? endforeach; ?>
								</div>
							<? endforeach; ?>
						<? endif; ?>
						<? if(empty($tpl['title'])): ?>
							<div>
								<? foreach(array_merge((array_key_exists('title', $tpl) ? array_intersect_key($tpl['fields'], array_flip($tpl['title'])) : $tpl['fields']), (get($tpl, 'counter') ?: array()), get($tpl, 'ecounter') ?: array()) as $name=>$field): ?>
									<span>
										<? if(substr($name, 0, 1) == "_"): ?>
											<input type="text" disabled>
										<? elseif($name == "id"): ?>
											<button type="submit"><?=(get($_GET, "edit") ? "Редактировать" : "Добавить")?></button>
										<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#^img(\d*|_.+)?#iu",$name)): ?>
											<input type="file" name="<?=$name?>[]" multiple="true">
										<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#^file(\d*|_.+)?#iu",$name)): ?>
											<input type="file" name="<?=$name?>[]" multiple="true">
										<? elseif($name == "hide"): ?>
											<select name="hide">
												<? foreach(get($tpl, 'spisok', 'hide') as $k=>$v): ?>
													<option value="<?=$k?>" <?=((!get($tpl, 'edit') && (get($field, 'Default') == $k)) || ($k == get($tpl, 'edit', $name)) ? "selected" : "")?>><?=$v?></option>
												<? endforeach; ?>
											</select>
										<? elseif($name == "uid"): ?>
											<select name="<?=$name?>">
												<? if(($f = get($tpl, 'edit', $name)) && !rb("{$conf['db']['prefix']}users", "id", $f)): ?>
													<option value="<?=$tpl['edit'][$name]?>" selected><?=$tpl['edit'][$name]?></option>
												<? endif; ?>
												<option value="NULL"></option>
												<? foreach(rb("{$conf['db']['prefix']}users") as $uid): ?>
													<option value="<?=$uid['id']?>" <?=((get($tpl, 'edit', $name) == $uid['id']) || (!get($tpl, 'edit') && ($uid['id'] == $conf['user']['uid'])) || (get($_GET, 'where', 'uid') && $_GET['where']['uid'] == $uid['id']) ? "selected" : "")?>>
														<?=$uid['id']?> <?=$uid['name']?>
													</option>
												<? endforeach; ?>
											</select>
										<? elseif($name == "gid"): ?>
											<select name="<?=$name?>">
												<? if(get($tpl, 'edit', $name) && !rb("{$conf['db']['prefix']}users", "id", $tpl['edit'][$name])): ?>
													<option value="<?=$tpl['edit'][$name]?>" selected><?=$tpl['edit'][$name]?></option>
												<? endif; ?>
												<option value="NULL"></option>
												<? foreach(rb("{$conf['db']['prefix']}users_grp") as $gid): ?>
													<option value="<?=$gid['id']?>" <?=((get($tpl, 'edit',  $name) == $gid['id']) || (!get($tpl, 'edit') && ($gid['id'] == $conf['user']['uid'])) ? "selected" : "")?>>
														<?=$gid['id']?> <?=$gid['name']?>
													</option>
												<? endforeach; ?>
											</select>
										<? elseif($name == "mid"): ?>
											<select name="<?=$name?>">
												<? if(($f = get($tpl, 'edit', $name)) && !rb("modules-index", "id", $f)): ?>
													<option value="<?=$tpl['edit'][$name]?>" selected><?=$tpl['edit'][$name]?></option>
												<? endif; ?>
												<option value="NULL"></option>
												<? foreach(rb("modules-index") as $mid): ?>
													<option value="<?=$mid['id']?>" <?=((!get($tpl, 'edit', 'uid') && ($mid['id'] == get($conf, 'user', 'uid'))) || ($mid['id'] == get($tpl, 'edit', $name)) ? "selected" : "")?>>
														<?=$mid['id']?> <?=$mid['name']?>
													</option>
												<? endforeach; ?>
											</select>
										<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#(^|.+_)(time|last_time|reg_time|up|down)(\d+|_.+|$)#ui",$name,$match)): # Поле времени ?>
											<?// if(!$etime = (get($_GET, 'edit') ? rb($_GET['r'], "id", $_GET['edit'], $name) : time())): mpre("ОШИБКА расчета текущего времени") ?>
											<? if(!is_numeric($time = call_user_func(function($name){// mpre(123);
													if(!array_key_exists('edit', $_GET)){ return time();
													}elseif($index = rb($_GET['r'], "id", $_GET['edit'])){ return (int)get($index, $name);
													}else{ return 0; }
												}, $name))): mpre("ОШИБКА расчета текущего времени") ?>
											<? else: ?>
												<input type="text" name="<?=$name?>" value="<?=date("Y-m-d H:i:s", $time)?>" placeholder="<?=(get($tpl, 'etitle', get($match,2)) ?: $name)?>">
											<? endif; ?>
										<? elseif((substr($name, -3) == "_id") && (false === array_search(substr($name, 0, strlen($name)-3), explode(",", get($conf, 'settings', "{$arg['modpath']}_tpl_exceptions") ?: "")))): # Поле вторичного ключа связанной таблицы ?>
											<? if(!is_array($SELECT = rb("{$arg['modpath']}-". substr($name, 0, -3)))): mpre("Ошибка выборки списка для отображения") ?>
											<? elseif(!get($conf, 'settings', 'admin_datalist')): ?>
												<select name="<?=$name?>" style="width:100%;">
													<? if(strlen(get($tpl, "edit", $name)) && !rb("{$arg['modpath']}-". substr($name, 0, -3), "id", $tpl['edit'][$name])): ?>
														<option selected style="color:red;"><?=htmlspecialchars($tpl['edit'][$name])?></option>
													<? endif; ?>
													<option value="NULL"></option>
													<? foreach($SELECT as $ln): ?>
														<option value="<?=$ln['id']?>" <?=((get($tpl, 'edit', $name) == $ln['id']) || (!get($tpl, 'edit') && (($ln['id'] == ((get($_GET, 'where', $name)) ?: get($field, 'Default'))))) ? "selected" : "")?>>
															<?=$ln['id']?>&nbsp;<?=htmlspecialchars(get($ln, 'name'))?>
														</option>
													<? endforeach; ?>
												</select>
												<? if((count($SELECT) > 1000) && !mpre("Огромный список ". count($SELECT). " эл. <b>". substr($name, 0, -3). "</b> <a target='blank' href='/settings:admin/r:mp_settings/?&where[modpath]={$arg['modpath']}&where[name]={$arg['modpath']}_tpl_exceptions&need[value]={$name}'>исключить</a>")); ?>
											<? elseif(!$tab = substr($name, 0, -3)): mpre("ОШИБКА определения имени таблицы списка") ?>
											<? elseif(!is_array($LIST = rb("{$arg['modpath']}-{$tab}"))): mpre("ОШИБКА выборки списка для поля") ?>
											<?// elseif((!$list_id = (get($tpl, 'edit', 'id') ?: get($_GET, 'where', $name))) && !is_numeric($list_id) && !is_string($list_id) && !is_null($list_id)): mpre("ОШИБКА определения номера списка `{$name}`", get($tpl, 'edit'), $name, gettype($list_id)) ?>
											<? elseif(!is_numeric($list_id = call_user_func(function($tpl) use($name){
													if($list_id = get($tpl, 'edit', $name)){ return (int)$list_id; mpre("Берем из значения редактируемых данных");
													}elseif($list_id = get($_GET, 'where', $name)){ return $list_id; mpre("Берем из параметра условия");
													}else{// mpre("Идентификатор записи не установлен");
														return 0;
													}
												}, $tpl))): mpre("ОШИБКА получения идентификатора записи") ?>
											<? elseif(!is_array($list = ($list_id ? rb($LIST, 'id', $list_id) : []))): mpre("ОШИБКА выборки элемента по идентификатору `{$list_id}`", $name) ?>
											<? elseif(!is_array($_LIST = rb($LIST, "name", "id", "[". get($list, 'name'). "]"))): mpre("ОШИБКА выборки связанной таблицы") ?>
											<?// elseif((!$list_value = get($list, 'name')) && !is_numeric($list_value) && !is_string($list_value) && !is_null($list_value)): mpre("ОШИБКА определения занчения списка", gettype($list_value)) ?>
											<? //elseif(!is_string($list_value = (get($list, 'name') ?: ""))): mpre("ОШИБКА получения значения поля имени", $list) ?>
											<? elseif(!is_string($list_value = call_user_func(function($_LIST) use($list, $list_id){
													if(!$list_id){ //mpre("Идентификатор не задан");
													}elseif(!is_string($name = (($n = get($list, 'name')) ? ".{$n}" : ""))){ mpre("ОШИБКА формирования имени элемента");
													}elseif(1 < count($_LIST)){ return "{$list['id']}{$name}"; mpre("Не уникальное значение имени", "{$list['id']}.{$list['name']}");
													}elseif(!$name = get($list, 'name')){ return $list_id. ""; mpre("Имя элемента не задано");
													}else{ return $name;
													} return "";
												}, $_LIST))): mpre("ОШИБКА определения значения поля", $list) ?>
											<? else:// mpre($list_value) ?>
												<input type="text" name="<?=$name?>" value="<?=$list_value?>" list="<?=$name?>_list" style="background-color:#ddd;">
												<datalist id="<?=$name?>_list">
													<? foreach($LIST as $list): ?>
														<option value="<?=htmlspecialchars(get($list, 'name'))?>"><?=$list['id']?></option>
													<? endforeach; ?>
												</datalist>
											<? endif; ?>
										<?// elseif(get($tpl, 'espisok', $name)): ?>
										<? elseif(get($tpl, 'espisok') && array_key_exists($name, $tpl['espisok'])): ?>
											<select name="<?=$name?>">
												<option value="NULL"></option>
												<? foreach($tpl['espisok'][$name] as $espisok): ?>
													<option value="<?=$espisok['id']?>" <?=((!get($tpl, 'edit') && (get($field, 'Default') == $espisok['id'])) || (get($tpl, 'edit', $name) == $espisok['id']) || (get($_GET, 'where', $name) == $espisok['id']) ? "selected" : "")?>>
														<?=$espisok['id']?> <?=get($espisok, 'name')?>
													</option>
												<? endforeach; ?>
											</select>
										<? else: # Обычное текстовове поле. Если не одно условие не сработало ?>
											<input type="text" name="<?=$name?>" value="<?=htmlspecialchars((get($tpl, 'edit', $name) || is_numeric(get($tpl, 'edit', $name))) ? $tpl['edit'][$name] : (get($field, 'Default') ?: (get($_GET, 'where', $name) ?: "")))?>" placeholder="<?=(get($tpl, 'etitle', $name) ?: $name)?>">
										<? endif; ?>
									</span>
								<? endforeach; ?>
							</div>
						<? endif; ?>
					<? endif; ?>
				</div>
			</form>
		<? endif; ?>
	</div>
</div>
