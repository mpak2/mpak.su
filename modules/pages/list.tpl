<h1><?=$conf['tpl']['cat']['name']?></h1>

<div>
	<? foreach($conf['tpl']['index'] as $n=>$cat): ?>
		<h3><?=$conf['tpl']['cat'][ $n ]['name']?></h3>
		<ul style="padding-left:20px;">
			<? foreach($cat as $k=>$v): ?>
				<li style="border:0;">
					<? if($arg['access'] > 3 || $v['uid'] == $conf['user']['uid']): ?>
						<span style="float:right;">
							<a href="/<?=$arg['modname']?>:правка/<?=$v['id']?>"><img src="/img/del.png"></a>
							<a href="/<?=$arg['modname']?>:правка/del:<?=$v['id']?>">Удалить</a>
						</span>
					<? endif; ?>
					<a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=mpue($v['name'])?>"><?=$v['name']?></a>
				</li>
			<? endforeach; ?>
		</ul>
	<? endforeach; ?>
</div>