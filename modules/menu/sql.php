<? die;

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_region SET id=1, name=\"Основное\", description=\"Меню в боковой колонке блоков\"");
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_region SET id=2, name=\"Верхнее\", description=\"Меню в верхней части сайта\"");

$menu = array(
	array('region_id'=>1, 'name'=>'Главная страница', 'href'=>'/'),
	array('region_id'=>1, 'name'=>'Личный кабинет', 'href'=>'/users/0'),
	array('region_id'=>1, 'name'=>'Статьи', 'href'=>'/pages:list/cid:1'),
	array('region_id'=>1, 'name'=>'Гостевая книга', 'href'=>'/gbook'),
	array('region_id'=>1, 'name'=>'Фотогаллерея', 'href'=>'/foto'),
	array('region_id'=>1, 'name'=>'Видео', 'href'=>'/video/1'),
	array('region_id'=>1, 'name'=>'Чат', 'href'=>'/chat'),

	array('region_id'=>2, 'name'=> 'Кабинет', 'href'=>'/users'),
	array('region_id'=>2, 'name'=> 'Услуги', 'href'=>'/pages/3'),
	array('region_id'=>2, 'name'=> 'Цены', 'href'=>'/pages/4'),
	array('region_id'=>2, 'name'=> 'Котакты', 'href'=>'/pages/5'),
);

foreach($menu as $n=>$line){
	$values = array(0=>" `sort`=\"$n\"");
	foreach($line as $k=>$v){
		$values[] = " `$k`=\"".mpquot($v)."\"";
	}
	mpqw($sql = "INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_index` SET ".implode(', ', $values));
}
