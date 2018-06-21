<div class="sql analiz">
	<? if($file = get($tpl, 'file')): ?>
		<? mpre($file); ?>
	<? elseif($dump = get($tpl, 'dump')): ?>		
		<h1>mysqldump</h1>
		<pre style="margin:5px; padding:5px; border-top:2px solid #ddd; border-bottom:2px solid #ddd;"><?=$dump?></pre>
	<? else: ?>
		<div class="table" style="width:100%;">
			<div>
				<span style="min-width:40%;">
					<style>
						.sql.analiz .table {width:30%;}
						.sql.analiz .table>div>span {padding:2px;}
					</style>
					<p>
						<form table="<?=$_GET['r']?>" action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/null" method="post">
							<script sync>
								(function($, script){
									$(script).parent().one("init", function(e){
									}).on("click", "a.del", function(e){
										var table = $(e.delegateTarget).attr("table");
										if(confirm("Подтвердите удаление таблицы `"+ table+ "`")){
											$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/null", {del:table}, function(data){
												console.log("Добавление таблицы", data);
											}, 'json').error(function(error){
												alert(error.responseText);
											}).done(function(json){ // /bmf:admin/r:mp_bmf_test
//												document.location.href = "/<?=$arg['modpath']?>:<?=$arg['fn']?>";
												document.location.href = "/<?=first(array_slice(explode('_', $_GET['r']), 1, 2))?>:admin/r:<?=$_GET['r']?>";
											});
										}
									})
								})(jQuery, document.scripts[document.scripts.length-1]);
							</script>
							<span style="float:right;">
								<a class="del" href="javascript:void(0)">Удалить</a>
							</span>
