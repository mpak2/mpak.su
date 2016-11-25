<h1>Статистика нагрузки</h1>
<? if(!$HISTORY = qn($sql = "SELECT id,time,uid,history_type_id FROM {$conf['db']['prefix']}admin_history WHERE time>". strtotime($period = "-3 month"))): mpre("Изменения за месяц не найдены {$sql}") ?>
<? elseif(!$USERS = rb("{$conf['db']['prefix']}users", "id", "id", rb($HISTORY, "uid"))): mpre("Пользователи не найдены") ?>
<? elseif(!$__HISTORY__ = array_map(function($history){
		return $history + ['_date'=>date("Y.m.d", strtotime(date("Y-m-d", $history['time'])))];
	}, $HISTORY)): mpre("Ошибка разбивки статистики по пользователям") ?>
<? elseif(!$_HISTORY = rb($__HISTORY__, "_date", "id")): mpre("Ошибка формирования двухуровневого массива дат") ?>
<? elseif(!$HISTORY_TYPE = rb("admin-history_type")): mpre("Ошибка выборки истории типа") ?>
<? elseif(!$WEEK = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс']): mpre("Ошика задания массива недели") ?>
<? else:// mpre($_HISTORY) ?>
	<div class="table">
		<div class="th">
			<span>Дата</span>
			<? foreach($USERS as $users): ?>
				<span><?=$users['name']?></span>
			<? endforeach; ?>
		</div>
		<? for($t = strtotime($period); $t<= time(); $t += 86400): $time = strtotime(date("Y-m-d", $t)); ?>
			<div style="background-color:<?=((date("N", $time) > 5) ? "#ccc" : "inherit")?>;">
				<? if(!$_date = date("Y.m.d", $time)): mpre("Ошибка вычисления даты") ?>
				<? elseif(!($_HISTORY_ = get($_HISTORY, $_date)) &0): mpre("Элементов на дату не найдено"); ?>
				<? else: ?>
					<span><?=$_date?> (<?=get($WEEK, date("N", $time))?>)</span>
					<? foreach($USERS as $users): ?>
						<span>
							<? foreach($HISTORY_TYPE as $history_type): ?>
									<? if(!($count = count(rb($_HISTORY_, "uid", "history_type_id", "id", $users['id'], $history_type['id']))) &0):// mpre("Нет элементов") ?>
									<? else: ?>
										<span title="<?=$history_type['name']?>"><?=$count?></span> /
									<? endif; ?>
							<? endforeach; ?>
							<span title="Операций суммарно"><b><?=count(rb($_HISTORY_, "uid", "id", $users['id']))?></b></span>
						</span>
					<? endforeach; ?>
				<? endif; ?>
			</div>
		<? endfor; ?>
		<div class="th">
			<span>Суммарно</span>
			<? foreach($USERS as $users): ?>
				<span>
					<? foreach($HISTORY_TYPE as $history_type): ?>
						<? if(!($count = count(rb($__HISTORY__, "uid", "history_type_id", "id", $users['id'], $history_type['id']))) &0):// mpre("Нет элементов") ?>
						<? else: ?>
							<span title="<?=$history_type['name']?>"><?=$count?></span>/
						<? endif; ?>
					<? endforeach; ?>
					<span title="Операций суммарно"><b><?=count(rb($__HISTORY__, "uid", "id", $users['id']))?></b></span>
				</span>
			<? endforeach; ?>
		</div>
	</div>
<? endif; ?>
