<div class="sql analiz">
	<style>
		.sql.analiz .table {width:30%;}
		.sql.analiz .table>div>span {padding:2px;}
	</style>
	<p>
		<script sync>
			(function($, script){
				$(script).parent().on("change", "select", function(e){
					var table = $(e.delegateTarget).find("option:selected").val();
					document.location.href = "/<?=$arg['modpath']?>:<?=$arg['fn']?>"+(table ? "/r:"+table : "");
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
		<select>
			<option></option>
			<? foreach(tables() as $table=>$row): ?>
				<option <?=($_GET['r'] == $table ? "selected" : "")?>><?=$table?></option>
			<? endforeach; ?>
		</select>
	</p>
	
	<? if($_GET['r'] && ($fields = fields($_GET['r']))): ?>
		<div class="table">
			<? foreach($fields as $field=>$row): ?>
				<div>
					<span><input type="text" value="<?=$field?>" placeholder="Название"></span>
					<span>
						<select>
							<option></option>
							<? foreach($fields as $f=>$r): ?>
								<option><?=$f?></option>
							<? endforeach; ?>
						</select>
					</span>
					<span>
						<select>
							<option></option>
							<? foreach(array("text", "integer") as $fd): ?>
								<option><?=$fd?></option>
							<? endforeach; ?>
						</select>
					</span>
					<span><input type="text" placeholder="Значение"></span>
				</div>
			<? endforeach; ?>
			<div>
				<span><input type="text" placeholder="Название"></span>
				<span>
					<select>
						<? foreach($fields as $f=>$r): ?>
							<option><?=$f?></option>
						<? endforeach; ?>
					</select>
				</span>
				<span>
					<select>
						<? foreach(array("text", "integer") as $fd): ?>
							<option><?=$fd?></option>
						<? endforeach; ?>
					</select>
				</span>
				<span><input type="text" placeholder="Значение"></span>
			</div>
		</div>
		<p><button>Сохранить</button></p>
	<? endif; ?>
</div>
