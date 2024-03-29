<div class="sql analiz">
	<? if($file = get($tpl, 'file')): ?>
		<? mpre($file); ?>
	<? elseif($dump = get($tpl, 'dump')): ?>		
		<h1>mysqldump</h1>
		<pre style="margin:5px; padding:5px; border-top:2px solid #ddd; border-bottom:2px solid #ddd;"><?=$dump?></pre>
	<? elseif(!$table = get($_GET, 'r')): mpre("ОШИБКА получения имени таблицы") ?>
	<? elseif(!is_array($INDEXES = indexes($table))): mpre("ОШИБКА получения списка индексов таблицы" ,$table) ?>
	<? elseif(!$FIELDS = fields($table)): mpre("ОШИБКА получения списка полей таблицы") ?>
	<? elseif(!is_array($indexes = array_map(function($index){ // Получение имен полей из имен индексов
			if(!$parts = explode("-", $index, 2)){ mpre("ОШИБКА разбивки имени ключа на составляющие");
			}else{ return get($parts, 1); }
		}, array_keys($INDEXES)))): mpre("ОШИБКА поулчения имен полей из индексов" ,$INDEXES) ?>
	<? elseif(!is_array($INDEXES = array_combine($indexes, $INDEXES))): mpre("ОШИБКА установки индексов по именам полей" ,$INDEXES) ?>
	<? elseif(!$types = array("INTEGER", "REAL", "TEXT")): mpre("ОШИБКА установки типов полей") ?>
	<? else: //mpre($FIELDS) ?>
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
									</a> (Старый интерфейс <a href="/sql:admin_sql_old/r:<?=$_GET["r"]?>">admin_sql_old</a>)
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
													<? foreach($types as $fd): ?>
														<option <?=((get($FIELDS, $field, 'Type') == $fd) || (get($FIELDS, $field, 'type') == $fd) ? "selected" : "")?>><?=$fd?></option>
													<? endforeach; ?>
												</select>
											</span>
											<span>
												<input type="text" value="<?=$default?>" name="f[<?=$field?>][default]" style="width:60px;" placeholder="Значение">
											</span>
											<span>
												<input type="text" value="<?=get($fields, $field, 'Comment')?>" name="f[<?=$field?>][comment]" placeholder="Коментарий" <?=(get($conf, 'db', 'type') == 'mysql' ? "" : "disabled")?>>
											</span>
											<span style="width:50px;">
												<input type="checkbox" name="f[<?=$field?>][unique]"<?=(get($INDEXES, "{$field}-unique") ? "checked" : "")?> placeholder="Уникальность">
												<span title="Уникальное поле">Уник</span>
											</span>
											<span>
												<input type="checkbox" name="f[<?=$field?>][index]" <?=(get($INDEXES, $field) ? "checked" : "")?>>
												<span title="Поле индекса">Ключ</span>
											</span>
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
											<? foreach($types as $fd): ?>
												<option><?=$fd?></option>
											<? endforeach; ?>
										</select>
									</span>
									<span><input type="text" name="$[default]" disabled style="width:60px;" placeholder="Значение"></span>
									<span><input type="text" name="$[comment]" placeholder="Коментарий" <?=(get($conf, 'db', 'type') == 'mysql' ? "" : "disabled")?>></span>
									<span>
										<input type="checkbox" disabled name="$[unique]" placeholder="Уникальность">
										<span title="Уникальное поле">Уник</span>
									</span>
									<span>
										<input type="checkbox" disabled name="$[index]" placeholder="Индекс">
										<span title="Поле индекса">Ключ</span>
									</span>
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
							<form method="post">
								<div class="table" style="white-space:nowrap;">
									<div class="th">
										<span>Поле</span>
										<span>Таблица</span>
										<span>Связь</span>
										<span>Действие обновить</span>
										<span>Действие удалить</span>
										<span>Удалить ключ</span>
									</div>
									<? foreach($FIELDS as $field=>$fld): ?>
										<? //if(substr($field, -3) != "_id"):// mpre("Поле не является вторичным ключем `{$field}`"); ?>
										<? if(!call_user_func(function() use($field){ # Проверка на соответствие поля критериям вторичного ключа
												if(substr($field, -3) == "_id"){ return $field; mpre("Вторичный ключ внутри таблицы");
												}elseif(!$ex = explode('-', $field)){ mpre("Раскладываем имя поля по элементам");
												}elseif(2 == count($ex)){ return $field; mpre("Вторичное поле вне раздела вида `pages-index`");
												}else{// mpre("Поле `{$field}` не соответствует требованиям вторичного ключа");
												}
											})): // mpre("Поле не является вторичным ключем `{$field}`"); ?>
										<? elseif((!$foreign_keys = get($FOREIGN_KEYS, $field)) &0): mpre("Вторичный ключ поля") ?>
										<? elseif(!is_array($foreign_key = get($FOREIGN_KEYS, $field) ?: [])): mpre("ОШИБКА получения ключа") ?>
										<? elseif(!$actions = ['NO ACTION'=>'', 'SET NULL'=>'Обнулить поле', 'SET DEFAULT'=>'Действие по молчанию', 'RESTRICT'=>'Блокировка изменений', 'CASCADE'=>'Удалить связанные']): mpre("ОШИБКА установки возможных действий") ?>
										<? else:// mpre($sql, $foreign_keys) ?>
											<div>
												<span><?=$field?></span>
												<span><?=get($foreign_key, 'table')?></span>
												<span><?=get($foreign_key, 'to')?></span>
												<span>
													<? if($on_update = get($foreign_keys, 'on_update')): ?>
														<? if(!$action = get($actions, $on_update)): ?>
															<?=$on_update?>
														<? else: ?>
															<span title="<?=$on_update?>" style="font-weight:bold;"><?=$action?></span>
														<? endif; ?>
													<? elseif(!$selected = "SET NULL"): mpre("ОШИБКА установки значения по умолчанию") ?>
													<? else: ?>
														<select name="on_update[<?=$field?>]">
															<? foreach($actions as $action=>$name): ?>
																<option value="<?=$action?>" <?=($selected == $action ? "selected" : "")?>><?=$name?></option>
															<? endforeach; ?>
														</select>
													<? endif; ?>
												</span>
												<span>
													<? if($on_delete = get($foreign_keys, 'on_delete')): ?>
														<? if(!$action = get($actions, $on_delete)): ?>
															<?=$on_delete?>
														<? else: ?>
															<span title="<?=$on_delete?>" style="font-weight:bold;"><?=$action?></span>
														<? endif; ?>
													<? elseif(!$selected = "CASCADE"): mpre("ОШИБКА установки значения по умолчанию") ?>
													<? else: ?>
														<select name="on_delete[<?=$field?>]">
															<? foreach($actions as $action=>$name): ?>
																<option value="<?=$action?>" <?=($selected == $action ? "selected" : "")?>><?=$name?></option>
															<? endforeach; ?>
														</select>
													<? endif; ?>
												</span>
												<span style="text-align:center;">
													<button name="foreign" value="<?=$field?>"><?=($foreign_keys ? "Удалить" : "Создать")?></button>
												</span>
											</div>
										<? endif; ?>
									<? endforeach; ?>
								</div>
							</form>
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
									$(script).parent().one("init", function(e){
										$(FORMS = $(e.currentTarget).is("form") ? e.currentTarget : $(e.currentTarget).find("form")).on("submit", function(e){
											$.ajax({
												type: 'POST',
												url: $(e.currentTarget).attr('action'),
												data: $(e.currentTarget).serialize(),
												dataType: 'json',
											}).done(function(json){
//												alert("Спасибо. Информация сохранена.");
												$(".info").text(json);
											}).fail(function(error){
//												alert(error.responseText);
												$(".info").html(error.responseText);
											}); return false;
										}).attr("target", "response_"+(timeStamp = e.timeStamp));

										/*$("<"+"iframe>").attr("name", "response_"+timeStamp).appendTo(FORMS).load(function(){
											var response = $(this).contents().find("body").html();
											if(json = $.parseJSON(response)){
												console.log("json:", json);
												alert("Информация добавлена в кабинет");
											}else{ alert(response); }
										}).hide();*/
									}).ready(function(e){ $(script).parent().trigger("init"); })
								})(jQuery, document.currentScript)
							</script>
							<p>
								<? if(!$QUERY = rb("query")): mpre("ОШИБКА выбборки списка запросов") ?>
								<? else: //mpre($QUERY) ?>
									<select name="query" style="width:100%;">
										<option></option>
										<? foreach($QUERY as $query): ?>
											<option><?=(get($query, 'name') ?: $query["query"])?></option>
										<? endforeach; ?>
									</select>
								<? endif; ?>
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
