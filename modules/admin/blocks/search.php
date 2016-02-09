<? if((($blocknum = get($arg, "blocknum"))) && get($_POST, 'blocknum')): ?>
	<? foreach($_POST as $name=>$settings): ?>
		<? if(get($settings, 'fields')): ?>
			<? $post[$name] = $settings; ?>
		<? endif; ?>
	<? endforeach; ?>
	<? if($blocks_index = fk("{$conf['db']['prefix']}blocks_index", array("id"=>$blocknum), null, array("param"=>($json = json_encode($param = (empty($post) ? array() : $post)))))): ?>
		<?// qw("UPDATE {$conf['db']['prefix']}blocks_index SET param=\"". addslashes($json = json_encode($post)). "\" WHERE id=". (int)$blocknum) ?>
		<?// mpre($param, $json) ?>
	<? endif; ?>
<? else: ?>
	<? if($blocks_index = rb("{$conf['db']['prefix']}blocks_index", "id", $blocknum)): ?>
		<? ($param = json_decode($blocks_index['param'], true)) ?>
	<? endif; ?>
<? endif; ?>
<? if($arg['modpath'] == "blocks"): ?>
	<form method='post'>
		<style>
			.admin_search {overflow:hidden;}
			.admin_search > div { float:left; width:24%; }
			.admin_search > div:nth-child(4n+1) {clear:both;}
		</style>
		<input type="hidden" name="blocknum" value="<?=$arg['blocknum']?>">
		<div class="admin_search">
			<? foreach(array_column(tables(), "Tables_in_{$conf['db']['name']}") as $table): ?>
				<? if($name = substr($table, strlen($conf['db']['prefix']))): ?>
					<div>
						<div style="padding:10px;">	
							<b><?=$table?></b>
							<p><input type='text' name="<?=$table?>[name]" value="<?=(get($param, $table, "name") ?: (get($conf, "settings", $name) ?: $name))?>" placeholder="Имя таблицы" style='width:100%'></p>
							<p><input type='text' name="<?=$table?>[href]" value="<?=(get($param, $table, "href") ?: "/". first(explode("_", $name)). ":admin/r:{$table}?&where[id]={id}")?>" placeholder="Адрес перехода" style='width:100%'></p>
							<div>
								<? foreach(fields($table) as $field): /*if ($v['Field'] == 'id') continue;*/ ?>
									<div>
										<input type="checkbox" name="<?=$table?>[fields][<?=$field['Field']?>]" <?=(get($param, $table, "fields", $field['Field']) ? "checked" : "")?>><b><?=$field['Field']?></b> <?=$field['Type']?>
									</div>
								<? endforeach; ?>
							</div>
						</div>
					</div>
				<? endif; ?>
			<? endforeach; ?>
		</div>
		<div>
			<span><input type='submit' name='update_param' value='Сохранить'></span>
		</div>
	</form>
<? else: ?>
	<form action="/admin:search/search_block_num:<?=$arg['blocknum']?>" method="post" style="padding:10px;">
		<input type="text" name="search">
		<button>Искать</button>
	</form>
<? endif; ?>
