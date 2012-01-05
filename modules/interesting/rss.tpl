<?="<?xml version=\"1.0\"?>\n"?>
<rss version="2.0">
  <channel>
    <title><?=iconv('cp1251', 'utf-8', $conf['settings']['title'])?></title>
    <link>http://<?=$_SERVER['HTTP_HOST']?>/</link>
    <description><?=iconv('cp1251', 'utf-8', $conf['settings']['description'])?></description>
    <language>ru-ru</language>
    <pubDate><?=date('r')?></pubDate>
 
    <lastBuildDate>Tue, 10 Jun 2003 09:41:01 GMT</lastBuildDate>
    <docs>http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?>:<?=$arg['fn']?>/null/rss.xml</docs>
    <generator>Weblog Editor 2.0</generator>
    <managingEditor><?=$conf['settings']['news_email']?></managingEditor>
    <webMaster><?=$conf['settings']['news_email']?></webMaster>
 
	<? foreach($conf['tpl']['xml'] as $k=>$v): ?>
	<item>
      <title><?=$v['name']?></title>
      <link>http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?>/<?=$v['id']?></link>
      <description><?=htmlspecialchars($v['description'])?></description>
      <pubDate><?=date('r', $v['time'])?></pubDate>
      <guid>http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?></guid>
    </item>
	<? endforeach; ?>
 
  </channel>
</rss>