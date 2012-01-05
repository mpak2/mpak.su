<?="<?xml version=\"1.0\"?>\n"?>
<rss version="2.0">
  <channel>
    <title><?=$conf['settings']['title']?></title>
    <link>http://<?=$_SERVER['HTTP_HOST']?>/</link>
    <description><?=$conf['settings']['description']?></description>
    <language>ru-ru</language>
    <pubDate><?=date('r')?></pubDate>
 
    <lastBuildDate>Tue, 10 Jun 2003 09:41:01 GMT</lastBuildDate>
    <docs>http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?>:<?=$arg['fn']?>/null/rss.xml</docs>
    <generator>Weblog Editor 2.0</generator>
    <managingEditor><?=$conf['settings']['news_email']?></managingEditor>
    <webMaster><?=$conf['settings']['news_email']?></webMaster>
 
	<? foreach($conf['tpl']['xml'] as $k=>$v): ?>
	<item>
      <title><?=$v['tema']?></title>
      <link>http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?>/<?=$v['id']?></link>
      <description><?=htmlspecialchars($v['text'])?></description>
      <pubDate><?=date('r', $v['time'])?></pubDate>
      <guid>http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?>/kid:<?=$v['kid']?></guid>
    </item>
	<? endforeach; ?>
 
  </channel>
</rss>