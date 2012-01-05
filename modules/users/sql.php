<? die;

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_auth (`id` ,`name` ,`auth` ,`description`) VALUES ('1' ,'Логин' ,'1' ,'Авторизация посредством логина и пароля'), ('2' ,'OpenID' ,'1' ,'Открытая децентрализованная система единого входа');");

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_grp (`name`) VALUES ('Гость'), ('Администратор'), ('Зарегистрированные')"); #Зарегистрированные пользователей

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']} (`tid`, `name`, `pass`, `reg_time`) VALUES (1, 'гость', 'nopass', '".time()."')"); # Гость
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']} (`tid`, `name`, `pass`, `reg_time`) VALUES (1, '".mpquot($_POST['user'])."', '".mphash($_POST['user'], $_POST['pass1'])."', '".time()."')"); # Создаем пользователя Администратор

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mem (`uid`, `gid`) VALUES ((SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE name = 'гость'), (SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_grp WHERE name = 'Гость'))"); # Добавляем пользователя гость в группу гость
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mem (`uid`, `gid`) VALUES ((SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE name = '{$_POST['user']}'), (SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_grp WHERE name = 'Администратор'))"); # Добавляем Администратора группу Администраторов
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mem (`uid`, `gid`) VALUES ((SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE name = '{$_POST['user']}'), (SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_grp WHERE name = 'Зарегистрированные'))"); # Добавляем Администратора в группу Зарегистрированных прользователей

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_type (`id` ,`name` ,`auth` ,`description`) VALUES ('1' ,'Логин' ,'1' ,'Авторизация посредством логина и пароля')");
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_type (`id` ,`name` ,`auth` ,`description`) VALUES ('2' ,'OpenID' ,'1' ,'Открытая децентрализованная система единого входа')");

?>