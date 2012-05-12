<? die;

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_region SET id=1, name=\"Основное\", description=\"Меню в боковой колонке блоков\"");
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_region SET id=2, name=\"Верхнее\", description=\"Меню в верхней части сайта\"");

$menu = array(
	array('rid'=>1, 'name'=>'Главная страница', 'link'=>'/', 'description'=>'Ссылка на главную страницу',),
	array('rid'=>1, 'name'=>'Личный кабинет', 'link'=>'/users/0', 'description'=>'Свойства пользователя',),
	array('rid'=>1, 'name'=>'Статьи', 'link'=>'/pages:list/cid:1', 'description'=>'Список статей сайта',),
	array('rid'=>1, 'name'=>'Гостевая книга', 'link'=>'/gbook', 'description'=>'Обратная связь с пользователями',),
	array('rid'=>1, 'name'=>'Фотогаллерея', 'link'=>'/foto', 'description'=>'Мои фотографии',),
	array('rid'=>1, 'name'=>'Видео', 'link'=>'/video/1', 'description'=>'Видео материалы', ),
	array('rid'=>1, 'name'=>'Чат', 'link'=>'/chat','description'=>'Место для общения',),

	array('rid'=>2, 'name'=> 'Обо мне', 'link'=>'/users/0', 'description'=> 'Рассказ о себе',),
	array('rid'=>2, 'name'=> 'Услуги', 'link'=>'/pages/3', 'description'=> 'Список услуг',),
	array('rid'=>2, 'name'=> 'Цены', 'link'=>'/pages/4', 'description'=> 'Прайс лист',),
	array('rid'=>2, 'name'=> 'Котакты', 'link'=>'/pages/5', 'description'=> 'Контактные данные',),
);

foreach($menu as $n=>$line){
	$values = array(0=>" `orderby`=\"$n\"");
	foreach($line as $k=>$v){
		$values[] = " `$k`=\"".mpquot($v)."\"";
	}
	mpqw($sql = "INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}` SET ".implode(', ', $values));
}

?>