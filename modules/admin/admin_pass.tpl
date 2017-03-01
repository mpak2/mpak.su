<div class="themes_pass">
	<style>
		.themes_pass .table > div > span {padding:5px;}
		.themes_pass .table > div > span {border-bottom:1px solid #eee;}
	</style>
	<h1 style="padding:10px;">Доступы к корпоративным ресурсам</h1>
	<? if($tpl['admin_pass'] = rb("{$conf['db']['prefix']}admin_pass", "uid", "id", $conf['user']['uid'])): ?>
		<div class="table">
			<div class="th">
				<span>Доступ</span>
				<span>Изменен</span>
				<span>Форма авторизации</span>
				<span>Логин</span>
				<span>Пароль</span>
				<span>Кому доступен</span>
			</div>
			<? // $tpl['admin_pass'] as $admin_pass): ?>
				<? foreach(rb("{$conf['db']['prefix']}admin_pass_accounts") as $pass_accounts): ?>
					<div>
						<span>
							<?=$pass_accounts['name']?>
						</span>
						<span>
							<? if($pass_passwords = rb("{$conf['db']['prefix']}admin_pass_passwords", "pass_accounts_id", $pass_accounts['id'])): ?>
								<?=date("d.m.Y H:i", $pass_passwords['time'])?>
							<? endif; ?>
						</span>
						<span><a href="<?=$pass_accounts['href']?>" target="blank"><?=$pass_accounts['href']?></a></span>
						<span><?=$pass_accounts['login']?></span>
						<span>
							<? if($admin_pass = rb("{$conf['db']['prefix']}admin_pass", "uid", "pass_accounts_id", $conf['user']['uid'], $pass_accounts['id'])): ?>
								<?=$pass_passwords['name']?>
							<? else: ?>
								<span style="color:#ccc;">[скрыт]</span>
							<? endif; ?>
						</span>
						<span>
							<ul>
								<? foreach(rb("{$conf['db']['prefix']}admin_pass", "pass_accounts_id", "id", $pass_accounts['id']) as $admin_pass): ?>
									<? if($uid = rb("{$conf['db']['prefix']}users", "id", $admin_pass['uid'])): ?>
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
