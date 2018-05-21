<? if(!$admin = rb("{$conf['db']['prefix']}admin_index", "id", (get($_GET, 'id') ?: 3))): mpre("Ошибка выборки админ раздела для отображения на главной админки") ?>
	<? qw("ALTER TABLE {$conf['db']['prefix']}admin RENAME {$conf['db']['prefix']}admin_index") ?>
<? elseif(!$MODULES_INDEX = rb('modules-index', 'admin', 'id', $admin['id'])): mpre("Ошибка выборки списка модулей") ?>
<? elseif(!is_array($MODULES_INDEX_UACCESS = rb("modules-index_uaccess"))): mpre("ОШИБКА выборки списка разрешения пользователй") ?>
<? elseif(!is_array($MODULES_INDEX_GACCESS = rb("modules-index_gaccess"))): mpre("ОШИБКА выборки списка разрешений для группы") ?>
<? else: ?>
	<div class="cont">
		<ul class="nl MdlsList">
			<? foreach($MODULES_INDEX as $modules_index): ?>
				<? if((!$_MODULES_INDEX_UACCESS = rb($MODULES_INDEX_UACCESS, "mid", "uid", "id", $modules_index['id'], $conf['user']['uid'])) &0): mpre("Ошибка выборки прав пользователей") ?>
				<? elseif((!$_MODULES_INDEX_GACCESS = rb($MODULES_INDEX_GACCESS, "mid", "gid", "id", $modules_index['id'], $conf['user']['gid'])) &0): mpre("Ошибка выборки прав пользователей") ?>
				<? elseif(!$gmax = ($_MODULES_INDEX_GACCESS ? max(array_column($_MODULES_INDEX_GACCESS, 'admin_access')) : 1)): mpre("Ошибка максимального разрешения для группы"); ?>
				<? elseif(!$umax = ($_MODULES_INDEX_UACCESS ? max(array_column($_MODULES_INDEX_UACCESS, 'admin_access')) : 1)): mpre("Ошибка максимального разрешения для пользователя"); ?>
				<? elseif(!is_numeric(array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr']))) && (max($umax, $gmax) < 4)):// mpre("Недостаточно прав доступа к разделу"); ?>
				<? else: ?>
					<li>
						<a href="/<?=$modules_index['folder']?>"><img src="/admin:img/<?=$modules_index['id']?>/null/modules.png" alt="" /></a>
						<h1><a href="/<?=$modules_index['folder']?>:admin"><?=$modules_index['name']?></a></h1>
						<p><?=$modules_index['description']?></p>
						<? if(get($conf, 'modules', 'settings', 'admin_access') >= 4): ?>
							<div class="button"><a href="/settings:admin/r:mp_settings/?&where[modpath]=<?=$modules_index['folder']?>">настройки</a></div>
						<? endif; ?>
					</li>
				<? endif; ?>
			<? endforeach; ?>
		</ul>
	</div>
<? endif; ?>
