<!-- <a href="http://<?=$conf['tpl']['http_host']?>/<?=$arg['modpath']?>?search_block_num=<?=$_REQUEST['search_block_num']?>&search=<?=htmlspecialchars($_REQUEST['search'])?>">http://<?=$conf['tpl']['http_host']?>/<?=$arg['modpath']?>?search_block_num=<?=$_REQUEST['search_block_num']?>&search=<?=htmlspecialchars($_REQUEST['search'])?></a> -->
<div style="margin:10px;">
	<? if($result = get($conf, 'tpl', 'result')): ?>
		<? foreach($result as $k=>$v): ?>
			<div style="margin: 15px 3px 3px 3px;">
				<div style="font-style:italic;">
					<?=strip_tags($v['text'])?>
				</div>
				<div>
					<div style="float:right;">(<?=$v['name']?>)</div>
					<a href="<?=$v['link']?>" style="color:#093;"><?=$v['link']?></a>
				</div>
			</div>
		<? endforeach; ?>
	<? else: ?>
		<div style="margin:50px; text-align:center;">Ничего не найдено</div>
	<? endif; ?>
	<div style="margin:10px;">
		<?=get($tpl, 'pager')?>
	</div>
</div>
