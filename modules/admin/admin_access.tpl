<div class="themes_access">
	<style>
		.themes_access .table > div > span {padding:5px;}
		.themes_access .table > div > span {border-bottom:1px solid #eee;}
	</style>
	<h1 style="padding:10px;">Доступы к корпоративным ресурсам</h1>
	<? if($tpl['admin_access'] = rb("{$conf['db']['prefix']}admin_access", "uid", "id", $conf['user']['uid'])): ?>
		<div class="table">
			<div class="th">
				<span>Доступ</span>
				<span>Изменен</span>
				<span>Форма авторизации</span>
				<span>Логин</span>
				<span>Пароль</span>
				<span>Кому доступен</span>
			</div>
			<? // $tpl['admin_access'] as $admin_access): ?>
				<? foreach(rb("{$conf['db']['prefix']}admin_access_accounts") as $access_accounts): ?>
					<div>
						<span>
							<?=$access_accounts['name']?>
						</span>
						<span>
							<? if($access_passwords = rb("{$conf['db']['prefix']}admin_access_passwords", "access_accounts_id", $access_accounts['id'])): ?>
								<?=date("d.m.Y H:i", $access_passwords['time'])?>
							<? endif; ?>
						</span>
						<span><a href="<?=$access_accounts['href']?>" target="blank"><?=$access_accounts['href']?></a></span>
						<span><?=$access_accounts['login']?></span>
						<span>
							<? if($admin_access = rb("{$conf['db']['prefix']}admin_access", "uid", "access_accounts_id", $conf['user']['uid'], $access_accounts['id'])): ?>
								<?=$access_passwords['name']?>
							<? else: ?>
								<span style="color:#ccc;">[скрыт]</span>
							<? endif; ?>
						</span>
						<span>
							<ul>
								<? foreach(rb("{$conf['db']['prefix']}admin_access", "access_accounts_id", "id", $access_accounts['id']) as $admin_access): ?>
									<? if($uid = rb("{$conf['db']['prefix']}users", "id", $admin_access['uid'])): ?>
										<li><?=$uid['name']?></li>
									<? endif; ?>
								<? endforeach; ?>
							</ul>
						</span>
					</div>
				<? endforeach; ?>
			<?// endforeach; ?>
		</div>
	<? endif; ?>
</div>
