<div class="themes_access">
	<style>
		.themes_access .table > div > span {padding:5px;}
		.themes_access .table > div > span {border-bottom:1px solid #eee;}
	</style>
	<h1 style="padding:10px;">Доступы к корпоративным ресурсам</h1>
	<? if($ADMIN_ACCESS = rb("admin-access")): ?>
		<div class="table">
			<div class="th">
				<span>Доступ</span>
				<span>Изменен</span>
				<span>Форма авторизации</span>
				<span>Логин</span>
				<span>Пароль</span>
				<span>Кому доступен</span>
			</div>
			<? foreach(rb("admin-access_accounts") as $access_accounts): ?>
				<div>
					<span>
						<?=$access_accounts['name']?>
					</span>
					<span>
						<? if($access_passwords = rb("admin-access_passwords", "access_accounts_id", $access_accounts['id'])): ?>
							<?=date("d.m.Y H:i", $access_passwords['time'])?>
						<? endif; ?>
					</span>
					<span><a href="<?=$access_accounts['href']?>" target="blank"><?=$access_accounts['href']?></a></span>
					<span><?=$access_accounts['login']?></span>
					<span>
						<? if($admin_access = rb($ADMIN_ACCESS, "uid", "access_accounts_id", $conf['user']['uid'], "[{$access_accounts['id']},0,NULL]")): ?>
							<?=get($access_passwords, 'name')?>
						<? else: ?>
							<span style="color:#ccc;">[скрыт]</span>
						<? endif; ?>
					</span>
					<span>
						<ul>
							<? foreach(rb($ADMIN_ACCESS, "access_accounts_id", "id", "[{$access_accounts['id']},0,NULL]") as $admin_access): ?>
								<? if($uid = rb("{$conf['db']['prefix']}users", "id", $admin_access['uid'])): ?>
									<li><?=$uid['name']?></li>
								<? endif; ?>
							<? endforeach; ?>
						</ul>
					</span>
				</div>
			<? endforeach; ?>
		</div>
	<? endif; ?>
</div>
