<? if(!$uid = rb("{$conf['db']['prefix']}users", "id", $conf['user']['uid'])): mpre("Зарегистрированный пользователь ненайден") ?>
	<? inc("modules/users/staffers_menu.tpl") ?>
<? elseif(!$THEMES_YANDEX = rb('themes-yandex')): mpre("Список приложений не найден") ?>
<? else: ?>
	<h1>Приложение</h1>
	<? foreach($THEMES_YANDEX as $themes_yandex): ?>
		<ul>
			<li>
				<a href="/themes:admin/r:themes-yandex?&where[id]=<?=$themes_yandex['id']?>"><?=$themes_yandex['name']?></a>
				<a href="https://oauth.yandex.ru/authorize?response_type=token&client_id=<?=$themes_yandex['code']?>" target="blank">Получить токен</a>
			</li>
		</ul>
	<? endforeach; ?>
<? endif; ?>