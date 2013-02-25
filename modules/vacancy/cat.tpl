<script src="/include/jquery/treeview/jquery.treeview.js" type="text/javascript"></script>
<script src="/include/jquery/treeview/jquery.cookie.js" type="text/javascript"></script>
<link rel="stylesheet" href="/include/jquery/treeview/jquery.treeview.css" />

<script type="text/javascript">
	$(function(){
		$("#black, #gray").treeview({
			collapsed: true,
			animated: "fast",
			persist: "cookie",
			cookieId: "treeview-black"
		});
	});
</script>
<div style="text-align:center; margin:10px;">Добавить свою вакансию можно в Личном кабинете в разделе <a href="/users:other/0">Продвижение</a></div>
<div style="position:relative;">
	<div style="position:relative; float:right; width:50%;">
		<? foreach($conf['tpl']['vacancy'] as $k=>$v): ?>
			<div style="border-bottom:1px dashed gray; margin:10px 0; padding-bottom:10px;">
				<div style="margin:10px 0; overflow:hidden;">
					<div>
						<span style="float:right;">разместил: <a href="/users/<?=$v['uid']?>"><?=($v['uid'] > 0 ? $v['uname'] : $conf['settings']['default_usr'])?></a></span>
						<span>
							<?=date('d.m.Y H:i:s', $v['time'])?>
						</span>
					</div>
					<div>
						<span style="float:right;"><?=$v['mtel']?> <?=$v['sity']?></span>
						<span style="font-weight:bold;"><a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=str_replace('%', '%25', $v['name'])?>"><?=$v['name']?></a></span>
					</div>
				</div>
				<div>
					<?=nl2br(mb_substr($v['description'], 0, 100, "utf-8"))?>
					<div style="float:right;"><a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=str_replace('%', '%25', $v['name'])?>">Подробнее</a></div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<ul class="treeview-gray treeview" id="gray" style="width:50%;">
		<? $tree = function($tr, $tree) use($conf, $arg) { if(empty($tr[''])) return; ?>
			<li <?=($tr['']['cat_id'] >= 0 ? '' : ' class="open"')?>>
				<? if(count($tr) > 1): ?>
					<div class="hitarea closed-hitarea collapsable-hitarea"></div>
					<span><?=$tr['']['name']?></span>
					<? if($cnt = $conf['tpl']['cnt'][ (int)$tr['']['id'] ]['cnt']): ?>
					[<?=$cnt?>]
					<? endif; ?>
					<ul>
					<? foreach($tr as $k=>$v): ?>
						<?=$tree($v, $tree)?>
					<? endforeach; ?>
					</ul>
				<? else: ?>
					<a href="/<?=$arg['modname']?>/cat_id:<?=$tr['']['id']?>"><?=$tr['']['name']?></a>
					<? if($cnt = $conf['tpl']['cnt'][ $tr['']['id'] ]['cnt']): ?>
					[<?=$cnt?>]
					<? endif; ?>
				<? endif; ?>
			</li>
		<? }; $tree($conf['tpl']['tree'], $tree); ?>
	</ul>
</div>
