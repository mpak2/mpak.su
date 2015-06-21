<? die;

mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_cat` (`name`) VALUES ('Категория')"); # Категория для добавляемых страниц
$page_text = <<<EOF
<div style="text-align: center; margin: 150px 10px;"><span style="font-weight: bold;">Поздравляем Вас с созданием сайта.</span><br />Отредактировать данную страницу можно<br />нажав на ссылку <a title="Редатировать" href="/?m[pages]=admin&amp;edit=1">Редатировать</a>. Все необходимое<br />&nbsp;для работы Вы найдете в <a title="Админстранице" href="/admin">Админстранице</a> сайта.<br />Оставляйте свои замечания и предложения <a title="Здесь" href="http://mpak.su/develop/kid:0">Здесь</a><br />Буду рад ответить на вопросы по <span style="font-weight: bold;">icq 264723755</span><br />
</div>
EOF;
//mpqw("INSERT INTO `{$conf['db']['prefix']}{$arg['modpath']}_post` (`kid`, `uid`, `date`, `name`, `text`) VALUES (".mysql_insert_id().", 1, ".time().", 'Стартовая страница', '".mpquot($page_text)."')"); # Стартовая страница
