<? if($_GET['id']): ?>
	<? if($index = $tpl['index'][ $_GET['id'] ]): ?>
		<div><?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[id]=". (int)$index['id'])?></div>
		<h1><?=$index['name']?></h1>
		<div><?=$index['text']?></div>
		<div style="margin-top:20px;"><?=$conf['settings']['comments']?></div>
	<? else: ?>
		<div style="margin-top:150px; text-align:center;">
			Данная страница не найдена на сайте.<br />Возможно она была удалена.
		</div>
	<? endif; ?>
<? else: ?>
	<ul>
		<? foreach($tpl['pages'] as $cat_id=>$cats): ?>
			<li>
				<b><?=$tpl['cat'][ $cat_id ]['name']?></b> [<?=$tpl['cat'][ $cat_id ]['cnt']?>]
				<ul>
					<? foreach($cats as $v): ?>
						<li><a href="/<?=$arg['modname']?>/<?=$v['id']?>"><?=$v['name']?></a></li>
					<? endforeach; ?>
				</ul>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>