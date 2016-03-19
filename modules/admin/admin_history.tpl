<div class="themes_history">
	<style>
		.themes_history .table { border-collapse:collapse; }
		.themes_history .table > div > span { padding:3px; border:1px solid #ddd; }
		.themes_history div.toggle > div {display:none; position:absolute; background-color:white; border:1px solid #ddd; padding:0 20px; z-index: 10; }
		.themes_history div.toggle:hover > div {display:block;}
	</style>
	<h2>Логиноварие данных</h2>

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
		<? foreach(rb("{$conf['db']['prefix']}admin_history", 40) as $admin_history): ?>
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
							<?=$admin_history_type['name']?>
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
		<p><?=$tpl['pager']?></p>
	</div>
</div>
