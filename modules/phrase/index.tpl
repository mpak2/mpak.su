<!-- [settings:foto_lightbox] -->
<? if($arg['access'] > 1): ?>
	<style> .fw {width:100%;} .ar {text-align:right; width:20%;}</style>
	<form method="post" style="margin-bottom:25px;" enctype="multipart/form-data">
		<? if($conf['tpl']['form']['edit']['id']): ?><input type="hidden" name="id" value="<?=$conf['tpl']['form']['edit']['id']?>"><? endif; ?>
		<table class="fw">
		<? foreach(mpql(mpqw("DESC {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}")) as $k=>$v): ?>
			<? if(array_search($v['Field'], $conf['tpl']['form']['hide']) !== false) continue; ?>
			<tr>
				<td class="ar"><?=$conf['tpl']['form']['title'][$v['Field']] ? $conf['tpl']['form']['title'][$v['Field']] : $v['Field']?>: </td>
				<td>
					<? if($v['Field'] == 'img'): ?>
						<input type="file" name="<?=$v['Field']?>">
					<? elseif($conf['tpl']['form']['type'][$v['Field']] == 'select'): ?>
						<select name="<?=$v['Field']?>">
							<? foreach($conf['tpl'][$v['Field']] as $n=>$z): ?>
								<option value="<?=$n?>"><?=$z?></option>
							<? endforeach; ?>
						</select>
					<? elseif($v['Type'] == 'text'): ?>
						<textarea class="fw" name="<?=$v['Field']?>"><?=$conf['tpl']['form']['edit'][$v['Field']]?></textarea>
					<? else: ?>
						<input class="fw" type="text" name="<?=$v['Field']?>" value="<?=$conf['tpl']['form']['edit'][$v['Field']]?>">
					<? endif; ?>
				</td>
		<? endforeach; ?>
			<tr><td colspan="2" class="ar"><input type="submit" name="submit" value="Добавить"></td></tr>
		</table>
	</form>
<? endif; ?>
<? if($_GET['id']): ?>
	<div>
			<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
				<div style="overflow:hidden;">
					<div id="gallery" style="text-align:center; float:left; margin:3px 10px; width:130px;">
						<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:600/h:500/null/img.jpg">
							<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:120/h:100/null/img.jpg">
						</a>
					</div>
					<div style="margin:10px; font-weight:bold;"><?=$v['name']?></div>
					<div style="margin:10px;"><?=$v['description']?></div>
				</div>
				<div style="text-align:right;">
					<a href="/<?=$arg['modpath']?><?=($arg['fn'] == 'index' ? '' : ":{$arg['fn']}")?>/p:<?=(int)$_GET['p']?>/<?=$v['id']?>">Комментариев [<?=(int)$conf['tpl']['comments'][$v['id']]?>]</a>
				</div>
			<? endforeach; ?>
	</div>
	<div><!-- [settings:comments] --></div>
<? else: ?>
	<div>
			<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
				<div style="overflow:hidden;">
					<? if($arg['access'] >= 3): ?>
						<div style="float:right;">
							<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/edit:<?=$v['id']?>">
								<img src="/img/edit.png" style="border:0px;">
							</a>
							<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/del:<?=$v['id']?>" onclick="javascript: if (confirm('Вы уверенны?')){return obj.href;}else{return false;}">
								<img src="/img/del.png" style="border:0px;">
							</a>
						</div>
					<? endif; ?>
					<div id="gallery" style="text-align:center; float:left; margin:3px 10px; width:130px;">
						<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:600/h:500/null/img.jpg">
							<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:120/h:100/null/img.jpg">
						</a>
					</div>
					<div style="margin:10px; font-weight:bold;"><?=$v['name']?></div>
					<div style="margin:10px;"><?=$v['description']?></div>
				</div>
				<div style="text-align:right;">
					<a href="/<?=$arg['modpath']?><?=($arg['fn'] == 'index' ? '' : ":{$arg['fn']}")?>/p:<?=(int)$_GET['p']?>/<?=$v['id']?>">Комментариев [<?=(int)$conf['tpl']['comments'][$v['id']]?>]</a>
				</div>
			<? endforeach; ?>
	</div>
	<div><? mpager($conf['tpl']['cnt']); ?></div>
<? endif; ?>