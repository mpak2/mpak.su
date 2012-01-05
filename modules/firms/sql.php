<? die;

echo 123;

$sql = <<<EOF
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mop (`id` ,`name` ,`description`) VALUES ('1' ,'(другой)' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mop (`id` ,`name` ,`description`) VALUES ('2' ,'БИ ЛАЙН' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mop (`id` ,`name` ,`description`) VALUES ('3' ,'МЕГАФОН' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mop (`id` ,`name` ,`description`) VALUES ('4' ,'МТС' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mop (`id` ,`name` ,`description`) VALUES ('5' ,'СКАЙЛИНК' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mop (`id` ,`name` ,`description`) VALUES ('6' ,'ТЕЛЕ2' ,'');
EOF;

foreach(explode("\n", $sql) as $k=>$v) mpqw($v); # Мобильные операторы

$sql = <<<EOF
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('11' ,'Мото транспорт' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('10' ,'Юридические услуги' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('9' ,'Авто перевозки' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('8' ,'Строительство' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('7' ,'Финансы и кредит' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('12' ,'Мебель ' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('13' ,'Веб программирование' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('14' ,'Верстка' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_prof (`id` ,`name` ,`description`) VALUES ('15' ,'Медицина' ,'');
EOF;

foreach(explode("\n", $sql) as $k=>$v) mpqw($v); # Профиль деятельности

$sql = <<<EOF
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('1' ,'АО' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('2' ,'ИП' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('3' ,'ОАО' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('4' ,'ООО' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('5' ,'ПБОЮЛ' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('6' ,'ТОО' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('7' ,'ЧП' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('8' ,'ЗАО' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('9' ,'АКОТ' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('10' ,'НП' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('11' ,'СПД' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('12' ,'СП' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('13' ,'ДП' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('14' ,'НПФ' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('15' ,'НПП' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('16' ,'Ltd.' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('17' ,'КХ' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('18' ,'ПК' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('19' ,'ФЛП' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('20' ,'ОДО' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sobst (`id` ,`name` ,`description`) VALUES ('21' ,'ИП нерз.' ,'');
EOF;

foreach(explode("\n", $sql) as $k=>$v) mpqw($v); # Форма собственности

$sql = <<<EOF
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('1' ,'Yandex' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('2' ,'Google' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('3' ,'Rambler' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('4' ,'Mail.ru' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('5' ,'Webalta' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('6' ,'Aport' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('7' ,'Ссылка с другого сайта' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('8' ,'Рекомендация партнера по бизнесу' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('9' ,'Журнал  "АвтоТрансИнфо"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('10' ,'Журнал "Товары и Цены"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('11' ,'Журнал "Снабженец"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('12' ,'Периодический журнал г.Омска' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('13' ,'Периодический журнал г.Томска' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('14' ,'Периодический журнал г.Хабаровска' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('15' ,'Периодический журнал г.Новосибирска' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('16' ,'Периодический журнал г.Владивостока' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('17' ,'Периодический журнал г.Красноярска' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('18' ,'Периодический журнал г.Кемерово' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('19' ,'Периодический журнал г.Барнаула' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('20' ,'Выставка' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('21' ,'Отраслевой транспортный журнал' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('22' ,'Отраслевой справочник по транспорту' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('23' ,'Справочник "Желтые страницы' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('24' ,'Справочник "Бизнес-Адрес"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('25' ,'Справочник "Весь Н. Новгород"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('26' ,'Справочник "Весь Екатеринбург"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('27' ,'Справочник "Весь Пермский край"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('28' ,'Справочник "Золотые страницы Сибири"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('29' ,'Справочник "Русский Бизнес Север"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('30' ,'Справочник "Контакт"' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_src (`id` ,`name` ,`description`) VALUES ('31' ,'Другое' ,'');
EOF;

foreach(explode("\n", $sql) as $k=>$v) mpqw($v); # Откуда узнали

$sql = <<<EOF
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('1' ,'(GMT -12:00) Меридиан смены дат' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('2' ,'(GMT -11:00) о.Мидуэй' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('3' ,'(GMT -10:00) Гавайи' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('4' ,'(GMT -09:00) Аляска' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('5' ,'(GMT -08:00) Тихуана' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('6' ,'(GMT -07:00) Аризона, Ла Пас' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('7' ,'(GMT -06:00) Гвадалахара, Мехико, Центральная Америка' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('8' ,'(GMT -05:00) Восточное время (США и Канада)' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('9' ,'(GMT -04:30) Каракас' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('10' ,'(GMT -04:00) Сантьяго' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('11' ,'(GMT -03:30) Ньюфаундленд' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('12' ,'(GMT -03:00) Грендландия, Джорджтаун' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('13' ,'(GMT -02:00) Среднеатлантическое время' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('14' ,'(GMT -01:00) Азорские о-ва, О-ва Зелёного мыса' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('15' ,'(GMT+00:00) Лондон, Касабланка' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('16' ,'(GMT+01:00) Белград, Варшава, Брюссель, Амстердам' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('17' ,'(GMT+02:00) Минск, Киев, Хельсинки, Афины, Иерусалим' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('18' ,'(GMT+03:00) Москва, Санкт-Петербург, Тбилиси' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('19' ,'(GMT+03:30) Тегеран' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('20' ,'(GMT+04:00) Ереван, Баку, Абу-Даби, Порт-Луи' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('21' ,'(GMT+04:30) Кабул' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('22' ,'(GMT+05:00) Екатеринбург, Ташкент, Исламбад' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('23' ,'(GMT+05:30) Бомбей, Калькутта, Шри Джаяварденепура' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('24' ,'(GMT+05:45) Катманду' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('25' ,'(GMT+06:00) Омск, Новосибирск, Астана' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('26' ,'(GMT+06:30) Янгун' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('27' ,'(GMT+07:00) Красноярск, Бангкок, Джакарта' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('28' ,'(GMT+08:00) Иркутск, Гонконг, Пекин, Тайпей, Перт' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('29' ,'(GMT+09:00) Якутск, Сеул, Токио' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('30' ,'(GMT+09:30) Аделаида, Дарвин' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('31' ,'(GMT+10:00) Владивосток, Хобарт, Канбера, Гуам' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('32' ,'(GMT+11:00) Сахалин, Магадан' ,'');
INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_tz (`id` ,`name` ,`description`) VALUES ('33' ,'(GMT+12:00) Камчатка, Фиджи, Окленд' ,'');
EOF;

foreach(explode("\n", $sql) as $k=>$v) mpqw($v); # Часовой пояс

?>