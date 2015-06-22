<? if($conf['modules']['sqlanaliz']['access'] > 4): ?>
	<span style="float:right; line-height:25px; padding:0 5px;">
		<a href="/sqlanaliz:admin/r:1/tab:<?=$_GET['r']?>">
			<?=(substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_") ?: "_"))?>
		</a>
	</span>
<? endif; ?>
<ul class="nl tabs">
	<? foreach($tpl['tables'] as $r): ?>
		<li class="<?=$r?> <?=($_GET['r'] == $r ? "act" : "")?>">
			<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?=$conf['db']['prefix']?><?=($s = substr($r, strlen($conf['db']['prefix'])))?>">
				<? if($n = $conf['settings'][$s]): ?>
					<?=$n?>
				<? else: ?>
					<?=(($i = substr($r, strlen("{$conf['db']['prefix']}{$arg['modname']}"))) ? $conf['modules'][$arg['modname']]['name'] : "_")?>
				<? endif; ?>
			</a>
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
			.table a.del {display:inline-block; background:url(i/mgif.gif); background-position:0 -56px; width:16px; height:16px;}
			.table a.key {display:inline-block; background:url(i/mgif.gif); background-position:-2px -155px; width:16px; height:16px; opacity:0.3}
			.table a.ekey {display:inline-block; background:url(i/mgif.gif); background-position:-20px -155px; width:16px; height:16px; opacity:0.3}
		</style>
		<script>
			(function($, script){
				$(script).parent().on("click", "a.del", function(e){
					if(confirm("Удалить элемент?")){
						var line_id = $(e.currentTarget).parents("[line_id]").attr("line_id");
						$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/"+line_id+"/null", {id:0}, function(data){
							if(isNaN(data)){ alert(data) }else{
								$(e.currentTarget).parents("[line_id]").remove();
							}
						})
					}
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
		<form action="/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?><?=(array_key_exists("edit", $_GET) ? "/{$_GET['edit']}" : "")?>/null" method="post" enctype="multipart/form-data">
			<script src="/include/jquery/jquery.iframe-post-form.js"></script>
			<script>
				(function($, script){
					$(script).parent().one("init", function(e){
						setTimeout(function(){
							$(e.delegateTarget).iframePostForm({
								complete:function(data){
									try{
										if(json = JSON.parse(data)){
											console.log("json:", json);
//											document.location.href = "/<?=$arg['modname']?>:<?=$arg['fe']?>/r:<?=$_GET['r']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : "")?>";
//											document.location.reload(true);
											document.location.href = "/<?=$arg['modname']?>:<?=$arg['fe']?>/r:<?=$_GET['r']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : "")?>?<? foreach($_GET['where'] as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>";
										}
									}catch(e){
										if(isNaN(data)){ alert(data) }else{
											console.log("date:", data)
										}
									}
								}
							});
						}, 200)
					}).trigger("init")
				})(jQuery, document.scripts[document.scripts.length-1])
			</script>
			<div class="table" style="margin-left:10px;">
				<div>
					<? if($tpl['title'] && !array_key_exists("edit", $_GET)): ?>
						<span style="width:80px;"><a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : "")?>/edit"><button type="button">Добавить</button></a></span>
					<? endif; ?>
					<? if(!$tpl['edit']): ?>
						<span><?=$tpl['pager']?></span>
					<? endif; ?>
				</div>
			</div>
			<div class="table">
				<? if($tpl['title'] && array_key_exists("edit", $_GET)): ?>
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
									<?=($conf['settings']["{$arg['modname']}_". substr($field['Field'], 0, -3)] ?: substr($field['Field'], 0, -3))?>
								<? else: ?>
									<?=$field['Field']?>
								<? endif; ?>
							</span>
							<span>
								<? if($field['Field'] == "id"): ?>
									<?=($tpl['edit']['id'] ?: "Номер записи назначаеся ситемой")?>
								<? elseif($field['Field'] == "img"): ?>
									<input type="file" name="img">
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
								<? elseif(array_search($field['Field'], array(1=>"time", "last_time", "reg_time", "up"))): # Поле времени ?>
									<input type="text" name="<?=$field['Field']?>" value="<?=date("Y-m-d H:i:s", ($edit ? rb($_GET['r'], "id", $edit['id'], $field['Field']) : time()))?>" placeholder="<?=($tpl['etitle'][$field['Field']] ?: $field['Field'])?>">
								<? elseif(substr($field['Field'], -3) == "_id"): # Поле вторичного ключа связанной таблицы ?>
									<select name="<?=$field['Field']?>" style="width:100%;">
										<option></option>
										<? foreach(rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($field['Field'], 0, -3)) as $ln): ?>
											<option value="<?=$ln['id']?>" <?=(($tpl['edit'] && ($tpl['edit'][ $field['Field'] ] == $ln['id'])) || (!$tpl['edit'] && ($field['Default'] == $ln['id'])) ? "selected" : "")?>>
												<?=$ln['id']?>&nbsp;<?=$ln['name']?>
											</option>
										<? endforeach; ?>
									</select>
								<? elseif($field['Field'] == "text"): ?>
<!--									<textarea name="<?=$field['Field']?>" placeholder="<?=($tpl['etitle'][$field['Field']] ?: $field['Field'])?>"></textarea>-->
									<?=mpwysiwyg($field['Field'], $tpl['edit'][$field['Field']])?>
								<? else: # Обычное текстовове поле. Если не одно условие не сработало ?>
									<input type="text" name="<?=$field['Field']?>" value="<?=($tpl['edit'] ? rb($_GET['r'], "id", $_GET['edit'], $field['Field']) : $field['Default'])?>" placeholder="<?=($tpl['etitle'][$field['Field']] ?: $field['Field'])?>">
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
						<? foreach(array_merge(($tpl['title'] ? array_intersect_key($tpl['fields'], array_flip($tpl['title'])) : $tpl['fields']), (array)$tpl['counter'], (array)$tpl['ecounter']) as $fiel=>$field): ?>
							<span>
								<? if($field['Comment']): ?>
									<span class="info" title="<?=$field['Comment']?>">?</span>
								<? endif; ?>
									<? if(substr($fiel, 0, 1) == "_"): ?>
										<?=($conf['settings']["{$arg['modname']}_". substr($fiel, 1)] ?: substr($fiel, 1))?>
									<? elseif($tpl['etitle'][$fiel]): ?>
									<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?order=<?=$fiel?>">
											<?=$tpl['etitle'][$fiel]?>
									</a>
									<? elseif(substr($fiel, -3) == "_id"): ?>
										<?=($conf['settings']["{$arg['modname']}_". substr($fiel, 0, -3)] ?: substr($fiel, 0, -3))?>
									<? else: ?>
								<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?order=<?=$fiel?>">
										<?=$fiel?>
								</a>
									<? endif; ?>
							</span>
						<? endforeach; ?>
					</div>
					<? if(!array_key_exists("edit", $_GET)): ?>
						<? foreach($tpl['lines'] as $lines): ?>
							<div line_id="<?=$lines['id']?>">
								<? foreach(array_merge(($tpl['title'] ? array_intersect_key($lines, array_flip($tpl['title'])) : $lines), (array)$tpl['counter'], (array)$tpl['ecounter']) as $k=>$v): ?>
									<span>
										<? if(substr($k, 0, 2) == "__"): // $tpl['ecounter'] ?>
											<? if(($f = substr($k, 2)) && ($m = array_shift(explode("_", $f)))): ?>
												<a href="/<?=$m?>:admin/r:<?=$conf['db']['prefix']?><?=$f?>?where[<?=(substr($_GET['r'], strlen($conf['db']['prefix'])))?>]=<?=$lines['id']?>">
													<?=($v[$lines['id']]['cnt'] ? "{$v[$lines['id']]['cnt']}&nbspшт" : "Нет")?>
												</a>
											<? endif; ?>
										<? elseif(substr($k, 0, 1) == "_"): // $tpl['counter'] ?>
											<a href="/<?=$arg['modname']?>:admin/r:<?="{$conf['db']['prefix']}{$arg['modpath']}{$k}?where[". substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id]={$lines['id']}"?>">
												<?=($tpl['counter'][$k][ $lines['id'] ] ? "{$tpl['counter'][$k][ $lines['id'] ]}&nbspшт" : "Нет")?>
											</a>
										<? elseif($k == "id"): ?>
											<span style="white-space:nowrap;">
												<a class="del" href="javascript:"></a>
												<a class="edit" href="/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?edit=<?=$v?><? foreach($_GET['where'] as $f=>$w): ?>&where[<?=$f?>]=<?=$w?><? endforeach; ?>"></a>
												<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>?where[id]=<?=$v?>"><?=$v?></a>
											</span>
										<? elseif($k == "img"): ?>
											<a target="blank" href="/<?=$arg['modname']?>:img/<?=$lines['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:img/w:800/h:600/null/img.png" title="<?=$v?>">
												<img src="/<?=$arg['modname']?>:img/<?=$lines['id']?>/tn:<?=substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>/fn:img/w:65/h:65/null/img.png" style="border:1px solid #aaa; padding:2px;">
											</a>
										<? elseif($k == "sort"): ?>
											<div style="white-space:nowrap;">
												<a class="inc" href="javascript:void(0);" style="background:url(i/mgif.gif); background-position:-18px -90px; width: 10px; height: 14px; display:inline-block;"></a>
												<?=$v?>
												<a class="inc" href="javascript:void(0);" style="background:url(i/mgif.gif); background-position:0 -90px; width: 10px; height: 14px; display:inline-block;"></a>
											</div>
										<? elseif($k == "hide"): ?>
											<?=$tpl['spisok']['hide'][$v]?>
										<? elseif($k == "uid"): ?>
											<span style="white-space:nowrap;">
												<? if($uid = rb("{$conf['db']['prefix']}users", "id", (int)$v)): ?>
<!--													<span style="color:#ddd;"><?=$v?></span>-->
													<a class="key" href="/users:admin/r:<?=$conf['db']['prefix']?>users?where[id]=<?=(int)$v?>" title="<?=$v?>"></a><?=$uid['name']?>
												<? elseif($v): ?>
													<span style="color:red;" title="<?=$v?>">
														<?=$v?>
													</span>
												<? endif; ?>
											</span>
										<? elseif(array_search($k, array(1=>"time", "last_time", "reg_time", "up"))): # Поле времени ?>
											<span style="white-space:nowrap;" title="<?=$v?>">
												<?=date("Y-m-d H:i:s", $v)?>
											</span>
										<? elseif(substr($k, -3) == "_id"): ?>
												<? if($el = rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, -3), "id", $v)): ?>
