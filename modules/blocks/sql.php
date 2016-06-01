<?

$users = ql("SELECT * FROM {$conf['db']['prefix']}modules WHERE folder=\"users\"", 0);

qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_reg` (`id`, `term`, `reg_id`, `mid`, `fn`, `description`) VALUES ('-1', 1, '1', '0', '', 'Админ'), ('-2', 1, '-2', '0', '', 'Админ'), ('1', 0, '0', '0', '', 'Общая Лево'), ('2', 0, '0', '0', '', 'Общая Право'), ('3', 0, '0', '0', '', 'Верх'), ('4', 0, '0', '0', '', 'АдминШапка'), ('5', 0, '0', '0', '', 'Лево'), ('6', 0, '0', '0', '', 'Право'), ('7', 0, '5', '{$users['id']}', 'index', 'Кабинет'), ('8', 0, '6', '{$users['id']}', 'index', 'Кабинет')");
qw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_reg_modules (`id` ,`sort` ,`reg_id` ,`modules_index` ,`name` ,`theme` ,`uri`) VALUES (NULL ,'0' ,'-1' ,'0' ,'' ,'zhiraf' ,'')");

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

$menu = array(
//	array('id'=>'11', 'theme'=>'!zhiraf', 'src'=>'admin/blocks/host', 'name'=>'Оплата хостинга', 'access'=>'-1', 'reg_id'=>'1', 'hide'=>'1',),

	array('id'=>'1', 'theme'=>'!zhiraf', 'src'=>'users/blocks/login', 'name'=>'Авторизация', 'access'=>'1', 'reg_id'=>'1'),
	array('id'=>'2', 'theme'=>'!zhiraf', 'src'=>'menu/blocks/menu', 'name'=>'Верхнее меню', 'shablon'=>'2', 'access'=>'1', 'reg_id'=>'3'),
	array('id'=>'3', 'theme'=>'!zhiraf', 'src'=>'pages/blocks/list', 'name'=>'Меню', 'access'=>'1', 'reg_id'=>'1'),
//	array('id'=>'4', 'theme'=>'!zhiraf', 'src'=>'../include/blocks/htmlcod', 'name'=>'Админменю', 'access'=>'0', 'reg_id'=>'1', 'param'=>$amenu),
//	array('id'=>'5', 'theme'=>'!zhiraf', 'src'=>'messages/blocks/messages', 'name'=>'Сообщения', 'access'=>'1', 'reg_id'=>'1'),
	array('id'=>'6', 'theme'=>'!zhiraf', 'src'=>'blocks/blocks/support', 'name'=>'Поддержка', 'access'=>'0', 'reg_id'=>'1', 'param'=>$spt),
	array('id'=>'7', 'theme'=>'zhiraf', 'src'=>'admin/blocks/top', 'name'=>'АдминШапка', 'access'=>'0', 'reg_id'=>'4'),
	array('id'=>'8', 'theme'=>'zhiraf', 'src'=>'admin/blocks/modlist', 'name'=>'СписокМодулей', 'access'=>'0', 'reg_id'=>'-1'),
	array('id'=>'9', 'theme'=>'zhiraf', 'src'=>'users/blocks/online', 'name'=>'Кто на сайте', 'access'=>'0', 'reg_id'=>'-1'),

//	array('id'=>'9', 'theme'=>'!zhiraf', 'src'=>'users/blocks/user', 'name'=>'Свойства пользователя', 'access'=>'1', 'reg_id'=>'7'),
//	array('id'=>'12', 'theme'=>'!zhiraf', 'src'=>'pages/blocks/my', 'name'=>'Мои статьи', 'access'=>'1', 'reg_id'=>'7'),
);

foreach($menu as $line){
	$blocks_index = fk("{$conf['db']['prefix']}{$arg['modpath']}_index", $w = array("id"=>$line['id']), $line, $line);
};

qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_gaccess` SET `index_id`='1', `gid`='2', `access`='1', `description`='Права доступа администратора к блоку смены шаблонов'");
qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_gaccess` SET `index_id`='3', `gid`='2', `access`='1', `description`='Права доступа администратора к блоку админменю'");
qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_gaccess` SET `index_id`='6', `gid`='2', `access`='1', `description`='Права доступа администратора к блоку поддержка'");

qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_gaccess` SET `index_id`='7', `gid`='2', `access`='1', `description`='Права доступа администратора к админ шапке'");
qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_gaccess` SET `index_id`='8', `gid`='2', `access`='1', `description`='Права доступа администратора к списку модулей'");
qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_gaccess` SET `index_id`='9', `gid`='2', `access`='1', `description`='Права доступа администратора к кто на сайте'");

//qw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index_gaccess` SET `index_id`='11', `gid`='3', `access`='1', `description`='Права доступа администратора к списку модулей'");

qw("UPDATE `{$conf['db']['prefix']}{$arg['modpath']}_index` SET `sort`=`id`");
qw("UPDATE `{$conf['db']['prefix']}{$arg['modpath']}_reg` SET `sort`=`id`");

?>
