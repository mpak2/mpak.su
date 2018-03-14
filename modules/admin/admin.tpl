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
		ul.tabs > li.sub > a:after {content:'↵';};
	</style>
	<? foreach(get($tpl, 'menu') ?: array() as $k=>$ar): ?>
		<li class="<?=($r = $tpl['tables'][$k])?> <?=($_GET['r'] == $r ? "act" : "")?> <?=($ar ? "sub" : "")?>">
			<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$conf['db']['prefix']?><?=($s = substr($r, strlen($conf['db']['prefix'])))?>">
				<? if(array_key_exists($s, $conf['settings']) && ($n = $conf['settings'][$s])): ?>
					<?=$n?>
				<? else: ?>
					<? if($i = substr($r, strlen("{$conf['db']['prefix']}{$arg['modpath']}"))): ?>
						<?=($i == "_index" ? $conf['modules'][$arg['modpath']]['name'] : $i)?>
					<? else: ?>_<? endif; ?>
				<? endif; ?>
			</a>
			<? if(!empty($ar)): ?>
				<ul>
					<? foreach($ar as $n=>$v): ?>
						<li class="<?=($r = $tpl['tables'][$v])?> <?=($_GET['r'] == $r ? "act" : "")?>" style="display:block; min-width:120px;">
							<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$conf['db']['prefix']?><?=($s = substr($r, strlen($conf['db']['prefix'])))?>">
								<? if($n = get($conf, 'settings', $s)): ?>
									<?=$n?>
								<? else: ?>
									<? if($i = substr($r, strlen("{$conf['db']['prefix']}{$arg['modpath']}"))): ?>
										<?=($i == "_index" ? $conf['modules'][$arg['modpath']]['name'] : $i)?>
									<? else: ?>_<? endif; ?>
								<? endif; ?>
							</a>
						</li>
						
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</li>
	<? endforeach; ?>
