<? if(!$blocknum = get($arg, "blocknum")): mpre("ОШИБКА получения номера блока") ?>
<? elseif(!$block = rb("blocks-index", "id", $blocknum)): mpre("ОШИБКА выборки блока") ?>
<? elseif(get($_POST, 'blocknum')): ?>
	<? foreach($_POST as $name=>$settings): ?>
		<? if(get($settings, 'fields')): ?>
			<? $post[$name] = $settings; ?>
		<? endif; ?>
	<? endforeach; ?>

	<? if(!is_array($param = $post = (empty($post) ? [] : $post))): mpre("ОШИБКА получения параметров блока") ?>
	<? elseif(!$json = json_encode($post, JSON_UNESCAPED_UNICODE)): mpre("ОШИБКА получения параметров поиска блока") ?>
	<? elseif(!$json = strtr($json, ['\/'=>'/'])): mpre("ОШИБКА конвертации формата параметров") ?>
	<? elseif(!qw("UPDATE {$conf['db']['prefix']}blocks_index SET `param`='". $json. "' WHERE id=". (int)$blocknum)): mpre("ОШИБКА обновления параметров блока") ?>
	<? elseif(!$blocks_index = rb("blocks-index", "id", $blocknum)): mpre("ОШИБКА получения параметров блока") ?>
	<? else: mpre("Сохранено", $post) ?>
	<? endif; ?>
<? else: ?>
	<? if($blocks_index = rb("blocks-index", "id", $blocknum)): ?>
		<? ($param = json_decode($blocks_index['param'], true)) ?>
	<? endif; ?>
<? endif; ?>

<? if(!$TABLES = tables()): mpre("ОШИБКА получения списка таблиц") ?>
<? elseif($arg['modpath'] == "blocks"): ?>
	<form method='post'>
		<style>
			.admin_search {overflow:hidden;}
			.admin_search > div { float:left; width:24%; }
			.admin_search > div:nth-child(4n+1) {clear:both;}
		</style>
		<input type="hidden" name="blocknum" value="<?=$arg['blocknum']?>">
		<div class="admin_search">
			<? foreach($TABLES as $table): ?>
				<? if(!$tab = get($table, 'name')): mpre("ОШИБКА выборки имени таблицы") ?>
				<? elseif(!$name = substr($tab, strlen($conf['db']['prefix']))): mpre("ОШИБКА получения имени таблицы без префикса") ?>
				<? else: ?>
					<div>
						<div style="padding:10px;">	
							<b><?=$name?></b>
							<p><input type='text' name="<?=$tab?>[name]" value="<?=(get($param, $name, "name") ?: (get($conf, "settings", $name) ?: $tab))?>" placeholder="Имя таблицы" style='width:100%'></p>
							<p><input type='text' name="<?=$tab?>[href]" value="<?=(get($param, $name, "href") ?: "/". first(explode("_", $name)). ":admin/r:{$tab}?&where[id]={id}")?>" placeholder="Адрес перехода" style='width:100%'></p>
							<div>
								<? foreach(fields($name) as $field):// mpre($field) ?>
									<? if(!$fld = (get($field, 'name') ?: get($field, 'Field'))): mpre("ОШИБКА получения имени поля") ?>
									<? elseif(!$type = (get($field, 'type') ?: get($field, 'Type'))): mpre("ОШИБКА получения типа поля") ?>
									<? else: ?>
										<div>
											<input type="checkbox" name="<?=$tab?>[fields][<?=$fld?>]" <?=(get($param, $tab, "fields", $fld) ? "checked" : "")?>><b><?=$fld?></b> <?=$type?>
										</div>
									<? endif; ?>
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
	<h2>Админпоиск</h2>
	<form action="/admin:search/search_block_num:<?=$arg['blocknum']?>" method="get" style="padding:10px;">
		<input type="text" name="search" style="width:70%;">
		<button>Искать</button>
	</form>
<? endif; ?>
