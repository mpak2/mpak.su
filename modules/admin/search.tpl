<!-- <center>Результат поиска по запросу: 
<a href="http://<?=$conf['tpl']['http_host']?>/<?=$arg['modpath']?>?search_block_num=<?=$_REQUEST['search_block_num']?>&search=<?=htmlspecialchars($_REQUEST['search'])?>">http://<?=$conf['tpl']['http_host']?>/<?=$arg['modpath']?>?search_block_num=<?=$_REQUEST['search_block_num']?>&search=<?=htmlspecialchars($_REQUEST['search'])?></a>
</center><p> -->
<div style="margin:10px;">
	<div style="margin:10px;">
		<?=$conf['tpl']['mpager']?>
	</div>
	<? foreach((array)$conf['tpl']['result'] as $k=>$v): ?>
		<div style="margin: 15px 3px 3px 3px;">
			<div style="float:right;">(<?=$v['name']?>)</div>
			<a href="http://<?=$v['link']?>">http://<?=$v['link']?></a>
		</div>
		<div style="font-style:italic;"><?=strip_tags($v['text'])?></div>
	<? endforeach; ?>

	<div style="margin:10px;">
		<? mpager($conf['tpl']['cnt']); ?>
	</div>
</div>