</ul>
<? if(array_search($_GET['r'], $tpl['tables']) !== false): ?>
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
					<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/edit?<? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?><?=(get($_GET, 'p') ? "&p={$_GET['p']}" : "")?>">
						<button type="button">Добавить</button>
					</a>
				</span>
			<? endif; ?>
			<? if(!get($tpl, 'edit')): ?>
				<span style="width:430px;"><?=$tpl['pager']?></span>
			<? endif; ?>
			<span style="padding-right:20px; text-align:right; overflow:visible; white-space:normal;">
				<? if($t = implode("_", array_slice(explode("_", $_GET['r']), 1))): # Короткое имя текущей таблицы ?>
					<ul class="admin">
						<? foreach(array_unique(array_map(function($f){ return first(explode('.', $f)); }, mpreaddir("/modules/{$arg['modpath']}", 1))) as $f): ?>
							<? if(strpos($f, "admin_") === false):// mpre("Имя файла не админ_") ?>
							<? elseif(!$fl = implode('_', array_slice(explode('_', $f), 1))): mpre("Ошибка формирования алиаса файла") ?>
							<? elseif(!($ft = implode('_', array_slice(explode('_', $t), 1))) &0): mpre("Ошибка формирования внутреннего имени таблицы") ?>
							<? elseif(!$ft || (strpos($fl, $ft) !== 0)):// mpre("Имя не соответствует формату страницы") ?>
							<? elseif(!$href = "/{$arg['modpath']}:{$f}". (($id = get($_GET, 'where', 'id')) ? "/{$id}" : ""). ""): mpre("Ошибка формирования адреса страницы") ?>
							<?// elseif(!$dir = mpopendir($od = $fl)): mpre("Имя файла в файловой системе не найдено {$od}") ?>
							<? elseif(!($title = mpopendir("modules/{$arg['modpath']}/{$f}.tpl")) && !($title = mpopendir("modules/{$arg['modpath']}/{$f}.php"))): mpre("Имя файла в файловой системе не найдено"); ?>
							<? elseif(!$bold = (strpos($title, "phar://") === 0 ? "inherit" : "bold")): mpre("Ошибка вычисления толщины текста"); ?>
							<? elseif(!$st = get($conf, 'settings', ($af = "{$arg['modpath']}_{$f}"))):// mpre("Имя страница в свойствах раздела не установлено") ?>
								<li title="<?=$title?>"><a href="<?=$href?>" style="font-weight:<?=$bold?>; color:#bbb;"><?=$af?></a></li>
							<? else: ?>
								<li title="<?=$title?>"><a href="<?=$href?>" style="font-weight:<?=$bold?>;"><?=$st?></a></li>
							<? endif; ?>
						<? endforeach; ?>
						<li><b><a href="/sqlanaliz:admin_sql/r:<?=$_GET['r']?>">БД</a></b></li>
						<li class="settings" style="position:relative; z-index:10;">
							<div class="settings">
								<h2>Свойства</h2>
								<div class="content">
									<h4>Свойства таблицы</h4>
									<div class="table" style="width:100%;">
										<? foreach(["{$arg['modpath']}"=>["Список"=>"=>espisok", "Исключения"=>"_tpl_exceptions"], "{$t}"=>["Название"=>"", "Заголовки"=>"=>title", "Сортировка"=>"=>order", "Счетчик"=>"=>ecounter"]] as $prx=>$params): ?>
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
		<? if(array_search($_GET['r'], $tpl['tables']) !== false): ?>
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
				.table .th > span {font-weight:bold; background:url(i/gradients.gif) repeat-x 0 -57px; border-top: 1px solid #dbdbdd; border-bottom: 1px solid #dbdbdd; line-height: 27px; white-space:nowrap;}
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
	//					var all = $(del.currentTarget).parents(".th").length;
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
					}).on("click", "[line_id] a.del", function(e){// alert("Удаление");
						if(e.ctrlKey || confirm("Удалить элемент?")){
							var line_id = $(e.currentTarget).parents("[line_id]").attr("line_id");
							var fn = $(e.currentTarget).parents("[fn]").attr("fn");
							var post = {}; post[fn] = "";
							$(e.currentTarget).find("img").css("opacity", 0.3);
							$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/"+ line_id+ "/null", post, function(response){
								console.log("line_id:", line_id, "response:", response);
								document.location.reload(true);
							})
						}
					}).on("click", "select", function(e){
						if(e.altKey){
	//						alert("Сработало");
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
							$("<"+"iframe>").attr("name", "response_"+timeStamp).appendTo(forms).load(function(){
								var data = $(this).contents().find("body").html();

								try{if(json = JSON.parse(data)){
									console.log("json:", json);
									var button = $(e.delegateTarget).find("button[type=submit]:focus");
	//								console.log("button:", button, "content:", $(button).text());
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
					<? if(get($tpl, 'title') && array_key_exists("edit", $_GET)): ?>
						<div class="th">
							<span style="width:15%;">Поле</span>
							<span>Значение</span>
						</div>
						<? foreach($tpl['fields'] as $name=>$field): ?>
							<div>
								<span style="text-align:right;">
									<? if($comment = get($field, 'Comment')): ?>
										<span class="info" title="<?=$comment?>">?</span>
									<? endif; ?>
									<? if($etitle = get($tpl, 'etitle', $name)): ?>
										<?=$etitle?>
									<? elseif(substr($name, -3) == "_id"): ?>
										<?=(get($conf, 'settings', "{$arg['modpath']}_". substr($name, 0, -3)) ?: substr($name, 0, -3))?>
									<? else: ?>
										<?=htmlspecialchars($name)?>
									<? endif; ?>
								</span>
								<span>
									<? if($name == "id"): # Вертикальное отображение ?>
										<?=(get($tpl, 'edit', "id") ?: "Номер записи назначаеся ситемой")?>
									<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#^img(\d*|_.+)?#iu",$name)): ?>
										<input type="file" name="<?=$name?>[]" multiple="true">
										<span class="info_comm">
											<a href="/<?=$arg['modpath']?>:img/<?=get($tpl, 'lines', get($tpl, 'edit', "id"))['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:<?=$name?>/w:109/h:109/null/img.png" target="_blank"><?=get($tpl,'edit',$name);?></a>
										</span>
									<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#^file(\d*|_.+)?#iu",$name)): ?>
										<input type="file" name="<?=$name?>[]" multiple="true">
										<span class="info_comm"><?=get($tpl,'edit',$name);?></span>
									<? elseif($name == "hide"): ?>
										<select name="hide">
											<? foreach(get($tpl, 'spisok', 'hide') as $k=>$v): ?>
												<option value="<?=$k?>" <?=((!get($tpl, 'edit') && (get($field, 'Default') == $k)) || ($k == get($tpl, 'edit', 'hide')) ? "selected" : "")?>><?=$v?></option>
											<? endforeach; ?>
										</select>
									<? elseif($name == "uid"): ?>
										<select name="<?=$name?>">
											<option value="NULL"></option>
											<? foreach(rb("{$conf['db']['prefix']}users") as $uid): ?>
												<option value="<?=$uid['id']?>" <?=((get($tpl, 'edit', $name) == $uid['id']) || (!get($tpl, "edit") && ($conf['user']['uid'] == $uid['id'])) || (get($_GET, 'where', 'uid') && $_GET['where']['uid'] == $uid['id']) ? "selected" : "")?>><?=$uid['name']?></option>
											<? endforeach; ?>
										</select>
									<? elseif($name == "gid"): ?>
										<select name="<?=$name?>">
											<option value="NULL"></option>
											<? foreach(rb("{$conf['db']['prefix']}users_grp") as $gid): ?>
												<option value="<?=$gid['id']?>" <?=((get($tpl, 'edit', $name) == $gid['id']) || (!get($tpl, "edit") && ($conf['user']['uid'] == $uid['id'])) ? "selected" : "")?>><?=$uid['name']?></option>
											<? endforeach; ?>
										</select>
									<? elseif($name == "mid"): ?>
										<select name="<?=$name?>">
											<option value="NULL"></option>
											<? foreach(rb("{$conf['db']['prefix']}modules_index") as $modules): ?>
												<option value="<?=$mid['id']?>" <?=((get($tpl, 'edit', $name) == $modules['id']) || (!get($tpl, "edit") && ($conf['user']['uid'] == $modules['id'])) ? "selected" : "")?>><?=$modules['name']?></option>
											<? endforeach; ?>
										</select>
									<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#(^|.+_)(time|last_time|reg_time|up|down)(\d+|_.+|$)#ui",$name,$match)): # Поле времени ?>									
										<input type="text" name="<?=$name?>" value="<?=date("Y-m-d H:i:s", get($tpl, 'edit', $name) /* ?: time() Сбивает пустое время */)?>" placeholder="<?=($tpl['etitle'][get($match,2)] ?: $name)?>">
									<? elseif((substr($name, -3) == "_id") && (false === array_search(substr($name, 0, -3), explode(",", get($conf, 'settings', "{$arg['modpath']}_tpl_exceptions") ?: "")))): # Поле вторичного ключа связанной таблицы ?>
										<? if(!get($conf, 'settings', 'admin_datalist')): ?>
											<select name="<?=$name?>" style="width:100%;">
												<? if(get($tpl, 'edit', $name) && !rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($name, 0, -3), "id", $tpl['edit'][$name])): ?> 
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
										<? elseif((!$list_id = (get($tpl, 'edit', $name) ?: get($_GET, 'where', $name))) && !is_numeric($list_id) && !is_string($list_id) && !is_null($list_id)): mpre("ОШИБКА определения номера списка `{$name}`", get($tpl, 'edit'), $name, gettype($list_id)) ?>
										<? elseif(!is_array($list = rb($LIST, "id", $list_id))): mpre("ОШИБКА выборки связанной таблицы") ?>
										<? elseif((!$list_value = get($list, 'name')) && !is_numeric($list_value) && !is_string($list_value) && !is_null($list_value)): mpre("ОШИБКА
 определения занчения списка", gettype($list_value)) ?>
										<? else:// mpre(htmlspecialchars($list_value)) ?>
											<input type="text" name="<?=$name?>" value="<?=($list ? htmlspecialchars($list_value) : ($list_id ?: ""))?>" list="<?=$name?>_list" style="background-color:#ddd;">
											<datalist id="<?=$name?>_list">
												<? foreach($LIST as $list): ?>
													<option value="<?=htmlspecialchars(array_key_exists('name', $list) ? get($list, 'name') : $list_id)?>"><?=$list['id']?></option>
												<? endforeach; ?>
											</datalist>
										<? endif; ?>
									<? elseif(!preg_match("#_id$#ui",$name) AND preg_match("#(^|.+_)(text)(\d+|_.+|$)#iu",$name)): ?>
										<?=mpwysiwyg($name, get($tpl, 'edit', $name) ?: "")?>
									<?// elseif($tpl_espisok = get($tpl, 'espisok', $name)): ?>
									<?// elseif(array_key_exists($name, $tpl['espisok'])): ?>
									<? elseif(get($tpl, 'espisok') && array_key_exists($name, $tpl['espisok'])): ?>
										<select name="<?=$name?>">
											<? if(!get($tpl, 'edit', $name)):// mpre("Значение не задано") ?>
											<? elseif($val = get('espisok', $name, get($tpl, 'edit', $name))):// mpre("Значение найдено") ?>
												<option value="<?=$value?>"><?=$value?></option>
											<? else:// mpre("Значение не найдено") ?>
												<option value="<?=get($tpl, 'edit', $name)?>"><?=get($tpl, 'edit', $name)?></option>
											<? endif; ?>
											<option value="NULL"></option>
											<? foreach($tpl['espisok'][$name] as $espisok): ?>
												<option value="<?=$espisok['id']?>" <?=(((!get($tpl, 'edit') && ($field['Default'] == $espisok['id'])) || ($espisok['id'] == get($tpl, 'edit', $name)) || (array_key_exists('edit', $_GET) && (get($_GET, 'where', $name) == $espisok['id']))) ? "selected" : "")?>><?=$espisok['id']?> <?=$espisok['name']?></option>
											<? endforeach; ?>
										</select>
									<? else: # Обычное текстовове поле. Если не одно условие не сработало ?>
										<input type="text" name="<?=$name?>" value="<?=htmlspecialchars(get($tpl, 'edit') ? rb($_GET['r'], "id", get($_GET, 'edit'), $name) : get($field, 'Default'))?>" placeholder="<?=(get($tpl, 'etitle', $name) ?: $name)?>">
									<? endif; ?>
								</span>
							</div>
						<? endforeach; ?>
						<div>
							<span></span>
							<span>
								<button type="submit">Сохранить</button>
								<button type="submit" name="_id" value="NULL">Дублировать</button>
							</span>
						</div>
					<? else: # Горизонтальный вариант таблицы ?>
						<div class="th">
							<? foreach(array_merge((array_key_exists('title', $tpl) ? array_intersect_key($tpl['fields'], array_flip($tpl['title'])) : $tpl['fields']), (get($tpl, 'counter') ?: array()), (get($tpl, 'ecounter') ?: array())) as $name=>$field):// mpre($name, $field) ?>
								<span>
									<? if(get($field, 'Comment')): ?>
										<span class="info" title="<?=$field['Comment']?>">?</span>
									<? endif; ?>
									<? if(substr($name, 0, 2) == "__"): ?>
										<span title="<?=substr($name, 2)?>">_<?=(get($conf, 'settings', substr($name, 2)) ?: substr($name, 2))?></span>
									<? elseif(substr($name, 0, 1) == "_"): ?>
										<span title="<?=substr($name, 1)?>"><?=(get($conf, 'settings', "{$arg['modpath']}_". substr($name, 1)) ?: substr($name, 1))?></span>
									<? elseif(get($tpl, 'etitle')): ?>
										<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?<? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>&order=<?=(get($_GET, "order") == $name ? "{$name} DESC" : $name )?>" title="<?=$name?>">
											<?=(get($tpl, 'etitle', $name) ?: $name)?>
										</a>
										<? if("id" == $name): ?>
											<span class="control">
												<input type="checkbox" title="Стандартно - инверсия; Shift - Повторение">
												<a class="del" href="javascript:" style="display:none;"></a>
											</span>
										<? endif; ?>
									<? elseif(substr($name, -3) == "_id"): ?>
										<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?<? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>&order=<?=(get($_GET, "order") == $name ? "{$name} DESC" : $name )?>" title="<?=$name?>"><?=(get($conf, 'settings', "{$arg['modpath']}_". substr($name, 0, -3)) ?: substr($name, 0, -3))?></a>
									<? else: ?>
										<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?<? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>&order=<?=(get($_GET, "order") == $name ? "{$name} DESC" : $name )?>" title="<?=$name?>">
											<?=$name?>
										</a>
									<? endif; ?>
								</span>
							<? endforeach; ?>
						</div>
						<? if(!get($_GET, "edit")): ?>
							<? foreach($tpl['lines'] as $lines): ?>
								<div line_id="<?=$lines['id']?>">
									<? foreach(array_merge((array_key_exists('title', $tpl) ? array_intersect_key($lines, array_flip($tpl['title'])) : $lines), get($tpl, 'counter') ?: array(), get($tpl, 'ecounter') ?: array()) as $k=>$v): ?>
										<span>
											<? if(substr($k, 0, 2) == "__"): // $tpl['ecounter'] ?>
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
												<? elseif(!$href = "/{$m}:admin/r:". (strpos($tab, '-') ? "" : $conf['db']['prefix']). "{$tab}?&where[{$field}]={$lines['id']}"): mpre("Ошибка генерации ссылки на связанную таблицу") ?>
												<? else:// mpre($m, $field) ?>
													<a href="<?=$href?>">
														<?=(($cnt = get($v, $lines['id'], 'cnt')) ? "{$cnt}&nbspшт" : "Нет")?>
													</a>
												<? endif; ?>
											<? elseif(substr($k, 0, 1) == "_"): // $tpl['counter'] ?>
												<a href="/<?=$arg['modpath']?>:admin/r:<?="{$conf['db']['prefix']}{$arg['modpath']}{$k}?&where[". (($_GET['r'] == "{$conf['db']['prefix']}users") && ($k == "_mem") ? "uid" : substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={$lines['id']}"?>">
													<?=(($cnt = get($tpl, 'counter', $k, $lines['id'])) ? "{$cnt}&nbspшт" : "Нет")?>
												</a>
											<? elseif($k == "id"): ?>
												<span class="control" style="white-space:nowrap;">
													<a class="del" href="javascript:"></a>
													<a class="edit" href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?&edit=<?=$v?><? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?><?=(get($_GET, 'order') ? "&order={$_GET['order']}" : "")?><?=(get($_GET, 'p') ? "&p={$_GET['p']}" : "")?>"></a>
													<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?&where[id]=<?=$v?>"><?=$v?></a>
													<input type="checkbox" name="id" value="<?=$v?>" style="display:none;">
												</span>
											<? elseif(!preg_match("#_id$#ui",$k) AND preg_match("#^img(\d*|_.+)?#iu",$k)): ?>
												<div class="imgs" fn="<?=$k?>" style="position:relative; height:14px;">
													<a class="del <?=($lines[$k]?"":"disabled")?>" href="javascript:void(0)" title="Удалить изображение"><img src="/img/del.png"></a>
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
												<? if($el = rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, -3), "id", $v)): ?>
	<!--												<span style="color:#ccc;"><?=$el['id']?></span>-->
													<span style="white-space:nowrap;">
														<a class="key" href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?="{$conf['db']['prefix']}{$arg['modpath']}_"?>
															<?=substr($k, 0, -3)?>?&where[id]=<?=$v?>" title="<?=$v?>">
														</a>&nbsp;<?=(isset($el['name']) ? htmlspecialchars($el['name']) : "<span style='color:#777'>[ ".get($el,'id')." ]</span>")?>
													</span>
												<? elseif($v): ?>
													<span style="color:red;"><?=$v?></span>
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
												<a href="/<?=$arg['modpath']?>:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/<?=$lines['id']?>"><?=htmlspecialchars($v)?>
												</a>
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
											<input type="text" name="<?=$name?>" value="<?=date("Y-m-d H:i:s", (get($_GET, 'edit') ? rb($_GET['r'], "id", $_GET['edit'], $name) : time()))?>" placeholder="<?=(get($tpl, 'etitle', get($match,2)) ?: $name)?>">
										<? elseif((substr($name, -3) == "_id") && (false === array_search(substr($name, 0, strlen($name)-3), explode(",", get($conf, 'settings', "{$arg['modpath']}_tpl_exceptions") ?: "")))): # Поле вторичного ключа связанной таблицы ?>
											<? if(!is_array($SELECT = rb("{$arg['modpath']}-". substr($name, 0, -3)))): mpre("Ошибка выборки списка для отображения") ?>
											<? elseif(!get($conf, 'settings', 'admin_datalist')): ?>
												<select name="<?=$name?>" style="width:100%;">
													<option value="NULL"></option>
													<? if(get($tpl, "edit", $name) && !rb("{$arg['modpath']}-". substr($name, 0, -3), "id", $tpl['edit'][$name])): ?>
														<option selected style="color:red;"><?=htmlspecialchars($tpl['edit'][$name])?></option>
													<? endif; ?>
													<? foreach($SELECT as $ln): ?>
														<option value="<?=$ln['id']?>" <?=((get($tpl, 'edit', $name) == $ln['id']) || (!get($tpl, 'edit') && (($ln['id'] == ((get($_GET, 'where', $name)) ?: get($field, 'Default'))))) ? "selected" : "")?>>
															<?=$ln['id']?>&nbsp;<?=htmlspecialchars(get($ln, 'name'))?>
														</option>
													<? endforeach; ?>
												</select>
												<? if((count($SELECT) > 1000) && !mpre("Огромный список ". count($SELECT). " эл. <b>". substr($name, 0, -3). "</b> <a target='blank' href='/settings:admin/r:mp_settings/?&where[modpath]={$arg['modpath']}&where[name]={$arg['modpath']}_tpl_exceptions&need[value]={$name}'>исключить</a>")); ?>
											<? elseif(!$tab = substr($name, 0, -3)): mpre("ОШИБКА определения имени таблицы списка") ?>
											<? elseif(!is_array($LIST = rb("{$arg['modpath']}-{$tab}"))): mpre("ОШИБКА выборки списка для поля") ?>
											<? elseif((!$list_id = (get($tpl, 'edit', $name) ?: get($_GET, 'where', $name))) && !is_numeric($list_id) && !is_string($list_id) && !is_null($list_id)): mpre("ОШИБКА определения номера списка `{$name}`", get($tpl, 'edit'), $name, gettype($list_id)) ?>
											<? elseif(!is_array($list = rb($LIST, "id", $list_id))): mpre("ОШИБКА выборки связанной таблицы") ?>
											<? elseif((!$list_value = get($list, 'name')) && !is_numeric($list_value) && !is_string($list_value) && !is_null($list_value)): mpre("ОШИБКА
	определения занчения списка", gettype($list_value)) ?>
											<? else:// mpre(htmlspecialchars($list_value)) ?>
												<input type="text" name="<?=$name?>" value="<?=(array_key_exists('name', $list) ? htmlspecialchars($list_value) : $list_id)?>" list="<?=$name?>_list" style="background-color:#ddd;">
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
		<? else: ?>
			<div style="margin:10px;">
				<script>
					(function($, script){
						$(script).parent().on("click", "button.table", function(e){
							if(value = prompt("Название таблицы")){
								$.post("/sqlanaliz:admin_sql/null", {table:"<?=$_GET['r']?>"}, function(data){
									$.post("/settings:admin/r:mp_settings/null", {modpath:"<?=$arg['modpath']?>", name:"<?=substr($_GET['r'], strlen($conf['db']['prefix']))?>", value:value, aid:4}, function(data){
										console.log("post:", data);
										document.location.reload(true);
									}/*, "json").fail(function(error){
										console.log("error:", error);
										alert(error.responseText);
									}*/);
								}, "json").fail(function(error){
									console.log("error:", error);
									alert(error.responseText);
								});
							}
						})
					})(jQuery, document.scripts[document.scripts.length-1])
				</script>
				Таблица не найдена
				<? if($conf['modules']['sqlanaliz']['admin_access'] > 4): ?>
					<button class="table">Создать</button>
				<? endif; ?>
			</div>
		<? endif; ?>
	</div>
</div>