<!--							<input type="text" name="table" value="" list="tables" placeholder="<?=get($_GET, 'r')?>">
							<datalist id="tables">
								<? foreach($tpl['tables'] as $table=>$row): ?>
									<option><?=$table?></option>
								<? endforeach; ?>
							</datalist>
							<button>Создать</button>-->
							<? if(!$table = get($_GET, 'r')): mpre("Имя таблицы не задано") ?>
							<? elseif(!$ar = explode("_", $table)): mpre("ОШИБКА разбивки таблицы по элементам") ?>
							<? else: ?>
								<span style="padding:0 10px;">
									таблица `<b><?=(get($conf, 'settings', implode("_", array_slice($ar, 1))) ?: get($_GET, 'r'))?></b>`
									<a href="/<?=last(array_slice($ar, 1, 1))?>:admin/r:<?=$_GET['r']?>">
										<?=$table?>
									</a>
								</span>
							<? endif; ?>
						</form>
					</p>
					
					<? if(get($_GET, 'r') && ($fields = fields($_GET['r']))): ?>
						<form action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>" method="post">
							<div class="table">
								<? foreach($fields as $field=>$row): ?>
									<? if(($default = (gettype($d = get($fields, $field, 'Default')) !== null ? $d : get($fields, $field, 'dflt_value'))) &&0): mpre("Значение по умолчанию для поля") ?>
									<? else:// mpre($default) ?>
										<div>
											<span><input type="text" name="f[<?=$field?>][name]" value="<?=$field?>" style="width:100px;" placeholder="Название"></span>
											<span>
												<select name="f[<?=$field?>][after]">
													<option></option>
													<? foreach($fields as $f=>$r): ?>
														<option><?=$f?></option>
													<? endforeach; ?>
												</select>
											</span>
											<span>
												<select name="f[<?=$field?>][type]">
													<option></option>
													<? foreach($tpl['types'] as $fd): ?>
														<option <?=((get($tpl, 'fields', $field, 'Type') == $fd) || (get($tpl, 'fields', $field, 'type') == $fd) ? "selected" : "")?>><?=$fd?></option>
													<? endforeach; ?>
												</select>
											</span>
											<span>
												<input type="text" value="<?=$default?>" name="f[<?=$field?>][default]" style="width:60px;" placeholder="Значение">
											</span>
											<span><input type="text" value="<?=get($fields, $field, 'Comment')?>" name="f[<?=$field?>][comment]" placeholder="Коментарий" <?=(get($conf, 'db', 'type') == 'mysql' ? "" : "disabled")?>></span>
											<span><input type="checkbox" name="f[<?=$field?>][index]" <?=((get($tpl, 'indexes', $field) || get($tpl, 'indexes', substr($_GET['r'], strlen($conf['db']['prefix'])). "-{$field}")) ? "checked" : "")?>></span>
										</div>
									<? endif; ?>
								<? endforeach; ?>
								<div>
									<span><input type="text" name="$[name]" style="width:100px;" placeholder="Название"></span>
									<span>
										<select name="$[after]">
											<? foreach($fields as $f=>$r): ?>
												<option selected><?=$f?></option>
											<? endforeach; ?>
										</select>
									</span>
									<span>
										<select name="$[type]">
											<? foreach($tpl['types'] as $fd): ?>
												<option><?=$fd?></option>
											<? endforeach; ?>
										</select>
									</span>
									<span><input type="text" name="$[default]" style="width:60px;" placeholder="Значение"></span>
									<span><input type="text" name="$[comment]" placeholder="Коментарий" <?=(get($conf, 'db', 'type') == 'mysql' ? "" : "disabled")?>></span>
									<span><input type="checkbox" name="$[index]"></span>
								</div>
							</div>
							<p><button>Сохранить</button></p>
						</form>
					<? endif; ?>
					<? if(($conf['db']['type'] == 'sqlite')): ?>
						<? if(!$sql = "PRAGMA foreign_key_list('{$_GET['r']}')"): mpre("Ошибка составления запроса списка вторичных ключей") ?>
						<? elseif(!is_array($data = mpql(mpqw($sql)))): mpre("Ошибка запроса к БД") ?>
						<? elseif(!$FIELDS = fields($_GET['r'])): mpre("Поля таблицы не найдены `{$_GET['r']}`") ?>
						<? elseif(!$sql = "PRAGMA foreign_key_list({$_GET['r']});"): mpre("Ошибка получения информации о вторичных ключах") ?>
						<? elseif(!is_array($FOREIGN_KEYS = mpqn(mpqw($sql), "from"))): mpre("Ошибка выполнения выборки вторичных ключей") ?>
						<? else:// mpre($sql, $FOREIGN_KEYS) ?>
							<div class="table">
								<script sync>
									(function($, script){
										$(script).parent().on("click", "button", function(e){
											var field = $(e.currentTarget).parents("[field]").attr("field");
											var on_update = $(e.currentTarget).parents("[field]").find("select[name=on_update] option:selected").attr("value");
											var on_delete = $(e.currentTarget).parents("[field]").find("select[name=on_delete] option:selected").attr("value");
											console.log("field:", field, "on_update:", on_update, "on_delete:", on_delete);
											$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/null", {foreign:field, on_update:on_update, on_delete:on_delete}, function(data){
												console.log("data:", data);
												document.location.reload(true);
											}, "json").fail(function(error){ alert(error.responseText); })
										})
									})(jQuery, document.currentScript)
								</script>
								<div class="th">
									<span>Поле</span>
									<span>Таблица</span>
									<span>Обновление</span>
									<span>Удаление</span>
									<span>Действие</span>
								</div>
								<? foreach($FIELDS as $field=>$fld): ?>
									<? //if(substr($field, -3) != "_id"):// mpre("Поле не является вторичным ключем `{$field}`"); ?>
									<? if(!call_user_func(function() use($field){ # Проверка на соответствие поля критериям вторичного ключа
											if(substr($field, -3) != "_id"){ return $field; mpre("Вторичный ключ внутри таблицы");
											}elseif(!$ex = explode('-', $field)){ mpre("Раскладываем имя поля по элементам");
											}elseif(2 == $ex){ return $field; mpre("Вторичное поле вне раздела вида `pages-index`");
											}else{// mpre("Поле {$field} не является вторичным ключем");
											}
										})): // mpre("Поле не является вторичным ключем `{$field}`"); ?>
									<? elseif((!$foreign_keys = get($FOREIGN_KEYS, $field)) &0): mpre("Вторичный ключ поля") ?>
									<? else:// mpre($sql, $foreign_keys) ?>
										<div field="<?=$field?>">
											<span style="white-space:nowrap;"><?=$field?></span>
											<span><?=$_GET['r']?></span>
											<span>
												<? if($on_update = get($foreign_keys, 'on_update')): ?>
													<?=$on_update?>
												<? else: ?>
													<select name="on_update">
														<option value="NO ACTION" selected></option>
														<option value="SET NULL">Нуль</option>
														<option value="SET DEFAULT">Умолчание</option>
														<option value="RESTRICT">Блок</option>
														<option value="CASCADE">Удалить</option>
													</select>
												<? endif; ?>
											</span>
											<span>
												<? if($on_delete = get($foreign_keys, 'on_delete')): ?>
													<?=$on_delete?>
												<? else: ?>
													<select name="on_delete">
														<option value="NO ACTION"></option>
														<option value="SET NULL">Нуль</option>
														<option value="SET DEFAULT">Умолчание</option>
														<option value="RESTRICT" selected>Блок</option>
														<option value="CASCADE">Удалить</option>
													</select>
												<? endif; ?>
											</span>
											<span style="text-align:center;">
												<button><?=($foreign_keys ? "Удалить" : "Создать")?></button>
											</span>
										</div>
									<? endif; ?>
								<? endforeach; ?>
							</div>
						<? endif; ?>
					<? else: ?>
						<? if($tpl['key_column_usage'] = ql(($sql = "SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE (TABLE_NAME='{$_GET['r']}' AND REFERENCED_TABLE_NAME != '') OR REFERENCED_TABLE_NAME = '{$_GET['r']}'"))): ?>
							<?// mpre("Список вторичных и первичных ключей для вторичных таблиц", $tpl['key_column_usage']); ?>
						<? endif; ?>
						<div class="table" style="width:100%;">
							<script sync>
								(function($, script){
									$(script).parent().on("click", "button.foreign", function(e){
										var field = $(e.currentTarget).parents("[field]").attr("field");
										var reference = $(e.currentTarget).parents("[field]").find("select[name=reference] option:selected").attr("value");
										$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/null", {foreign:field, reference:reference}, function(data){
											console.log("data:", data);
										}, "json").fail(function(error){ alert(error.responseText) })
									})
								})(jQuery, document.scripts[document.scripts.length-1])
							</script>
							<div class="th">
								<span>Поле</span>
								<span>Таблица</span>
								<span>Ключ</span>
								<span>Контроль</span>
								<span>Действие</span>
							</div>
							<? foreach($fields as $fld): ?>
								<? if($in_column_usage = rb($tpl['key_column_usage'], "REFERENCED_TABLE_NAME", "REFERENCED_COLUMN_NAME", "[{$_GET['r']}]", $fld['Field'])): ?>
									<?// mpre("Входящие ключи для {$fld['Field']} уже создан вторичный связанный ключ", $in_column_usage) ?>
								<? endif; if($out_column_usage = rb($tpl['key_column_usage'], "TABLE_NAME", "COLUMN_NAME", "[{$_GET['r']}]", $fld['Field'])): ?>
									<?// mpre("Исходящие ключи для {$fld['Field']} уже создан вторичный связанный ключ", $out_column_usage) ?>
								<? endif; if(("_id" == substr($fld['Field'], -3)) /*|| $in_column_usage*/): ?>
									<div field="<?=$fld['Field']?>">
										<span><?=$fld['Field']?></span>
										<span>
											<? if($in_column_usage): ?>
												<?=$in_column_usage['TABLE_NAME']?> 
											<? elseif($out_column_usage): ?>
												<?=$out_column_usage['REFERENCED_TABLE_NAME']?>
											<? else: ?>
												<?=("{$conf['db']['prefix']}{$arg['modpath']}_". substr($fld['Field'], 0, -3))?>
											<? endif; ?>
										</span>
										<span>
											<?=($in_column_usage ? $in_column_usage['COLUMN_NAME'] : "id")?>
										</span>
										<span>
											<? if(!($in_column_usage || $out_column_usage)): ?>
												<select name="reference">
													<option value="NO ACTION"></option>
													<option value="SET NULL">Нуль</option>
													<option value="RESTRICT" selected>Блок</option>
													<option value="CASCADE">Удалить</option>
												</select>
											<? endif; ?>
										</span>
										<span style="text-align:center;">
											<button class="foreign"><?=(($in_column_usage || $out_column_usage) ? "Удалить ключ" : "Создать ключ")?></button>
										</span>
									</div>
								<? endif; ?>
							<? endforeach; ?>
						</div>
					<? endif; ?>
				</span>
				<span style="padding-left:20px;">
					<? if(($conf['db']['type'] == 'sqlite')): ?>
						<? mpre("Для БД sqlite не доступно резервное копирование"); ?>
					<? else: ?>
						<div>
							<h1>Резервинование</h1>
							<div>
								<form method="post" enctype="multipart/form-data">
									<span style="float:right;">
										<input type="file" name="file">
										<button>Восстановить данные из файла</button>
									</span>
									<button>Сделать резервную копию</button>
									<label>
										<input type="checkbox" name="upload" checked>
										Скачать
									</label>
									<? if($status = rb(ql("SHOW TABLE STATUS"), "Name", "[{$_GET['r']}]")): ?>
										<p>
											<? if($modpath = first(array_slice(explode("_", $_GET['r']), 1, 1))): ?>
												<div>
													<? foreach(ql("SHOW TABLES LIKE '{$conf['db']['prefix']}{$modpath}%'") as $tables): ?>
														<? if($table = first($tables)): ?>
															<label style="display:inline-block; width:24%; white-space:nowrap;">
																<input type="checkbox" name="dump[<?=$table?>]" <?=($table == $_GET['r'] ? "checked" : "")?>><?=$table?>
															</label>
														<? endif; ?>
													<? endforeach; ?>
												</div>
											<? endif; ?>
										</p>
									<? endif; ?>
								</form>
							</div>
						</div>
					<? endif; ?>
					<div>
						<h1>Запрос</h1>
						<form action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/null" method="post">
							<script sync>
								(function($, script){
									$(script).parent().on("change", "select[name=query]", function(e){
										var query = $(e.currentTarget).find("option:selected").val();
										$(e.delegateTarget).find("textarea").val(query);
									}).one("init", function(e){
										$.getScript("/include/jquery/jquery.iframe-post-form.js", function(){// console.log("e:", e);
											$(e.target).iframePostForm({
												complete:function(data){// alert(data);
													$(e.target).next(".info").html(data);
												}
											})
										})
									}).ready(function(){ $(script).parent().trigger("init"); })
								})(jQuery, document.currentScript);
							</script>
							<p>
								<select name="query" style="width:100%;">
									<option></option>
									<? foreach(rb("query") as $query): ?>
										<option><?=$query['query']?></option>
									<? endforeach; ?>
								</select>
							</p>
							<p><textarea name="sql" style="width:100%; height:100px;" placeholder="Текст запроса"><? if(get($conf, 'db', 'type') == 'sqlite'): ?><?="SELECT * FROM sqlite_master WHERE tbl_name='{$_GET['r']}'"?><? else: ?><?=($_GET['r'] ? "SHOW CREATE TABLE `{$_GET['r']}`" : "")?><? endif; ?></textarea></p>
							<p><button>Выполнить</button></p>
						</form>
						<div class="info"></div>
					</div>
				</span>
			</div>
		</div>
	<? endif; ?>
</div>
