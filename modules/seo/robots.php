<? $_GET['null'] = header("Content-Type: text/plain") ?>
<? foreach(array("Yandex", "*") as $n=>$r): ?>
<?="User-agent: {$r}\n\n"?>
<? if(($themes_index = get($conf, 'user', 'sess', 'themes_index')) && get($themes_index, 'hide')): ?><?="Disallow: /"?><? endif; ?>
<?="Disallow: /*?*"?> 
<?="Disallow: /*&p=*"?> 
<?="Disallow: /*?p=*"?> 
<?="Disallow: /*p:*"?> 
<? if($r == "Yandex"): ?>
<? if(get($themes_index, 'index_id') && ($index = rb("{$conf['db']['prefix']}themes_index", "id", $themes_index['index_id']))): ?>
<?="Host: {$index['name']}\n"?>
<? endif; ?>
<?="Host: {$_SERVER['HTTP_HOST']}\n"?>
<? endif; ?>
<?="Sitemap: http://{$_SERVER['HTTP_HOST']}/sitemap.xml\n"?> 
<? endforeach; ?>
