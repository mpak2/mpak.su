<? die;

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_region SET id=1, name=\"Основное\", description=\"Меню в боковой колонке блоков\"");
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_region SET id=2, name=\"Верхнее\", description=\"Меню в верхней части сайта\"");

$menu = array(
	array('region_id'=>1, 'name'=>'Главная страница', 'href'=>'/', 'description'=>'Ссылка на главную страницу',),
	array('region_id'=>1, 'name'=>'Личный кабинет', 'href'=>'/users/0', 'description'=>'Свойства пользователя',),
	array('region_id'=>1, 'name'=>'Статьи', 'href'=>'/pages:list/cid:1', 'description'=>'Список статей сайта',),
	array('region_id'=>1, 'name'=>'Гостевая книга', 'href'=>'/gbook', 'description'=>'Обратная связь с пользователями',),
	array('region_id'=>1, 'name'=>'Фотогаллерея', 'href'=>'/foto', 'description'=>'Мои фотографии',),
	array('region_id'=>1, 'name'=>'Видео', 'href'=>'/video/1', 'description'=>'Видео материалы', ),
	array('region_id'=>1, 'name'=>'Чат', 'href'=>'/chat','description'=>'Место для общения',),

	array('region_id'=>2, 'name'=> 'Обо мне', 'href'=>'/users/0', 'description'=> 'Рассказ о себе',),
	array('region_id'=>2, 'name'=> 'Услуги', 'href'=>'/pages/3', 'description'=> 'Список услуг',),
	array('region_id'=>2, 'name'=> 'Цены', 'href'=>'/pages/4', 'description'=> 'Прайс лист',),
	array('region_id'=>2, 'name'=> 'Котакты', 'href'=>'/pages/5', 'description'=> 'Контактные данные',),
);

foreach($menu as $n=>$line){
	$values = array(0=>" `sort`=\"$n\"");
	foreach($line as $k=>$v){
		$values[] = " `$k`=\"".mpquot($v)."\"";
	}
	mpqw($sql = "INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index` SET ".implode(', ', $values));
}

?>