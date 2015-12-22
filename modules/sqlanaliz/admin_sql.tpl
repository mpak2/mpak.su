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
											<? foreach(array("int(8)", "varchar(255)", "text") as $fd): ?>
												<option <?=($tpl['fields'][$field]['Type'] == $fd ? "selected" : "")?>><?=$fd?></option>
											<? endforeach; ?>
										</select>
									</span>
									<span><input type="text" name="f[<?=$field?>][val]" style="width:60px;" placeholder="Значение"></span>
									<span><input type="text" name="f[<?=$field?>][comment]" placeholder="Коментарий"></span>
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
										<? foreach(array("int(8)", "varchar(255)", "text") as $fd): ?>
											<option><?=$fd?></option>
										<? endforeach; ?>
									</select>
								</span>
								<span><input type="text" name="$[val]" style="width:60px;" placeholder="Значение"></span>
								<span><input type="text" name="$[comment]" placeholder="Коментарий"></span>
							</div>
						</div>
						<p><button>Сохранить</button></p>
					</form>
				<? endif; ?>
			</span>
			<span style="padding-left:20px;">
				<div class="table" style="width:100%">
					<? foreach(rb("{$conf['db']['prefix']}modules") + array(0=>array("id"=>0, "modpath"=>"")) as $modules): ?>
						<? if($tpl['tab'] = rb($tpl['tables'], "modpath", "id", "[{$modules['folder']}]")): ?>
							<div>
								<span style="width:140px; vertical-align:top; border-top:1px solid #ddd;">
									<h1><?=$modules['name']?></h1>
								</span>
								<span style="border-top:1px solid #ddd;">
									<ul>
										<? foreach($tpl['tab'] as $tables): ?>
											<li style="float:left; margin-left:25px;">
												<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/r:<?=$tables["Tables_in_{$conf['db']['name']}"]?>">
													<?=$tables["Tables_in_{$conf['db']['name']}"]?>
												</a>
											</li>
										<? endforeach; ?>
									</ul>
								</span>
							</div>
						<? endif; ?>
					<? endforeach; ?>
				</div>
			</span>
		</div>
	</div>
</div>
