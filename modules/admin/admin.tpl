<ul class="nl tabs">
	<style>
		ul.tabs li ul {position:absolute; display:none;}
		ul.tabs li:hover ul {display:block; width:inherit;}
		ul.tabs li:hover ul li {background-color:white; z-index:999;}
		ul.tabs > li.sub > a:after {content:'↵';};
	</style>
	<? foreach($tpl['menu'] as $k=>$ar): ?>
		<li class="<?=($r = $tpl['tables'][$k])?> <?=($_GET['r'] == $r ? "act" : "")?> <?=($ar ? "sub" : "")?>">
			<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$conf['db']['prefix']?><?=($s = substr($r, strlen($conf['db']['prefix'])))?>">
				<? if($n = $conf['settings'][$s]): ?>
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
								<? if($n = $conf['settings'][$s]): ?>
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
				}).on("click", "a.inc", function(e){
					var line_id = $(e.currentTarget).parents("[line_id]").attr("line_id");
					$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/null?<? foreach($_GET['where'] as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>", {inc:line_id}, function(request){
//						if(isNaN(request)){ alert(request) }else{
							console.log("request:", request);
							var main = $(e.currentTarget).parents("[line_id]");
							var prev = $(main).prev("[line_id]");

							if($(prev).length == 0){
								document.location.reload(true);
							}else{
								var main_id = $(main).attr("line_id");
								var prev_id = $(prev).attr("line_id");
								$(main).find(".sort span").text(request[main_id].sort);
								$(prev).find(".sort span").text(request[prev_id].sort);
								$(main).insertBefore(prev).promise().done(function(){
									$(main).css("background-color", "#f4f4f4").promise().done(function(){
										setTimeout(function(){
											$(main).css("background-color", "inherit");
										}, 500);
									});
								});
							}
//						}
					}, "json").fail(function(error) {
						console.error("error:", error);
						alert(error.responseText);
					});
				}).on("click", "a.dec", function(e){
					var line_id = $(e.currentTarget).parents("[line_id]").attr("line_id");
					$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/null?<? foreach($_GET['where'] as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>", {dec:line_id}, function(request){
							console.log("request:", request);
							var main = $(e.currentTarget).parents("[line_id]");
							var next = $(main).next("[line_id]");

							if($(next).length == 0){
								document.location.reload(true);
							}else{
								var main_id = $(main).attr("line_id");
								var next_id = $(next).attr("line_id");
								$(main).find(".sort span").text(request[main_id].sort);
								$(next).find(".sort span").text(request[next_id].sort);
								$(main).insertAfter(next).promise().done(function(){
									$(main).css("background-color", "#f4f4f4").promise().done(function(){
										setTimeout(function(){
											$(main).css("background-color", "inherit");
										}, 500);
									});
								});;
							}
					}, "json").fail(function(error) {
						console.error("error:", error);
						alert(error.responseText);
					});
				}).on("click", ".imgs a.del", function(e){
					if(e.ctrlKey || confirm("Удалить элемент?")){
						var line_id = $(e.currentTarget).parents("[line_id]").attr("line_id");
						var fn = $(e.currentTarget).parents("[fn]").attr("fn");
						var post = {}; post[fn] = "";
						$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/"+ line_id+ "/null", post, function(response){
							console.log("line_id:", line_id, "response:", response);
							document.location.reload(true);
						})
					}
				}).on("click", "select", function(e){
					if(e.metaKey){
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
		<form action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?><?=(array_key_exists("edit", $_GET) ? "/{$_GET['edit']}" : "")?>/null" method="post" enctype="multipart/form-data">
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
											document.location.href = "/<?=$arg['modpath']?>:<?=$arg['fe']?>/r:<?=$_GET['r']?>?<? foreach($_GET['where'] as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>&p=<?=(int)$_GET['p']?>";
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
					<? if(array_key_exists('title', $tpl) && !array_key_exists("edit", $_GET)): ?>
						<span style="width:10%; padding-left:20px;">
							<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/edit?<? foreach($_GET['where'] as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?><?=($_GET['p'] ? "&p={$_GET['p']}" : "")?>">
								<button type="button">Добавить</button>
							</a>
						</span>
					<? endif; ?>
					<? if(!array_key_exists('edit', $tpl)): ?>
						<span><?=$tpl['pager']?></span>
					<? endif; ?>
					<span style="width:30%; padding-right:20px; text-align:right;">
						<? foreach(mpreaddir("/modules/{$arg['modpath']}/", true) as $_file): ?>
							<? if((strpos($_file, "admin_") === 0) && (array_pop(explode(".", $_file)) == "tpl")): ?>
								<a href="/<?=$arg['modpath']?>:<?=($_short = array_shift(explode(".", $_file)))?>"><?=implode("_", (array_slice(explode("_", $_short), 1)))?></a>
							<? endif; ?>
						<? endforeach; ?>
						<a href="/sqlanaliz:admin_sql/r:<?=$_GET['r']?>">
							<?=($conf['settings'][ $t = implode("_", array_slice(explode("_", $_GET['r']), 1)) ] ?: $t)?>
						</a>
					</span>
				</div>
			</div>
			<div class="table">
				<? if(array_key_exists('title', $tpl) && array_key_exists("edit", $_GET)): ?>
					<div class="th">
						<span style="width:15%;">Поле</span>
						<span>Значение</span>
					</div>
					<? foreach($tpl['fields'] as $field): ?>
						<div>
							<span style="text-align:right;">
								<? if($field['Comment']): ?>
									<span class="info" title="<?=$field['Comment']?>">?</span>
								<? endif; ?>
								<? if($tpl['etitle'][$field['Field']]): ?>
									<?=$tpl['etitle'][$field['Field']]?>
								<? elseif(substr($field['Field'], -3) == "_id"): ?>
									<?=($conf['settings']["{$arg['modpath']}_". substr($field['Field'], 0, -3)] ?: substr($field['Field'], 0, -3))?>
								<? else: ?>
									<?=htmlspecialchars($field['Field'])?>
								<? endif; ?>
							</span>
							<span>
								<? if($field['Field'] == "id"): # Вертикальное отображение ?>
									<?=($tpl['edit']['id'] ?: "Номер записи назначаеся ситемой")?>
								<? elseif(array_search($field['Field'], array(1=>"img", "img2", "img3"))): ?>
									<input type="file" name="<?=$field['Field']?>[]" multiple="true">
								<? elseif($field['Field'] == "file"): ?>
									<input type="file" name="file[]" multiple="true">
								<? elseif($field['Field'] == "hide"): ?>
									<select name="hide">
										<? foreach($tpl['spisok']['hide'] as $k=>$v): ?>
											<option value="<?=$k?>" <?=((!$tpl['edit'] && ($field['Default'] == $k)) || ($k == $tpl['edit']['hide']) ? "selected" : "")?>><?=$v?></option>
										<? endforeach; ?>
									</select>
								<? elseif($field['Field'] == "uid"): ?>
									<select name="<?=$field['Field']?>">
										<option></option>
										<? foreach(rb("{$conf['db']['prefix']}users") as $uid): ?>
											<option value="<?=$uid['id']?>" <?=((array_key_exists("edit", $tpl) && ($tpl['edit'][ $field['Field'] ] == $uid['id'])) || (!array_key_exists("edit", $tpl) && ($conf['user']['uid'] == $uid['id'])) ? "selected" : "")?>><?=$uid['name']?></option>
										<? endforeach; ?>
									</select>
								<? elseif($field['Field'] == "gid"): ?>
									<select name="<?=$field['Field']?>">
										<option></option>
										<? foreach(rb("{$conf['db']['prefix']}users_grp") as $gid): ?>
											<option value="<?=$gid['id']?>" <?=((array_key_exists("edit", $tpl) && ($tpl['edit'][ $field['Field'] ] == $gid['id'])) || (!array_key_exists("edit", $tpl) && ($conf['user']['uid'] == $uid['id'])) ? "selected" : "")?>><?=$uid['name']?></option>
										<? endforeach; ?>
									</select>
								<? elseif($field['Field'] == "mid"): ?>
									<select name="<?=$field['Field']?>">
										<option></option>
										<? foreach(rb("{$conf['db']['prefix']}modules") as $modules): ?>
											<option value="<?=$mid['id']?>" <?=((array_key_exists("edit", $tpl) && ($tpl['edit'][ $field['Field'] ] == $modules['id'])) || (!array_key_exists("edit", $tpl) && ($conf['user']['uid'] == $modules['id'])) ? "selected" : "")?>><?=$modules['name']?></option>
										<? endforeach; ?>
									</select>
								<? elseif(array_search($field['Field'], array(1=>"time", "last_time", "reg_time", "up"))): # Поле времени ?>
									<input type="text" name="<?=$field['Field']?>" value="<?=date("Y-m-d H:i:s", ($tpl['edit'][ $field['Field'] ] ? $tpl['edit'][ $field['Field'] ] : time()))?>" placeholder="<?=($tpl['etitle'][$field['Field']] ?: $field['Field'])?>">
								<? elseif(substr($field['Field'], -3) == "_id"): # Поле вторичного ключа связанной таблицы ?>
									<select name="<?=$field['Field']?>" style="width:100%;">
										<? if(!rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($field['Field'], 0, -3), "id", $tpl['edit'][$field['Field']])): ?>
											<option><?=htmlspecialchars($tpl['edit'][$field['Field']])?></option>
										<? endif; ?>
										<option></option>
										<? foreach(rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($field['Field'], 0, -3)) as $ln): ?>
											<option value="<?=$ln['id']?>" <?=(($tpl['edit'] && ($tpl['edit'][ $field['Field'] ] == $ln['id'])) || (!$tpl['edit'] && ($ln['id'] == $_GET['where'][ $field['Field'] ] ?: $field['Default'])) ? "selected" : "")?>>
												<?=$ln['id']?>&nbsp;<?=$ln['name']?>
											</option>
										<? endforeach; ?>
									</select>
								<? elseif($field['Field'] == "text"): ?>
<!--									<textarea name="<?=$field['Field']?>" placeholder="<?=($tpl['etitle'][$field['Field']] ?: $field['Field'])?>"></textarea>-->
									<?=mpwysiwyg($field['Field'], $tpl['edit'][$field['Field']])?>
								<? elseif(array_key_exists($field['Field'], $tpl['espisok'])): ?>
									<select name="<?=$field['Field']?>">
										<option></option>
										<? foreach($tpl['espisok'][$field['Field']] as $espisok): ?>
											<option value="<?=$espisok['id']?>" <?=((!$tpl['edit'] && ($field['Default'] == $espisok['id'])) || ($espisok['id'] == $tpl['edit'][$field['Field']]) || (!$tpl['edit']['id'] && ($_GET['where'][ $field['Field'] ] == $espisok['id'])) ? "selected" : "")?>><?=$espisok['id']?> <?=$espisok['name']?></option>
										<? endforeach; ?>
									</select>
								<? else: # Обычное текстовове поле. Если не одно условие не сработало ?>
									<input type="text" name="<?=$field['Field']?>" value="<?=htmlspecialchars($tpl['edit'] ? rb($_GET['r'], "id", $_GET['edit'], $field['Field']) : $field['Default'])?>" placeholder="<?=($tpl['etitle'][$field['Field']] ?: $field['Field'])?>">
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
						<? foreach(array_merge((array_key_exists('title', $tpl) ? array_intersect_key($tpl['fields'], array_flip($tpl['title'])) : $tpl['fields']), (array_key_exists('counter', $tpl) ? $tpl['counter'] : array()), (array_key_exists('ecounter', $tpl) ? $tpl['ecounter'] : array())) as $fiel=>$field): ?>
							<span>
								<? if(array_key_exists('Comment', $field)): ?>
									<span class="info" title="<?=$field['Comment']?>">?</span>
								<? endif; ?>
									<? if(substr($fiel, 0, 2) == "__"): ?>
										<span title="<?=substr($fiel, 2)?>">_<?=($conf['settings'][substr($fiel, 2)] ?: substr($fiel, 2))?></span>
									<? elseif(substr($fiel, 0, 1) == "_"): ?>
										<span title="<?=substr($fiel, 1)?>"><?=($conf['settings']["{$arg['modpath']}_". substr($fiel, 1)] ?: substr($fiel, 1))?></span>
									<? elseif(array_key_exists('etitle', $tpl['etitle'])): ?>
									<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?order=<?=$fiel?>" title="<?=$fiel?>">
										<?=$tpl['etitle'][$fiel]?>
									</a>
									<? elseif(substr($fiel, -3) == "_id"): ?>
										<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?order=<?=$fiel?>" title="<?=$fiel?>"><?=($conf['settings']["{$arg['modpath']}_". substr($fiel, 0, -3)] ?: substr($fiel, 0, -3))?></a>
									<? else: ?>
								<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?order=<?=$fiel?>" title="<?=$fiel?>">
									<?=$fiel?>
								</a>
									<? endif; ?>
							</span>
						<? endforeach; ?>
					</div>
					<? if(!array_key_exists("edit", $_GET)): ?>
						<? foreach($tpl['lines'] as $lines): ?>
							<div line_id="<?=$lines['id']?>">
								<? foreach(array_merge((array_key_exists('title', $tpl) ? array_intersect_key($lines, array_flip($tpl['title'])) : $lines), array_key_exists('counter', $tpl) ? $tpl['counter'] : array(), array_key_exists($n = 'ecounter', $tpl) ? $tpl[$n] : array()) as $k=>$v): ?>
									<span>
										<? if(substr($k, 0, 2) == "__"): // $tpl['ecounter'] ?>
											<? if(($f = substr($k, 2)) && ($m = array_shift(explode("_", $f)))): ?>
												<a href="/<?=$m?>:admin/r:<?=$conf['db']['prefix']?><?=$f?>?&where[<?=($_GET['r'] == "{$conf['db']['prefix']}users" ? "uid" : substr($_GET['r'], strlen($conf['db']['prefix'])))?>]=<?=$lines['id']?>">
													<?=($v[$lines['id']]['cnt'] ? "{$v[$lines['id']]['cnt']}&nbspшт" : "Нет")?>
												</a>
											<? endif; ?>
										<? elseif(substr($k, 0, 1) == "_"): // $tpl['counter'] ?>
											<a href="/<?=$arg['modpath']?>:admin/r:<?="{$conf['db']['prefix']}{$arg['modpath']}{$k}?&where[". (($_GET['r'] == "{$conf['db']['prefix']}users") && ($k == "_mem") ? "uid" : substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={$lines['id']}"?>">
												<?=(array_key_exists($lines['id'], $tpl['counter'][$k]) && $tpl['counter'][$k][ $lines['id'] ] ? "{$tpl['counter'][$k][ $lines['id'] ]}&nbspшт" : "Нет")?>
											</a>
										<? elseif($k == "id"): ?>
											<span class="control" style="white-space:nowrap;">
												<a class="del" href="javascript:"></a>
												<a class="edit" href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?edit=<?=$v?><? foreach($_GET['where'] as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?><?=($_GET['p'] ? "&p={$_GET['p']}" : "")?>"></a>
												<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?&where[id]=<?=$v?>"><?=$v?></a>
											</span>
										<? elseif(array_search($k, array(1=>"img", "img2", "img3"))): ?>
											<div class="imgs" fn="<?=$k?>" style="position:relative; width:70px; height:70px;">
<!--												<a target="blank" href="/<?=$arg['modpath']?>:img/<?=$lines['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:<?=$k?>/w:800/h:600/null/img.png" title="<?=$v?>">-->
													<? if($lines[$k]): ?>
														<a class="del" href="javascript:void(0)" style="position:absolute; top:5px; right:5px;"><img src="/img/del.png"></a>
													<? endif; ?>
													<img src="/<?=$arg['modpath']?>:img/<?=$lines['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:<?=$k?><?=($lines[$k] ? "" : "/rand:". time())?>/w:65/h:65/null/img.png" style="border:1px solid #aaa; padding:2px;">
<!--												</a>-->
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
											<?=$tpl['spisok']['hide'][$v]?>
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
												<? if($mid = rb("{$conf['db']['prefix']}modules", "id", (int)$v)): ?>
													<a class="key" href="/users:admin/r:<?=$conf['db']['prefix']?>users?&where[id]=<?=(int)$v?>" title="<?=$v?>"></a><?=$mid['name']?>
												<? elseif($v): ?>
													<span style="color:red;" title="<?=$v?>"><?=$v?></span>
												<? endif; ?>
											</span>
										<? elseif(array_search($k, array(1=>"time", "last_time", "reg_time", "up"))): # Поле времени ?>
											<span style="white-space:nowrap;" title="<?=$v?>">
												<?=($v ? date("Y-m-d H:i:s", $v) : "")?>
											</span>
										<? elseif(substr($k, -3) == "_id"): ?>
											<? if($el = rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, -3), "id", $v)): ?>
<!--												<span style="color:#ccc;"><?=$el['id']?></span>-->
												<span style="white-space:nowrap;">
													<a class="key" href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?="{$conf['db']['prefix']}{$arg['modpath']}_"?>
														<?=substr($k, 0, -3)?>?&where[id]=<?=$v?>" title="<?=$v?>">
													</a>&nbsp;<?=htmlspecialchars($el['name'])?>
												</span>
											<? elseif($v): ?>
												<span style="color:red;"><?=$v?></span>
											<? endif; ?>
										<? elseif(array_key_exists('espisok', $tpl) && array_key_exists($k, $tpl['espisok'])): ?>
											<a class="ekey" href="/<?=array_shift(explode("_", $k))?>:admin/r:<?=$conf['db']['prefix']?><?=$k?>?&where[id]=<?=$v?>" title="<?=$v?>"></a>
											<?=(strlen($tpl['espisok'][$k][$v]['name']) > 16 ? mb_substr($tpl['espisok'][$k][$v]['name'], 0, 16, "UTF-8"). "..." : $tpl['espisok'][$k][$v]['name'])?>
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
							<? foreach(array_merge((array_key_exists('title', $tpl) ? array_intersect_key($tpl['fields'], array_flip($tpl['title'])) : $tpl['fields']), (array)$tpl['counter'], array_key_exists($n = 'ecounter', $tpl) ? $tpl[$n] : array()) as $fiel=>$field): ?>
								<span>
									<? if(substr($fiel, 0, 1) == "_"): ?>
										<input type="text" disabled>
									<? elseif($fiel == "id"): ?>
										<button type="submit"><?=(array_key_exists("edit", $_GET) ? "Редактировать" : "Добавить")?></button>
									<? elseif(array_search($fiel, array(1=>"img", "img2", "img3"))): ?>
										<input type="file" name="<?=$fiel?>[]" multiple="true">
									<? elseif($fiel == "file"): ?>
										<input type="file" name="file[]" multiple="true">
									<? elseif($fiel == "hide"): ?>
										<select name="hide">
											<? foreach($tpl['spisok']['hide'] as $k=>$v): ?>
												<option value="<?=$k?>" <?=((!$tpl['edit'] && ($field['Default'] == $k)) || ($k == $tpl['edit'][$fiel]) ? "selected" : "")?>><?=$v?></option>
											<? endforeach; ?>
										</select>
									<? elseif($fiel == "uid"): ?>
										<select name="<?=$fiel?>">
											<? if($tpl['edit'][$fiel] && !rb("{$conf['db']['prefix']}users", "id", $tpl['edit'][$fiel])): ?>
												<option value="<?=$tpl['edit'][$fiel]?>" selected><?=$tpl['edit'][$fiel]?></option>
											<? endif; ?>
											<option></option>
											<? foreach(rb("{$conf['db']['prefix']}users") as $uid): ?>
												<option value="<?=$uid['id']?>" <?=(($tpl['edit'] && ($tpl['edit'][ $fiel ] == $uid['id'])) || (!$tpl['edit'] && ($uid['id'] == $conf['user']['uid'])) ? "selected" : "")?>>
													<?=$uid['id']?> <?=$uid['name']?>
												</option>
											<? endforeach; ?>
										</select>
									<? elseif($fiel == "gid"): ?>
										<select name="<?=$fiel?>">
											<? if($tpl['edit'][$fiel] && !rb("{$conf['db']['prefix']}users", "id", $tpl['edit'][$fiel])): ?>
												<option value="<?=$tpl['edit'][$fiel]?>" selected><?=$tpl['edit'][$fiel]?></option>
											<? endif; ?>
											<option></option>
											<? foreach(rb("{$conf['db']['prefix']}users_grp") as $gid): ?>
												<option value="<?=$gid['id']?>" <?=(($tpl['edit'] && ($tpl['edit'][ $fiel ] == $gid['id'])) || (!$tpl['edit'] && ($gid['id'] == $conf['user']['uid'])) ? "selected" : "")?>>
													<?=$gid['id']?> <?=$gid['name']?>
												</option>
											<? endforeach; ?>
										</select>
									<? elseif($fiel == "mid"): ?>
										<select name="<?=$fiel?>">
											<? if($tpl['edit'][$fiel] && !rb("{$conf['db']['prefix']}modules", "id", $tpl['edit'][$fiel])): ?>
												<option value="<?=$tpl['edit'][$fiel]?>" selected><?=$tpl['edit'][$fiel]?></option>
											<? endif; ?>
											<option></option>
											<? foreach(rb("{$conf['db']['prefix']}modules") as $mid): ?>
												<option value="<?=$mid['id']?>" <?=(($tpl['edit'] && ($tpl['edit'][ $fiel ] == $uid['id'])) || (!$tpl['edit'] && ($mid['id'] == $conf['user']['uid'])) ? "selected" : "")?>>
													<?=$mid['id']?> <?=$mid['name']?>
												</option>
											<? endforeach; ?>
										</select>
									<? elseif(array_search($field['Field'], array(1=>"time", "last_time", "reg_time", "up"))): # Поле времени ?>
										<input type="text" name="<?=$fiel?>" value="<?=date("Y-m-d H:i:s", ($edit ? rb($_GET['r'], "id", $_GET['edit'], $fiel) : time()))?>" placeholder="<?=($tpl['etitle'][$fiel] ?: $fiel)?>">
									<? elseif((substr($fiel, -3) == "_id") && (false === array_search(substr($fiel, 0, strlen($fiel)-3), explode(",", (array_key_exists($n = "{$arg['modpath']}_tpl_exceptions", $conf['settings']) ? $conf['settings'][$n] : ""))))): # Поле вторичного ключа связанной таблицы ?>
										<select name="<?=$fiel?>" style="width:100%;">
											<? if(array_key_exists("edit", $tpl) && !rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($fiel, 0, -3), "id", $tpl['edit'][$fiel])): ?>
												<option><?=htmlspecialchars($tpl['edit'][$fiel])?></option>
											<? else: ?>
												<option></option>
											<? endif; ?>
											<? foreach(rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($fiel, 0, -3)) as $ln): ?>
												<option value="<?=$ln['id']?>" <?=((array_key_exists('edit', $tpl) && ($tpl['edit'][$fiel] == $ln['id'])) || (!array_key_exists('edit', $tpl) && ($ln['id'] == ((array_key_exists('where', $_GET) && array_key_exists($fiel, $_GET['where'])) ?: $field['Default']))) ? "selected" : "")?>>
													<?=$ln['id']?>&nbsp;<?=htmlspecialchars($ln['name'])?>
												</option>
											<? endforeach; ?>
										</select>
									<? elseif(array_key_exists('espisok', $tpl) && array_key_exists($fiel, $tpl['espisok'])): ?>
										<select name="<?=$fiel?>">
											<option></option>
											<? foreach($tpl['espisok'][$fiel] as $espisok): ?>
												<option value="<?=$espisok['id']?>" <?=((!$tpl['edit'] && ($field['Default'] == $espisok['id'])) || ($espisok['id'] == $tpl['edit'][$fiel]) ? "selected" : "")?>><?=$espisok['id']?> <?=$espisok['name']?></option>
											<? endforeach; ?>
										</select>
									<? else: # Обычное текстовове поле. Если не одно условие не сработало ?>
										<input type="text" name="<?=$fiel?>" value="<?=htmlspecialchars(array_key_exists('edit', $tpl) ? rb($_GET['r'], "id", $_GET['edit'], $fiel) : $field['Default'] ?: (array_key_exists('where', $_GET) && array_key_exists($fiel, $_GET['where'])) ? $_GET['where'][$fiel] : "")?>" placeholder="<?=(array_key_exists($fiel, $tpl['etitle']) ? $tpl['etitle'][$fiel] : $fiel)?>">
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
								});
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
