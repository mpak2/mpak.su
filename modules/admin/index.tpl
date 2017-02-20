<div class="cont">
	<ul class="nl MdlsList">
		<? foreach(rb("modules-index", "admin", "id", $_GET['id']) as $modules_index): ?>
			<? if((!$MODULES_INDEX_UACCESS = rb("modules-index_uaccess", "mid", "uid", "id", $modules_index['id'], $conf['user']['uid'])) &0): mpre("Ошибка выборки прав пользователей") ?>
			<? elseif((!$MODULES_INDEX_GACCESS = rb("modules-index_gaccess", "mid", "gid", "id", $modules_index['id'], $conf['user']['gid'])) &0): mpre("Ошибка выборки прав пользователей") ?>
			<? elseif(!$gmax = ($MODULES_INDEX_GACCESS ? max(array_column($MODULES_INDEX_GACCESS, 'admin_access')) : 1)): mpre("Ошибка максимального разрешения для группы"); ?>
			<? elseif(!$umax = ($MODULES_INDEX_UACCESS ? max(array_column($MODULES_INDEX_UACCESS, 'admin_access')) : 1)): mpre("Ошибка максимального разрешения для пользователя"); ?>
			<? elseif(!is_numeric(array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr']))) && (max($umax, $gmax) < 4)):// mpre("Недостаточно прав доступа к разделу"); ?>
			<? else: ?>
				<li>
					<a href="/<?=$modules_index['folder']?>"><img src="/admin:img/<?=$modules_index['id']?>/null/modules.png" alt="" /></a>
					<h1><a href="/<?=$modules_index['folder']?>:admin"><?=$modules_index['name']?></a></h1>
					<p><?=$modules_index['description']?></p>
	<!--				<div class="button"><a href="/admin/hide:<?=$modules_index['id']?>/<?=$_GET['id']?>">скрыть</a></div>-->
					<? if($conf['modules']['settings']['admin_access'] >= 4): ?>
						<div class="button"><a href="/settings:admin/r:mp_settings/?&where[modpath]=<?=$modules_index['folder']?>">настройки</a></div>
					<? endif; ?>
				</li>
			<? endif; ?>
		<? endforeach; ?>
	</ul>
</div>
