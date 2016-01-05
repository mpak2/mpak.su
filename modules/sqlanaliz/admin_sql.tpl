<div class="sql analiz">
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
						<input type="text" name="table" value="" list="tables" placeholder="<?=$_GET['r']?>">
						<datalist id="tables">
							<? foreach($tpl['tables'] as $table=>$row): ?>
								<option><?=$table?></option>
							<? endforeach; ?>
						</datalist>
						<button>Применить</button>
						<? if($ar = explode("_", $_GET['r'])): ?>
							<span style="padding:0 10px;">
								<a href="/<?=last(array_slice($ar, 1, 1))?>:admin/r:<?=$_GET['r']?>">
									<?=(get($conf, 'settings', implode("_", array_slice($ar, 1))) ?: $_GET['r'])?>
								</a>
							</span>
						<? endif; ?>
					</form>
				</p>
				
				<? if($_GET['r'] && ($fields = fields($_GET['r']))): ?>
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
											<? foreach(array("int(11)", "float", "varchar(255)", "text", "longtext") as $fd): ?>
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
										<? foreach(array("int(11)", "float", "varchar(255)", "text", "longtext") as $fd): ?>
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
			</span>
			<span style="padding-left:20px;">
				<h1>Запрос</h1>
				<form action="/<?=$arg['modname']?>:<?=$arg['fn']?>/null" method="post">
					<script src="/include/jquery/jquery.iframe-post-form.js"></script>
					<script sync>
						(function($, script){
							$(script).parent().one("init", function(e){
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
								}, 300)
							}).trigger("init")
						})(jQuery, document.scripts[document.scripts.length-1])
					</script>
					<p><textarea name="sql" style="width:100%; height:100px;" placeholder="Текст запроса"></textarea></p>
					<p><button>Выполнить</button></p>
				</form>
				<div class="info"></div>
			</span>
		</div>
	</div>
</div>