<!--													<span style="color:#ccc;"><?=$el['id']?></span>-->
													<span style="white-space:nowrap;">
														<a class="key" href="/<?=$arg['modname']?>:<?=$arg['fn']?>/r:<?="{$conf['db']['prefix']}{$arg['modpath']}_"?><?=substr($k, 0, -3)?>?where[id]=<?=$v?>" title="<?=$v?>"></a>&nbsp;<?=$el['name']?>
													</span>
												<? elseif($v): ?>
													<span style="color:red;"><?=$v?></span>
												<? endif; ?>
										<? elseif($tpl['espisok'][$k]): ?>
											<a class="ekey" href="/invest:admin/r:<?=$conf['db']['prefix']?><?=$k?>?where[id]=<?=$v?>" title="<?=$v?>"></a>
											<?=(strlen($tpl['espisok'][$k][$v]['name']) > 16 ? mb_substr($tpl['espisok'][$k][$v]['name'], 0, 16, "UTF-8"). "..." : $tpl['espisok'][$k][$v]['name'])?>
										<? else: ?>
											<?=$v?>
										<? endif; ?>
									</span>
								<? endforeach; ?>
							</div>
						<? endforeach; ?>
					<? endif; ?>
					<? if(empty($tpl['title'])): ?>
						<div>
							<? foreach(array_merge(($tpl['title'] ? array_intersect_key($tpl['fields'], array_flip($tpl['title'])) : $tpl['fields']), (array)$tpl['counter'], (array)$tpl['ecounter']) as $fiel=>$field): ?>
								<span>
									<? if(substr($fiel, 0, 1) == "_"): ?>
										<input type="text" disabled>
									<? elseif($fiel == "id"): ?>
										<button type="submit"><?=(array_key_exists("edit", $_GET) ? "Редактировать" : "Добавить")?></button>
									<? elseif($fiel == "img"): ?>
										<input type="file" name="img">
									<? elseif($fiel == "hide"): ?>
										<select name="hide">
											<? foreach($tpl['spisok']['hide'] as $k=>$v): ?>
												<option value="<?=$k?>" <?=((!$tpl['edit'] && ($field['Default'] == $k)) || ($k == $tpl['edit']['hide']) ? "selected" : "")?>><?=$v?></option>
											<? endforeach; ?>
										</select>
									<? elseif($fiel == "uid"): ?>
										<select name="<?=$fiel?>">
											<option></option>
											<? foreach(rb("{$conf['db']['prefix']}users") as $uid): ?>
												<option value="<?=$uid['id']?>" <?=(($tpl['edit'] && ($tpl['edit'][ $fiel ] == $uid['id'])) || (!$tpl['edit'] && ($uid['id'] == $conf['user']['uid'])) ? "selected" : "")?>>
													<?=$uid['id']?> <?=$uid['name']?>
												</option>
											<? endforeach; ?>
										</select>
									<? elseif(array_search($field['Field'], array(1=>"time", "last_time", "reg_time", "up"))): # Поле времени ?>
										<input type="text" name="<?=$fiel?>" value="<?=date("Y-m-d H:i:s", ($edit ? rb($_GET['r'], "id", $_GET['edit'], $fiel) : time()))?>" placeholder="<?=($tpl['etitle'][$fiel] ?: $fiel)?>">
									<? elseif((substr($fiel, -3) == "_id") && (false === array_search(substr($fiel, 0, strlen($fiel)-3), explode(",", $conf['settings']["{$arg['modpath']}_tpl_exceptions"])))): # Поле вторичного ключа связанной таблицы ?>
										<select name="<?=$fiel?>" style="width:100%;">
											<option><?=($ln = rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($fiel, 0, -3), "id", $tpl['edit'][$fiel]) ? "" : $tpl['edit'][$fiel])?></option>
											<? foreach(rb("{$conf['db']['prefix']}{$arg['modpath']}_". substr($fiel, 0, -3)) as $ln): ?>
												<option value="<?=$ln['id']?>" <?=(($tpl['edit'] && ($tpl['edit'][$fiel] == $ln['id'])) || (!$tpl['edit'] && ($ln['id'] == ($_GET['where'][$fiel] ?: $field['Default']))) ? "selected" : "")?>>
													<?=$ln['id']?>&nbsp;<?=$ln['name']?>
												</option>
											<? endforeach; ?>
										</select>
									<? else: # Обычное текстовове поле. Если не одно условие не сработало ?>
										<input type="text" name="<?=$fiel?>" value="<?=($tpl['edit'] ? rb($_GET['r'], "id", $_GET['edit'], $fiel) : $field['Default'])?>" placeholder="<?=($tpl['etitle'][$fiel] ?: $fiel)?>">
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
							$.get("/?m[sqlanaliz]=admin&r=1&new=<?=$_GET['r']?>&null", function(data){
								console.log("get:", data);
								$.post("/?m[settings]=admin", {modpath:"<?=$arg['modpath']?>", name:"<?=substr($_GET['r'], strlen($conf['db']['prefix']))?>", value:value, aid:4, add:'добавить'}, function(data){
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
