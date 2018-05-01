<? if(!$ADMIN = mpqn(qw("SELECT * FROM {$conf['db']['prefix']}admin_index", function($error) use($conf){
		if(("mysql" == $conf['db']['type']) && (!qw("ALTER TABLE `{$conf['db']['prefix']}admin` RENAME `{$conf['db']['prefix']}admin_index`"))){ mpre("ОШИБКА переименования таблицы админразделов");
		}elseif(("sqlite" == $conf['db']['type']) && (!qw("ALTER TABLE `{$conf['db']['prefix']}admin` RENAME TO `{$conf['db']['prefix']}admin_index`"))){ mpre("ОШИБКА переименования таблицы админразделов");
		}else{ mpre("ОШИБКА переименования таблицы админразделов для БД `{$conf['db']['type']}`", $error); }
	}))): mpre("Админразделы не установлены") ?>
<? elseif(!$MODULES_INDEX = rb("modules-index")): mpre("Список модейлей пуст") ?>
<? elseif(!$modpath = (array_search('admin', $_GET['m']) ?: first(array_flip($_GET['m'])))): mpre("Ошибка определения имени раздела") ?>
<? else: ?>
	<div>
		<script sync>
			(function($, script){
				$(script).parent().on("click", "a.del", function(e){
					$(e.delegateTarget).parents(".left-menu").remove();
				})
			})(jQuery, document.currentScript)
		</script>
		<div style="float:right;">
			<a class="del" href="javascript:">
				<img src="/img/del.png">
			</a>
		</div>
		<ul class="nl ModulesList">
			<? foreach($ADMIN as $admin): ?>
				<li><h2><?=$admin['name']?></h2></li>
				<? foreach(rb($MODULES_INDEX, "admin", "id", $admin['id']) as $modules_index): ?>
					<? if((!$MODULES_INDEX_UACCESS = rb("modules-index_uaccess", "mid", "uid", "id", $modules_index['id'], $conf['user']['uid'])) &0): mpre("Ошибка выборки прав пользователей") ?>
					<? elseif((!$MODULES_INDEX_GACCESS = rb("modules-index_gaccess", "mid", "gid", "id", $modules_index['id'], $conf['user']['gid'])) &0): mpre("Ошибка выборки прав пользователей") ?>
					<? elseif(!$gmax = ($MODULES_INDEX_GACCESS ? max(array_column($MODULES_INDEX_GACCESS, 'admin_access')) : 1)): mpre("Ошибка максимального разрешения для группы"); ?>
					<? elseif(!$umax = ($MODULES_INDEX_UACCESS ? max(array_column($MODULES_INDEX_UACCESS, 'admin_access')) : 1)): mpre("Ошибка максимального разрешения для пользователя"); ?>
					<? elseif(!is_numeric(array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr']))) && (max($umax, $gmax) < 5)):// mpre("Недостаточно прав доступа к разделу"); ?>
					<? else: ?>
						<li modpath="<?=$modules_index['folder']?>" class="<?=($modpath == $modules_index['folder'] ? "act" : "")?>">
							<a href="/<?=$modules_index['folder']?>:admin"><?=$modules_index['name']?></a>
						</li>
					<? endif; ?>
				<? endforeach; ?>
			<? endforeach; ?>
		</ul>
	</div>
<? endif; ?>

