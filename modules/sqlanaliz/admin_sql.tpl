<div class="sql analiz">
	<? if($file = get($tpl, 'file')): ?>
		<? mpre($file); ?>
	<? elseif($dump = get($tpl, 'dump')): ?>
		<? foreach($dump as $k=>$d): ?>
			<h1><?=$k?></h1>
			<pre style="margin:5px; padding:5px; border-top:2px solid #ddd; border-bottom:2px solid #ddd;"><?=$d?></pre>
		<? endforeach; ?>
	<? else: ?>
		<div class="table" style="width:100%;">
			<div>
				<span style="width:40%;">
					<style>
						.sql.analiz .table {width:30%;}
						.sql.analiz .table>div>span {padding:2px;}
					</style>
					<p>
						<form table="<?=$_GET['r']?>" action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/null" method="post">
							<script src="/include/jquery/jquery.iframe-post-form.js"></script>
							<script sync>
								(function($, script){
									$(script).parent().one("init", function(e){
										setTimeout(function(){
											$(e.delegateTarget).iframePostForm({
												complete:function(data){
													try{if(json = JSON.parse(data)){
														if(json.table){
															document.location.href = "/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:"+json.table;
														} console.log("json:", json);
													}}catch(e){if(isNaN(data)){ alert(data) }else{
														console.log("date:", data)
													}}
												}
											});
										}, 100)
									}).on("click", "a.del", function(e){
										var table = $(e.delegateTarget).attr("table");
										if(confirm("Подтвердите удаление таблицы `"+ table+ "`")){
											$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/null", {del:table}, function(data){
												if(isNaN(data)){ alert(data) }else{
													document.location.href = "/<?=$arg['modpath']?>:<?=$arg['fn']?>";
													console.log("data:", data);
												}
											});
										}
									}).trigger("init")
								})(jQuery, document.scripts[document.scripts.length-1])
							</script>
							<span style="float:right;">
								<a class="del" href="javascript:void(0)">Удалить</a>
							</span>
							<input type="text" name="table" value="" list="tables" placeholder="<?=get($_GET, 'r')?>">
							<datalist id="tables">
								<? foreach($tpl['tables'] as $table=>$row): ?>
									<option><?=$table?></option>
								<? endforeach; ?>
							</datalist>
							<button>Применить</button>
							<? if($ar = explode("_", get($_GET, 'r'))): ?>
								<span style="padding:0 10px;">
									<a href="/<?=last(array_slice($ar, 1, 1))?>:admin/r:<?=$_GET['r']?>">
										<?=(get($conf, 'settings', implode("_", array_slice($ar, 1))) ?: get($_GET, 'r'))?>
									</a>
								</span>
							<? endif; ?>
						</form>
					</p>
					
					<? if(get($_GET, 'r') && ($fields = fields($_GET['r']))): ?>
						<form action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>" method="post">
							<div class="table">
								<? foreach($fields as $field=>$row): ?>
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
												<? foreach($types = array("int(11)", "smallint(6)", "bigint(20)", "float", "varchar(255)", "text", "longtext") as $fd): ?>
													<option <?=($tpl['fields'][$field]['Type'] == $fd ? "selected" : "")?>><?=$fd?></option>
												<? endforeach; ?>
											</select>
										</span>
										<span><input type="text" value="<?=$fields[$field]['Default']?>" name="f[<?=$field?>][default]" style="width:60px;" placeholder="Значение"></span>
										<span><input type="text" value="<?=$fields[$field]['Comment']?>" name="f[<?=$field?>][comment]" placeholder="Коментарий"></span>
										<span><input type="checkbox" name="f[<?=$field?>][index]" <?=(array_key_exists($field, $tpl['indexes']) ? "checked" : "")?>></span>
									</div>
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
									<span><input type="text" name="$[default]" style="width:60px;" placeholder="Значение"></span>
									<span><input type="text" name="$[comment]" placeholder="Коментарий"></span>
									<span><input type="checkbox" name="$[index]"></span>
								</div>
							</div>
							<p><button>Сохранить</button></p>
						</form>
					<? endif; ?>

					<? if(($tpl['key_column_usage'] = ql($sql = "SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE (TABLE_NAME='{$_GET['r']}' AND REFERENCED_TABLE_NAME != '') OR REFERENCED_TABLE_NAME = '{$_GET['r']}'"))): ?>
						<?// mpre("Список вторичных и первичных ключей для вторичных таблиц", $tpl['key_column_usage']); ?>
					<? endif; ?>
					<div class="table" style="width:100%;">
						<script sync>
							(function($, script){
								$(script).parent().on("click", "button", function(e){
									var field = $(e.currentTarget).parents("[field]").attr("field");
									var reference = $(e.currentTarget).parents("[field]").find("select[name=reference] option:selected").attr("value");
									console.log("field:", field, "reference:", reference);
									$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$_GET['r']?>/null", {foreign:field, reference:reference}, function(data){
										console.log("data:", data);
										document.location.reload(true);
									}, "json").fail(function(error){ alert(error.responseText); })
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
							<? endif; if(("_id" == substr($fld['Field'], -3)) || $in_column_usage): ?>
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
										<button><?=(($in_column_usage || $out_column_usage) ? "Удалить ключ" : "Создать ключ")?></button>
									</span>
								</div>
							<? endif; ?>
						<? endforeach; ?>
					</div>
				</span>
				<span style="padding-left:20px;">
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
					<div>
						<h1>Запрос</h1>
						<form action="/<?=$arg['modpath']?>:<?=$arg['fn']?>/null" method="post">
							<script src="/include/jquery/jquery.iframe-post-form.js"></script>
							<script sync>
								(function($, script){
									$(script).parent().on("change", "select[name=query]", function(e){
										var query = $(e.currentTarget).find("option:selected").val();
										$(e.delegateTarget).find("textarea").val(query);
									}).one("init", function(e){
										setTimeout(function(){
											$(e.delegateTarget).iframePostForm({
												complete:function(data){
													try{if(json = JSON.parse(data)){
														console.log("json:", json);
		//												document.location.reload(true);
													}}catch(e){if(isNaN(data)){
		//												alert(data);
														$(this).next().html(data);
														console.log("next:", $(this).next());
													}else{
														console.log("date:", data)
													}}
												}
											});
											setTimeout(function(){
												$(e.delegateTarget).find("button").trigger("click");
											}, 100);
										}, 300);
									}).trigger("init")
								})(jQuery, document.scripts[document.scripts.length-1])
							</script>
							<p>
								<select name="query" style="width:100%;">
									<option></option>
									<? foreach(rb("query") as $query): ?>
										<option><?=$query['query']?></option>
									<? endforeach; ?>
								</select>
							</p>
							<p><textarea name="sql" style="width:100%; height:100px;" placeholder="Текст запроса"><?=($_GET['r'] ? "SHOW CREATE TABLE `{$_GET['r']}`" : "")?></textarea></p>
							<p><button>Выполнить</button></p>
						</form>
						<div class="info"></div>
					</div>
				</span>
			</div>
		</div>
	<? endif; ?>
</div>
