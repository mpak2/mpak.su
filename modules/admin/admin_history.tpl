<? if($admin_history = rb("admin-history", "id", get($_GET, 'id'))): ?>
	<div class="admin_history">
		<h1>Изменения</h1>
		<style>
			.admin_history .diff td{
				vertical-align : top;
				white-space    : pre;
				white-space    : pre-wrap;
				font-family    : monospace;
				background-color:#efefef;
			}
			.admin_history .diff td.diffUnmodified {
				width:50%;
				background-color:white;
			}
			.admin_history div.table > div > span:first-child{
				width:50%;
			}
			.admin_history .diffDeleted span {
				border: 1px solid rgb(255,192,192);
				background: rgb(255,224,224);
			}
			.admin_history .diffInserted span {
				border: 1px solid rgb(192,255,192);
				background: rgb(224,255,224);
			}
			.admin_history .top div.table > div > span {
				padding:0 10px;
			}
			.admin_history .top div.table > div > span:first-child {
				text-align:right;
			}
		</style>
		<? if($diff = json_decode($admin_history['diff'], true)): ?>
			<? if($data = json_decode($admin_history['data'], true)): ?>
				<div class="table">
				<? foreach(array_intersect_key($diff, $data) as $k=>$v): ?>
						<div>
							<span><?=$data[$k]?></span>
							<span><?=$v?></span>
						</div>
					<? /*if(file_put_contents($nam1 = tempnam(sys_get_temp_dir(), "diff_1_"), $data[$k])): ?>
						<? if(file_put_contents($nam2 = tempnam(sys_get_temp_dir(), "diff_2_"), $v)): ?>
							<? mpre($nam1, $nam2) ?>
							<?=diff::toTable(diff::compareFiles($nam1, $nam2))?>
						<? endif; ?>
					<? endif;*/ ?>
				<? endforeach; ?>
				</div>
			<? else: mpre("Данные не определены"); endif; ?>
		<? else: mpre("Изменения не обнаружены"); endif; ?>
	</div>
<? else: ?>
	<div class="themes_history">
		<style>
			.themes_history .table { border-collapse:collapse; }
			.themes_history .table > div > span { padding:3px; border:1px solid #ddd; }
			.themes_history div.toggle > div {display:none; position:absolute; background-color:white; border:1px solid #ddd; padding:0 20px; z-index: 10; }
			.themes_history div.toggle:hover > div {display:block;}
		</style>
		<script sync>
			(function($, script){
				$(script).parent().on("change", "select", function(e){ // Загрузка родительского элемента
					var admin_history_tables_id = $(e.currentTarget).find("option:selected").attr("value");
					document.location.href = "/<?=$arg['modpath']?>:<?=$arg['fn']?>/admin-history_tables:"+admin_history_tables_id;
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
		<h2>Логироварие данных</h2>
		<? if($tpl['admin_history'] = rb("{$conf['db']['prefix']}admin_history", 40, "history_tables_id", "id", (get($_GET, "admin-history_tables") ?: true))): ?>
			<p style="float:right;">
				<select name="admin_history_tables_id">
					<option value="0"></option>
					<? foreach(rb("admin-history_tables") as $admin_history_tables): ?>
						<option value="<?=$admin_history_tables['id']?>" <?=(get($_GET, "admin-history_tables") == $admin_history_tables['id'] ? "selected" : "")?>><?=$admin_history_tables['name']?></option>
					<? endforeach; ?>
				</select>
			</p>
			<p><?=$tpl['pager']?></p>
			<div class="table">
				<div class="th">
					<span>Номер</span>
					<span>Время</span>
					<span>Пользователь</span>
					<span>Изменения</span>
					<span>Данные</span>
					<span>Операция</span>
					<span>Таблица</span>
					<span>Директория</span>
					<span>Раздел</span>
					<span>Заголовок</span>
				</div>
				<? foreach($tpl['admin_history'] as $admin_history): ?>
					<? if($history_tables = rb("{$conf['db']['prefix']}admin_history_tables", "id", $admin_history['history_tables_id'])): ?>
						<div>
							<span><?=$admin_history['id']?></span>
							<span>
								<?=date("d.m.Y H:i", $admin_history['time'])?>
							</span>
							<span>
								<? if($uid = rb("{$conf['db']['prefix']}users", "id", $admin_history['uid'])): ?><?=$uid['name']?><? endif; ?>
							</span>
							<span style="position:relative;">
								<? if($data = ($admin_history['diff'] ? json_decode($admin_history['diff'], true) : false)): ?>
									<div class="toggle">
										<a href="javascript:void(0)">Смотреть</a> <?=count($data)?>
										<div><? mpre($data) ?></div>
									</div>
								<? endif; ?>
							</span>
							<span style="position:relative;">
								<? if($data = ($admin_history['data'] ? json_decode($admin_history['data'], true) : false)): ?>
									<div class="toggle">
										<a href="javascript:void(0)">Смотреть</a> <?=count($data)?>
										<div><? mpre($data) ?></div>
									</div>
								<? endif; ?>
							</span>
							<span>
								<? if($admin_history_type = rb("{$conf['db']['prefix']}admin_history_type", "id", $admin_history['history_type_id'])): ?>
									<? if("Редактирование" == $admin_history_type['name']): ?>
										<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$admin_history['id']?>"><?=$admin_history_type['name']?></a>
									<? else: ?>
										<?=$admin_history_type['name']?>
									<? endif; ?>
								<? endif; ?>
							</span>
							<span>
								<?=$history_tables['name']?>
							</span>
							<span><?=$history_tables['modpath']?></span>
							<span><?=$history_tables['modname']?></span>
							<span>
								<a href="/<?=$history_tables['modpath']?>:admin/r:<?=$history_tables['name']?>?&where[id]=<?=$admin_history['name']?>">
									<?=get($conf, 'settings', substr($history_tables['name'], strlen($conf['db']['prefix'])))?>
								</a>
							</span>
						</div>
					<? endif ?>
				<? endforeach; ?>
			</div>
			<p><?=$tpl['pager']?></p>
		<? endif; ?>
	</div>
<? endif; ?>
