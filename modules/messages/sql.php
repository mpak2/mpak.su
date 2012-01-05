<? die;

/*$param = <<<EOF
a:4:{i:0;a:5:{s:3:"url";s:25:"/(index.php|)?forum(.*)/i";s:4:"mail";s:4:"mpak";s:7:"subject";s:18:"Сообщение в форуме";s:5:"param";s:14:"charset=cp1251";s:4:"text";s:107:"В форуме новое сообещние.
<p />Ссылка: <a href=/?m[forum]&vid={GET:vid}>Форум</a>
<p />Текст: {POST:text}";}i:1;a:5:{s:3:"url";s:46:"/&#92/(index.php|)&#92?m&#92[domens&#92](.*)/i";s:4:"mail";s:4:"mpak";s:7:"subject";s:18:"Регистрация домена";s:5:"param";s:14:"charset=cp1251";s:4:"text";s:30:"{POST:host} {POST:description}";}i:3;a:5:{s:3:"url";s:30:"/&#92/(index.php|)?zakaz(.*)/i";s:4:"mail";s:4:"mpak";s:7:"subject";s:24:"Сообщение в форму заказа";s:5:"param";s:14:"charset=cp1251";s:4:"text";s:24:"Текст формы: {POST:text}";}i:4;a:5:{s:3:"url";s:30:"/&#92/(index.php|)?gbook(.*)/i";s:4:"mail";s:4:"mpak";s:7:"subject";s:26:"Сообщение в гостевой книге";s:5:"param";s:14:"charset=cp1251";s:4:"text";s:69:"<a href=/gbook>Гостевая книга</a><p />
{POST:name}<p />
{POST:text}";}}
EOF;

echo $sql = "INSERT INTO {$conf['db']['prefix']}blocks (`file` ,`name` ,`theme` ,`shablon` ,`access` ,`pol` ,`rid` ,`orderby` ,`param` ,`enabled`) VALUES ('messages/blocks/mailer.php' ,'Уведомление' ,'' ,'' ,'1' ,'0' ,'1' ,'0' ,\"". mpquot($param). "\" ,'1')";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}blocks_uaccess` SET `bid`='".mysql_insert_id()."', `uid`=2, `access`=1, `description`='Права доступа администратора к уведомлений'");*/

?>