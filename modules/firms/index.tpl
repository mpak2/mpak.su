<? if($_GET['id']): ?>
	<style>
		.tdback {background-color:#fefefe; padding:5px;}
	</style>
	<table style="background-color:#aaa;" cellspacing=1>
		<? foreach($conf['tpl'][$arg['fn']] as $n=>$m): ?>
			<? foreach($m as $k=>$v): if(array_search($k, array('img', 'time')) !== false) continue; ?>
				<tr>
					<td class="tdback"><?=($conf['settings'][ $f = "{$arg['modpath']}_{$arg['fn']}_$k" ] ?: $f)?></td>
					<td class="tdback">
						<?=($conf['tpl'][$k][ $v[$k] ]['name'] ? "<a href=\"/{$arg['modpath']}:{$arg['fn']}\">{$conf['tpl'][$k][ $v[$k] ]['name']}</a>" : $v)?>&nbsp;
					</td>
				</tr>
			<? endforeach; ?>
		<? endforeach; ?>
	</table>
	<div style="margin-top:10px;"><?=$conf['settings']['comments']?></div>
<? else: ?>
	<div style="float:right; width:60%;">
		<div style="margin:10px;"><?=$conf['tpl']['mpager']?></div>
		<div>
			<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
				<div class="krug" style="clear:both;">
					<div style="margin:5px 0;">
						<span style="float:right;">
							<a href="/users/<?=$v['uid']?>">
								<?=($v['uid'] > 0 ? $conf['tpl']['users'][ $v['uid'] ]['name'] : $conf['settings']['default_usr'])?>
							</a>
						</span>
						<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$v['id']?>"><?=$v['name']?></a>
					</div>
					<div style="margin-left:10px;">
						<? if($v['img']): ?>
							<div style="float:left; padding:3px 10px 3px 0;">
								<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:50/h:50/c:1/null/<?=$v['name']?>.jpg">
							</div>
						<? endif; ?>
						<span style="float:right; margin:0 0 5px 5px; font-style:italic;"><?=$v['sity']?></span>
						<?=mb_substr($v['description'], 0, 160, 'utf-8')?>
					</div>
				</div>
			<? endforeach; ?>
		</div>
		<div style="margin:10px;"><?=$conf['tpl']['mpager']?></div>
	</div>
	<div>
		<? foreach($conf['tpl']['prof'] as $k=>$v): ?>
			<div>
				<a href="/<?=$arg['modpath']?>/prof_id:<?=$v['id']?>" style="<?=($_GET['prof_id'] == $v['id'] ? "font-weight:bold;" : "")?>">
					<?=$v['name']?>
				</a> [<?=$v['cnt']?>]
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>
