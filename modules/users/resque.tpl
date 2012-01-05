<div style="margin:120px auto; text-align:center;">
	<? if($_POST['resque']): ?>
		Пароль изменен
	<? elseif($conf['tpl']['resque']): ?>
		<form method="post">
			<input type="hidden" name="resque" value="<?=$conf['tpl']['resque']?>">
			Введите новый пароль <input type="pass" name="pass">
			<input type="submit" value="Изменить">
		</form>
	<? else: ?>
		<form method="post">
			<div style="margin:10px;">
				<? if($conf['tpl']['user']): ?>
					Вам на почту выслан код подтверждения
				<? elseif($_POST['email']): ?>
					Пользователь с указанным адресом не найден
				<? else: ?>
					Укажите адрес электронной почты пользователя
				<? endif; ?>
			</div>
			<div>
				<input type="text" name="email" title="Адрес электронной почты">
				<input type="submit" value="Восстановить">
			</div>
		</form>
	<? endif; ?>
</div>
