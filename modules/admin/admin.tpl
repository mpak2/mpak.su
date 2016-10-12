<ul class="nl tabs">
	<style>
		ul.tabs li ul {position:absolute; display:none;}
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
<div class="lines">
	<? if(array_search($_GET['r'], $tpl['tables']) !== false): ?>
		<style>
			.table > div > span {border-collapse:collapse; padding:5px; vertical-align:middle;}
			.table > div > span:first-child {width:70px;}
			.table > div:hover {background-color:#f4f4f4;}
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
			.lines ul.admin li {display:inline-block;}
			.lines ul.admin li:before{ content:"• "; }
			.lines .settings ul li {display:list-item;}
			.lines .settings ul li:before {content:none;}
			.lines .settings .table>div>span:last-child {text-align:right;}
		</style>
		<script>
			(function($, script){
				$(script).parent().on("click", ".control a.del", function(e){
					if(e.ctrlKey || confirm("Удалить элемент?")){
						var line_id = $(e.currentTarget).parents("[line_id]").attr("line_id");
						$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/"+line_id+"/null", {id:0}, function(data){
							if(isNaN(data)){ alert(data) }else{
								$(e.currentTarget).parents("[line_id]").remove();
							}
						})
					}
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
				}).on("click", ".imgs a.del", function(e){
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
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
		<form action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?><?=(get($_GET, "edit") ? "/{$_GET['edit']}" : "")?>/null" method="post" enctype="multipart/form-data">
			<script src="/include/jquery/jquery.iframe-post-form.js"></script>
			<script>
				(function($, script){
					$(script).parent().one("init", function(e){
						setTimeout(function(){
							$(e.delegateTarget).iframePostForm({
								complete:function(data){
									try{
										if(json = jQuery.parseJSON(data)){
											console.log("json:", json);
											document.location.href = "/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?<? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?><?=(get($_GET, 'order') ? "&order={$_GET['order']}" : "")?><?=(get($_GET, 'p') ? "&p={$_GET['p']}" : '')?>";
										}
									}catch(e){
										if(isNaN(data)){
											console.log("isNaN:", data)
											alert(data);
										}else{
											console.log("date:", data)
										}
									}
								}
							});
						}, 200)
					}).trigger("init")
				})(jQuery, document.scripts[document.scripts.length-1])
			</script>
			<div class="table">
				<div>
					<? if(get($tpl, 'title') && !get($_GET, "edit")): ?>
						<span style="width:60px; padding-left:20px;">
							<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/edit?<? foreach(get($_GET, 'where') ?: array() as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?><?=(get($_GET, 'p') ? "&p={$_GET['p']}" : "")?>">
								<button type="button">Добавить</button>
							</a>
						</span>
					<? endif; ?>
					<? if(!get($tpl, 'edit')): ?>
						<span style="width:430px;"><?=$tpl['pager']?></span>
					<? endif; ?>
					<span style="padding-right:20px; text-align:right;">
						<script sync>
							(function($, script){
								$(script).parent().on("mouseenter mouseleave", "li.settings", function(e){ // Загрузка родительского элемента
									if("mouseenter" == e.type){
										$(e.delegateTarget).find("div.settings").show();
									}else{
										$(e.delegateTarget).find("div.settings").hide();
									}
								})
							})(jQuery, document.scripts[document.scripts.length-1])
						</script>
						<? if($t = implode("_", array_slice(explode("_", $_GET['r']), 1))): # Короткое имя текущей таблицы ?>
							<ul class="admin">
								<? foreach(array_unique(array_map(function($f){ return first(explode('.', $f)); }, mpreaddir("/modules/{$arg['modpath']}", 1))) as $f): ?>
									<? if((strpos($f, "admin_") === 0) && ($fl = implode('_', array_slice(explode('_', $f), 1))) && (!($ft = implode('_', array_slice(explode('_', $t), 1))) || (strpos(($fl), $ft) === 0))): # Адреса страниц начинающихся на admin_ и совпадающие с текущей таблицуй ?>
										<li><a href="/<?=$arg['modpath']?>:<?=$f?><?=(($id = get($_GET, 'where', 'id')) ? "/{$id}" : "")?>"><?=(get($conf, 'settings', ($af = "{$arg['modpath']}_{$f}")) ?: $af)?></a></li>
									<? endif; ?>
								<? endforeach; ?>
								<li><b><a href="/sqlanaliz:admin_sql/r:<?=$_GET['r']?>">БД</a></b></li>
								<li class="settings" style="position:relative;">
									<div class="settings" style="display:none; position:absolute; width:300px; background-color:white; right:0px; top:20; text-align:left; min-height:50px;">
										<h2>Свойства</h2>
										<div style="padding:10px; border:1px solid #eee; border-top:0;">
											<h4>Таблица</h4>
											<div class="table" style="width:100%;">
												<? foreach(["{$t}"=>["Название"=>"", "Заголовки"=>"=>title", "Сортировка"=>"=>order", "Счетчик"=>"=>ecounter", "Список"=>"=>espisok"], "{$arg['modpath']}"=>[/*"Список"=>"=>espisok"*/]] as $prx=>$params): ?>
													<? foreach($params as $name=>$uri): ?>
														<div>
															<span><?=$name?></span>
															<span>
																<a href="/settings:admin/r:mp_settings/?&where[modpath]=<?=$arg['modpath']?>&where[name]=<?=($st = $prx. $uri)?>">
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
								<? elseif(array_search($name, array(1=>"img", "img2", "img3"))): ?>
									<input type="file" name="<?=$name?>[]" multiple="true">
								<? elseif($name == "file"): ?>
									<input type="file" name="file[]" multiple="true">
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
											<option value="<?=$uid['id']?>" <?=((get($tpl, 'edit', $name) == $uid['id']) || (!get($tpl, "edit") && ($conf['user']['uid'] == $uid['id'])) ? "selected" : "")?>><?=$uid['name']?></option>
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
								<? elseif(array_search($name, array(1=>"time", "last_time", "reg_time", "up", 'down'))): # Поле времени ?>
									<input type="text" name="<?=$name?>" value="<?=date("Y-m-d H:i:s", get($tpl, 'edit', $name) ?: time())?>" placeholder="<?=($tpl['etitle'][$name] ?: $name)?>">
								<? elseif((substr($name, -3) == "_id") && (false === array_search(substr($name, 0, -3), explode(",", get($conf, 'settings', "{$arg['modpath']}_tpl_exceptions") ?: "")))): # Поле вторичного ключа связанной таблицы ?>
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
								<? elseif($name == "text"): ?>
									<?=mpwysiwyg($name, get($tpl, 'edit', $name) ?: "")?>
								<? elseif($tpl_espisok = get($tpl, 'espisok', $name)): ?>
									<select name="<?=$name?>">
										<option value="NULL"></option>
										<? foreach($tpl_espisok as $espisok): ?>
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
						<span><button type="submit">Сохранить</button></span>
					</div>
				<? else: # Горизонтальный вариант таблицы ?>
					<div class="th">
						<? foreach(array_merge((array_key_exists('title', $tpl) ? array_intersect_key($tpl['fields'], array_flip($tpl['title'])) : $tpl['fields']), (get($tpl, 'counter') ?: array()), (get($tpl, 'ecounter') ?: array())) as $name=>$field): ?>
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
											<? if(($f = substr($k, 2)) && ($keys = array_keys($ar = explode("_", $f))) && ($m = $ar[min($keys)])): ?>
												<a href="/<?=$m?>:admin/r:<?=$conf['db']['prefix']?><?=$f?>?&where[<?=($_GET['r'] == "{$conf['db']['prefix']}users" ? "uid" : substr($_GET['r'], strlen($conf['db']['prefix'])))?>]=<?=$lines['id']?>">
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
											</span>
										<? elseif(array_search($k, array(1=>"img", "img2", "img3"))): ?>
											<div class="imgs" fn="<?=$k?>" style="position:relative; width:70px; height:70px;">
													<? if($lines[$k]): ?>
														<a class="del" href="javascript:void(0)" style="position:absolute; top:5px; right:5px;" title="Удалить изображение">
															<img src="/img/del.png" style="background-color:#eee; border:1px solid #888; border-radius:3px;">
														</a>
													<? endif; ?>
													<img src="/<?=$arg['modpath']?>:img/<?=$lines['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:<?=$k?><?=($lines[$k] ? "" : "/rand:". time())?>/w:65/h:65/null/img.png" style="border:1px solid #aaa; padding:2px;"  title="<?=$v?>">
											</div>
										<? elseif($k == "file"): ?>
											<a target="blank" href="/<?=$arg['modpath']?>:file/<?=$lines['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:file/null/<?=basename($lines[$k])?>" title="<?=$v?>">
												<?=$v?>
											</a>
										<? elseif($k == "sort"): ?>
											<div class="sort" style="white-space:nowrap;">
												<a class="inc" href="javascript:void(0);" style="background:url(i/mgif.gif); background-position:-18px -90px; width: 10px; height: 14px; display:inline-block;"></a>
												<span><?=$v?></span>
												<a class="dec" href="javascript:void(0);" style="background:url(i/mgif.gif); background-position:0 -90px; width: 10px; height: 14px; display:inline-block;"></a>
											</div>
										<? elseif($k == "hide"): ?>
											<?=(get($tpl, 'spisok', 'hide', $v) ?: "")?>
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
										<? elseif(array_search($k, array(1=>"time", "last_time", "reg_time", "up", 'down'))): # Поле времени ?>
											<span style="white-space:nowrap;" title="<?=$v?>">
												<?=($v ? date("Y-m-d H:i:s", $v) : "")?>
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
											<? if($e = get($tpl, 'espisok', $k, $v)): ?>
												<a class="ekey" href="/<?=first(explode("_", $k))?>:admin/r:<?=$conf['db']['prefix']?><?=$k?>?&where[id]=<?=$v?>" title="<?=$v?>"></a>
												<?=(strlen($name = get($e, "name")) > 16 ? mb_substr($name, 0, 16, "UTF-8"). "..." : $name)?>
											<? endif; ?>
										<? elseif($k == "name"): ?>
											<a href="/<?=$arg['modpath']?>
												<?=(($substr = substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))) == "index" ? "" : ":{$substr}")?>/<?=$lines['id']?>"><?=htmlspecialchars($v)?>
											</a>
										<? else: ?>
											<?=htmlspecialchars($v)?>
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
									<? elseif(array_search($name, array(1=>"img", "img2", "img3"))): ?>
										<input type="file" name="<?=$name?>[]" multiple="true">
									<? elseif($name == "file"): ?>
										<input type="file" name="file[]" multiple="true">
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
												<option value="<?=$uid['id']?>" <?=((get($tpl, 'edit', $name) == $uid['id']) || (!get($tpl, 'edit') && ($uid['id'] == $conf['user']['uid'])) ? "selected" : "")?>>
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
									<? elseif(array_search($name, array(1=>"time", "last_time", "reg_time", "up", 'down'))): # Поле времени ?>
										<input type="text" name="<?=$name?>" value="<?=date("Y-m-d H:i:s", (get($_GET, 'edit') ? rb($_GET['r'], "id", $_GET['edit'], $name) : time()))?>" placeholder="<?=(get($tpl, 'etitle', $name) ?: $name)?>">
									<? elseif((substr($name, -3) == "_id") && (false === array_search(substr($name, 0, strlen($name)-3), explode(",", get($conf, 'settings', "{$arg['modpath']}_tpl_exceptions") ?: "")))): # Поле вторичного ключа связанной таблицы ?>
										<select name="<?=$name?>" style="width:100%;">
											<option value="NULL"></option>
											<? if(get($tpl, "edit", $name) && !rb("{$arg['modpath']}-". substr($name, 0, -3), "id", $tpl['edit'][$name])): ?>
												<option selected style="color:red;"><?=htmlspecialchars($tpl['edit'][$name])?></option>
											<? endif; ?>
											<? foreach(rb("{$arg['modpath']}-". substr($name, 0, -3)) as $ln): ?>
												<option value="<?=$ln['id']?>" <?=((get($tpl, 'edit', $name) == $ln['id']) || (!get($tpl, 'edit') && (($ln['id'] == (get($_GET, 'where', $name)) ?: get($field, 'Default')))) ? "selected" : "")?>>
													<?=$ln['id']?>&nbsp;<?=htmlspecialchars(get($ln, 'name'))?>
												</option>
											<? endforeach; ?>
										</select>
									<? elseif(get($tpl, 'espisok', $name)): ?>
										<select name="<?=$name?>">
											<option value="NULL"></option>
											<? foreach($tpl['espisok'][$name] as $espisok): ?>
												<option value="<?=$espisok['id']?>" <?=((!get($tpl, 'edit') && (get($field, 'Default') == $espisok['id'])) || (get($tpl, 'edit', $name) == $espisok['id']) || (get($_GET, 'where', $name) == $espisok['id']) ? "selected" : "")?>>
													<?=$espisok['id']?> <?=get($espisok, 'name')?>
												</option>
											<? endforeach; ?>
										</select>
									<? else: # Обычное текстовове поле. Если не одно условие не сработало ?>
										<input type="text" name="<?=$name?>" value="<?=(get($tpl, 'edit', $name) ?: (get($field, 'Default') ?: (get($_GET, 'where', $name) ?: "")))?>" placeholder="<?=(get($tpl, 'etitle', $name) ?: $name)?>">
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
			<? if($conf['modules']['sqlanaliz']['access'] > 4): ?>
				<button class="table">Создать</button>
			<? endif; ?>
		</div>
	<? endif; ?>
</div>
