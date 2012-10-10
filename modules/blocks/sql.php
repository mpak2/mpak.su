<? die;

$users = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE folder=\"users\""), 0);

mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_reg` (`id`, `reg_id`, `mid`, `fn`, `description`) VALUES ('1', '0', '0', '', 'Общая Лево'), ('2', '0', '0', '', 'Общая Право'), ('3', '0', '0', '', 'Верх'), ('4', '0', '0', '', 'АдминШапка'), ('5', '0', '0', '', 'Лево'), ('6', '0', '0', '', 'Право'), ('7', '5', '{$users['id']}', 'index', 'Кабинет'), ('8', '6', '{$users['id']}', 'index', 'Кабинет')");

mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_shablon` (`id`, `name`, `description`, `shablon`) VALUES ('1', 'Основной', 'Блок по умолчанию', '<table width=240px cellspacing=0 cellpadding=5 border=0> <tr> <td align=center bgcolor=<!-- [settings:theme_block_color] -->><b><!-- [block:title] --></b></td> </tr> <tr> <td><!-- [block:content] --></td> </tr> </table>'), ('2', 'БезТитла', 'Блок без заголовка', '<!-- [block:content] -->')");

$amenu = <<<EOF
<ul>
	<li><a href="/?m[pages]=admin&edit">Добавить новую страницу</a>
	<li><a href="/?m[settings]=admin&where[modpath]=themes">Настройки сайта</a>
	<li><a href="/?m[users]=admin">Пользователи</a>
	<li><a href="/?m[sess]=admin">Входы на сайт</a>
	<li><a href="/?m[blocks]=admin">Управление блоками</a>
	<li><a href="/?m[menu]=admin">Главное меню</a>
	<li><a href="/?m[foto]=admin">Добавить фото</a>
	<li><a href="/?m[video]=admin">Видео файлы</a>
	<li><a href="/?m[poll]=admin">Добавить опрос</a>
	<li><a href="http://mpak.su/faq">Частые вопросы</a>
</ul>
EOF;

$spt = <<<EOF
Вопросы по работе системы задавайте в icq 264723755  т. +79312273177. По оформлению дизайна шаблонов модулей и блоков в icq 264723755
EOF;

$menu = array(
//	array('id'=>'0', 'theme'=>'!zhiraf', 'file'=>'themes/blocks/theme.php', 'name'=>'Выбор темы', 'access'=>'0', 'rid'=>'1', 'enabled'=>'1', ),
	array('id'=>'1', 'theme'=>'!zhiraf', 'file'=>'../include/blocks/login.php', 'name'=>'Авторизация', 'access'=>'1', 'rid'=>'1', 'enabled'=>'1', ),
	array('id'=>'2', 'theme'=>'!zhiraf', 'file'=>'menu/blocks/mnu.php', 'name'=>'Верхнее меню', 'shablon'=>'2', 'access'=>'1', 'rid'=>'3', 'param'=>'a:2:{s:4:"menu";s:1:"2";s:3:"tpl";s:7:"top.tpl";}', 'enabled'=>'1', ),
	array('id'=>'3', 'theme'=>'!zhiraf', 'file'=>'pages/blocks/list.php', 'name'=>'Меню', 'access'=>'1', 'rid'=>'1', 'enabled'=>'1'),
	array('id'=>'4', 'theme'=>'!zhiraf', 'file'=>'../include/blocks/htmlcod.php', 'name'=>'Админменю', 'access'=>'0', 'rid'=>'1', 'param'=>$amenu, 'enabled'=>'1',),
	array('id'=>'5', 'theme'=>'!zhiraf', 'file'=>'messages/blocks/messages.php', 'name'=>'Сообщения', 'access'=>'1', 'rid'=>'1', 'enabled'=>'1', ),
	array('id'=>'6', 'theme'=>'!zhiraf', 'file'=>'../include/blocks/htmlcod.php', 'name'=>'Поддержка', 'access'=>'0', 'rid'=>'1', 'param'=>$spt, 'enabled'=>'1',),

	array('id'=>'7', 'theme'=>'zhiraf', 'file'=>'admin/blocks/top.php', 'name'=>'АдминШапка', 'access'=>'0', 'rid'=>'4', 'enabled'=>'1',),
	array('id'=>'8', 'theme'=>'zhiraf', 'file'=>'admin/blocks/modlist.php', 'name'=>'СписокМодулей', 'access'=>'0', 'rid'=>'1', 'enabled'=>'1',),

	array('id'=>'9', 'theme'=>'!zhiraf', 'file'=>'users/blocks/user.php', 'name'=>'Свойства пользователя', 'access'=>'1', 'rid'=>'7', 'enabled'=>'1',),

	array('id'=>'10', 'theme'=>'!zhiraf', 'file'=>'chat/blocks/all.php', 'name'=>'Чат', 'access'=>'2', 'rid'=>'8', 'enabled'=>'1',),
	array('id'=>'11', 'theme'=>'!zhiraf', 'file'=>'foto/blocks/img.php', 'name'=>'Мои фото', 'access'=>'1', 'rid'=>'7', 'enabled'=>'1',),
	array('id'=>'12', 'theme'=>'!zhiraf', 'file'=>'pages/blocks/my.php', 'name'=>'Мои статьи', 'access'=>'1', 'rid'=>'7', 'enabled'=>'1',),
);

foreach($menu as $sort=>$line){
	$values = array();
	foreach($line as $k=>$v){
		$values[] = " `$k`='".mpquot($v)."'";
	}
	mpqw($sql = "INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}` SET ".implode(', ', $values). ", `orderby`='$sort'");
//	echo $sql;
}
mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_gaccess` SET `bid`='1', `gid`='2', `access`='1', `description`='Права доступа администратора к блоку смены шаблонов'");
mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_gaccess` SET `bid`='3', `gid`='2', `access`='1', `description`='Права доступа администратора к блоку админменю'");
mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_gaccess` SET `bid`='6', `gid`='2', `access`='1', `description`='Права доступа администратора к блоку поддержка'");

mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_gaccess` SET `bid`='7', `gid`='2', `access`='1', `description`='Права доступа администратора к админ шапке'");
mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_gaccess` SET `bid`='8', `gid`='2', `access`='1', `description`='Права доступа администратора к списку модулей'");

?>