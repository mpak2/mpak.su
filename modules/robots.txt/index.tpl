Host: <?=strtr($conf['settings']['http_host'], array('www.'=>''))?>


User-agent: *
Allow: /
Disallow: /search/
<? foreach($conf['tpl']['modules'] as $k=>$v): ?>
<? if($v['access']): ?>
Allow: /<?=$v['folder']?>/
<? else: ?>
Disallow: /<?=$v['folder']?>/
<? endif; ?>
<? endforeach; ?>
