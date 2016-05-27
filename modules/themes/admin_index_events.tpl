<div class="admin_index_events">
	<? if($users_event = rb("users-event_logs", "id", get($_GET, "users-event_logs"))): ?>
		<? mpre($users_event) ?>
	<? elseif(($index_events = rb("index_events", "id", get($_GET, "id"))) && ($users_event = rb("users-event", "id", $index_events['users_event']))): ?>
		<style>
			.admin_index_events .table {border-collapse:collapse;}
			.admin_index_events .table > div > span {padding:0 3px; border:1px solid gray;}
		</style>
		<span style="float:right;">
			<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>">Вернуться</a>
		</span>
		<h1><?=$users_event['name']?> (<?=$index_events['name']?>)</h1>
		<? if($tpl['users_event_logs'] = rb("users-event_logs", 30, "event_id", "id", $users_event['id'])): ?>
			<p><?=$tpl['pager']?></p>
			<div class="table">
				<div class="th">
					<span>пп</span>
					<span>Время</span>
					<span>Событие</span>
					<span>Пользователь</span>
					<span>Хост</span>
					<span>Инфо</span>
					<span>Агент</span>
				</div>
				<? foreach($tpl['users_event_logs'] as $users_event_logs): ?>
					<div><?// mpre($users_event_logs) ?>
						<span><?=$users_event_logs['id']?></span>
						<span><?=date("d.m.Y H:i:s", $users_event_logs['time'])?></span>
						<span>
							<? if($event_logs = rb("users-event_logs", "id", get($users_event_logs, 'event_logs_id'))): ?>
								<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/users-event_logs:<?=$event_logs['id']?>" title="<?=$event_logs['description']?>">
									<?=$event_logs['id']?>
								</a>
							<? endif; ?>
						</span>
						<span>
							<? if($users_event_logs['uid'] > 0): ?>
								<? if($uid = rb("{$conf['db']['prefix']}users", "id", $users_event_logs['uid'])): ?>
									<?=$uid['name']?>
								<? else: ?>
									<span style="color:red;"><?=$users_event_logs['uid']?></span>
								<? endif; ?>
							<? else: ?>
								<?=$conf['settings']['default_usr']?><?=$users_event_logs['uid']?>
							<? endif; ?>
						</span>
						<span style="white-space:nowrap;">
							<? if(get($users_event_logs, 'themes_index') && ($index = rb("themes-index", "id", $users_event_logs['themes_index']))): ?>
								<?=$index['name']?>
							<? endif; ?>
						</span>
						<span><a target="blank" href="<?=(empty($index) ? "" : "//{$index['name']}"). $users_event_logs['description']?>"><?=$users_event_logs['description']?></a></span>
						<span style="max-width:600px;">
							<? if($users_event_logs['uid'] < 0): ?>
								<? if($sess = rb("{$conf['db']['prefix']}sess", "id", -$users_event_logs['uid'])): ?>
									<?=$sess['agent']?>
								<? else: ?> Сессия не найдена <? endif; ?>
							<? endif; ?>
						</span>
					</div>
				<? endforeach; ?>
			</div>
			<p><?=$tpl['pager']?></p>
		<? endif; ?>
	<? else: ?>
		<h1>События на сайте</h1>
		<div class="table">
			<? if($tpl["index_events"] = rb("index_events")): ?>
				<? foreach(rb("users-event", "id", "id", rb($tpl["index_events"], "users_event")) as $users_event): ?>
					<? if($index_events = rb($tpl["index_events"], "users_event", $users_event['id'])): ?>
						<div>
							<span style="padding:10px;">
								<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$index_events['id']?>">
									<?=$users_event['name']?>
								</a> <?=$index_events['name']?>
							</span>
						</div>
						<? foreach(rb("users-event_logs", 5, "event_id", "id", $users_event['id']) as $users_event_logs): ?>
							<div>
								<span><?=$users_event_logs['description']?></span>
								<span>
									<? if($index = rb("themes-index", "id", get($users_event_logs, "themes_index"))): ?>
										<?=$index['name']?>
									<? endif; ?>
								</span>
								<span>
									<? if($users_event_logs['uid'] > 0): ?>
										<? if($uid = rb("{$conf['db']['prefix']}users", "id", $users_event_logs['uid'])): ?>
											<?=$uid['name']?>
										<? else: ?>
											<span style="color:red;"><?=$users_event_logs['uid']?></span>
										<? endif; ?>
									<? else: ?>
										<?=$conf['settings']['default_usr']?><?=$users_event_logs['uid']?>
									<? endif; ?>
								</span>
								<span><?=date("d.m.Y H:i", $users_event_logs['time'])?></span>
							</div>
						<? endforeach; ?>
					<? endif; ?>
				<? endforeach; ?>
			<? else: mpre("Событие не найдено"); endif; ?>
		</div>
	<? endif; ?>
</div>
