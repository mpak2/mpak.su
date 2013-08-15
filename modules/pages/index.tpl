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
<? elseif($cat = rb($tpl['cat'], "id", $_GET['cat_id'])): ?>
	<div>
		<h1><?=$cat['name']?></h1>
		<ul>
			<? foreach(rb($tpl['index'], "cat_id", "id", $cat['id']) as $index): ?>
				<li>
					<a href="/<?=$arg['modname']?>/<?=$index['id']?>"><?=$index['name']?></a>
				</li>
			<? endforeach; ?>
		</ul>
	</div>
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