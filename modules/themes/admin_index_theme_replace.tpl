<? if(!$file = get($_FILES, 'theme')):// mpre("Файлы не найдены") ?>
<? elseif($file['error']): mpre("Ошибка в загрузке файла") ?>
<? elseif(!$file['size']): mpre("Нулевой размер файла") ?>
<? elseif(($t = $file['type']) != ($w = "text/html")): mpre("Ожидается файл с типом <b>{$w}</b> а загрузен <b>{$t}</b>") ?>
<? elseif(!$f = mpopendir("include/class/simple_html_dom.php")): mpre("Ошибка полиска абсолютного пути до библиотеки дом") ?>
<? elseif(!require($f)): mpre("Ошибка подключения библиотеки парсера") ?>
<? elseif(!$html = new simple_html_dom()): mpre("Ошибка создания класса парсера") ?>
<? elseif(!$html->load($data = mpde(file_get_contents($w = $file['tmp_name'])))): mpre("Ошибка загрузки парсера из файла") ?>

<? elseif($html->find($w = "base", 0) && ($html->find($w = "base", 0)->href = "/themes/theme:<!-- [settings:theme] -->/null/")): mpre("Ошибка изменения пути к шаблону <b>{$w}</b>") ?>
<? elseif(!$html->find($w = "base", 0) && !(($header = $html->find($w = "head", -1)) && ($header->innertext = "\n<base href=\"/themes/theme:<!-- [settings:theme] -->/null/\">\n". $header->innertext))): mpre("Ошибка добавления пути к шаблону <b>{$w}</b>") ?>

<? elseif(($header = $html->find($w = "head", -1)) && !($header->innertext = $header->innertext. "\n<!-- [block:header] -->\n")): mpre("Ошибка добавления блока админстриптов <b>{$w}</b>") ?>
<? elseif(!$layout = $html->find($w = ".art-content-layout", 0)): mpre("Не найена центральная область шаблона <b>{$w}</b>") ?>
<? elseif(!$html->find($w = ".art-content-layout", 0)->innertext = "\n<p><!-- [block:adsense] --></p>\n<!-- [modules] -->\n"): mpre("Не найена центральная область и адсайнс шаблона <b>{$w}</b>") ?>

<? elseif(!$html->save($theme = "{$file['tmp_name']}_2")): mpre("Ошибка сохранения содержимого страницы") ?>
<? elseif(!$content = file_get_contents($theme)): mpre("Получение содержимого итогового шаблона") ?>

<? elseif(!$content = preg_replace("#<title>(.*)<\/title>#", "<title><!-- [settings:title] --></title>", $content)): mpre("Ошибка замены заголовка title") ?>
<? elseif(!$content = preg_replace("#>\s+<#", ">\n<", $content)): mpre("Ошибка замены переносов строк") ?>
<? elseif(!$content = preg_replace("#<!-- Created by Artisteer v[0-9.]+ -->#", "<!-- Created by denis -->", $content)): mpre("Ошибка замены артистера") ?>
<? elseif(!$content = preg_replace("#Copyright © [0-9]{4}#", "Copyright &copy; ". date("Y"), $content)): mpre("Ошибка замены года текущего копирайта") ?>

<? elseif(!file_put_contents($theme, $content)): mpre("Получение содержимого итогового шаблона") ?>
<? else: header("Content-Disposition: attachment; filename=\"{$file["name"]}\""); exit(readfile($theme)); ?>
	<? echo(nl2br(htmlspecialchars($content))); ?>
<? endif; ?>
<h1>Генерация шаблонов</h1>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="theme">
	<button type="submit">Отправить шаблон</button>
</form>