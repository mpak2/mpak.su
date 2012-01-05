<style>
	tr.selected {background-color:#eee;}
</style>
<script language="javascript">
	$(document).ready(function(){
		$('tr').not('.th').hover(function(){
			$(this).css('background-color', '#eee');
		},function(){
			$(this).css('background-color', '');
		});
		$('tr').not('.th').click(function(){
			document.location.href = $(this).find('td a').attr('href');
		});
	});
</script>

<? if($_GET['id']): ?>
	<h1 title="<?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}"] ?: $fn)?>: <?=$conf['tpl'][$arg['fn']]['name']?>" style="margin:10px 0;">
		<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>" style="font-size:100%;">
			<?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}"] ?: $fn)?>:
			<?=$conf['tpl'][$arg['fn']]['name']?>
		</a>
	</h1>
	<div style="overflow:hidden;">
		<? if($conf['tpl'][$arg['fn']]['img']): ?>
			<!-- [settings:foto_lightbox] -->
			<div style="float:right;" id="gallery">
				<a href="/<?=$arg['modpath']?>:img/<?=$conf['tpl'][$arg['fn']]['id']?>/tn:<?=$arg['fn']?>/w:600/h:400/null/img.jpg">
					<img alt="<?=$conf['tpl'][$arg['fn']]['name']?>" src="/<?=$arg['modpath']?>:img/<?=$conf['tpl'][$arg['fn']]['id']?>/tn:<?=$arg['fn']?>/w:120/h:100/null/img.jpg">
				</a>
			</div>
		<? endif; ?>
		<ul style="width:80%; float:left;">
		<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): if(array_search($k, array(1=>'href', 'img'))) continue; ?>
			<li style="clear:both;">
				<div style="font-weight:bold; float:left; width:200px;" title="<?=$fn?>"><?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}_{$k}"] ?: $fn)?> :</div>
				<div><?=$v?></div>
			</li>
		<? endforeach; ?>
		</ul>

		<? foreach((array)$conf['tpl']['fk'] as $n=>$m): ?>
			<? if(!empty($m)): ?>
				<h2 style="clear:both;"><?=($conf['settings'][$fn = "{$arg['modpath']}_$n"] ?: $fn)?> (<?=$conf['tpl']['cnt'][ $n ]?>)</h2>
				<table class="bg">
					<tr class="th">
						<? foreach($m[0] as $k=>$v):  if(array_search($k, array(1=>'href', 'img'))) continue; ?>
							<th style="padding:0 5px;"><span title="<?=($f = "{$arg['modpath']}_{$n}_{$k}")?>"><?=($conf['settings'][$f] ?: $f)?></span></th>
						<? endforeach; ?>
					</tr>
					<? foreach($m as $k=>$v): ?>
						<tr>
						<? foreach($v as $c=>$h): if(array_search($c, array(1=>'href', 'img'))) continue; ?>
							<td style="padding:0 5px;">
								<?=($conf['tpl'][$c] ? $conf['tpl'][$c][$h] : $h)?>
							</td>
						<? endforeach; ?>
						</tr>
					<? endforeach; ?>
				</table>
				<div style="margin:10px;"><?=$conf['tpl']['mpager'][ $n ]?></div>
			<? endif; ?>
		<? endforeach; ?>

	</div>
<? else: ?>
	<h1 style="margin:10px;" title="<?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}"] ?: $fn)?>">
		<?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}"] ?: $fn)?> (<?=$conf['tpl']['cnt'][$arg['fn']]?>)
	</h1>
	<table class="bg">
		<tr class="th">
			<? foreach($conf['tpl']['th'] as $k=>$v): if(array_search($k, array(1=>'href', 'img'))) continue; ?>
				<th style="padding:0 3px;"><span title="<?=("{$arg['modpath']}_{$arg['fn']}_{$k}")?>"><?=$v?></span></th>
			<? endforeach; ?>
		</tr>
		<? foreach($conf['tpl'][$arg['fn']] as $line): ?>
			<tr>
				<? foreach($line as $k=>$v): if(array_search($k, array(1=>'href', 'img'))) continue; ?>
					<td style="padding:0 5px;"><?=$v?></td>
				<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</table>
	<div style="margin:10px;"><?=$conf['tpl']['mpager']?></div>
<? endif; ?>