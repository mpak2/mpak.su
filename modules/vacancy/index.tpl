<div style="overflow:hidden;">
	<div style="float:right;"><a href="/<?=$arg['modpath']?>:cat">Весь каталог</a></div>
	<h1 style="height:20px;"><?=$conf['tpl']['cat']['name']?></h1>
</div>
<div>
	<? foreach($conf['tpl']['vacancy'] as $k=>$v): ?>
		<div style="border-bottom:1px dashed gray; margin:10px 0; padding-bottom:10px;">
			<div style="margin:10px 0; overflow:hidden;">
				<div>
					<span style="float:right;">разместил: <a href="/users/<?=$v['uid']?>"><?=($v['uid'] > 0 ? $v['uname'] : $conf['settings']['default_usr'])?></a></span>
					<span>
						<?=date('Y.m.d H:i:s', $v['time'])?>
					</span>
				</div>
				<div>
					<span style="float:right;"><?=$v['mtel']?></span>
					<span style="font-weight:bold;"><a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?></a></span>
				</div>
			</div>
			<div>
				<?=nl2br($_GET['id'] ? $v['description'] : substr($v['description'], 0, 100))?>
			</div>
		</div>
	<? endforeach; ?>
	<? if($_GET['id.id']): ?>
		<div><?=$conf['settings']['comments']?></div>
	<? endif; ?>
</div>