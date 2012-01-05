<h1>Личные сообщения</h1>
<? if ($_GET['id']): ?>
	<? $mess = $conf['tpl']['mess']['0']; $next = $conf['tpl']['mess']['1']; ?>
	<div style="margin: 10px;">
	<a href="/<?=$arg['modpath']?>">Входящие</a> ||
	<a href="/<?=$arg['modpath']?>:send">Создать</a> ||
	<? if($mess['uid']): ?>
		<a href="/<?=$arg['modpath']?>:send/<?=$mess['id']?>">Ответить</a> ||
	<? else: ?>
		Ответить ||
	<? endif; ?>
	<a href="/<?=$arg['modpath']?>/del:<?=$mess['id']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>">Удалить</a> ||
	<? if($next): ?>
		<a href="/<?=$arg['modpath']?>/<?=$next['id']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>">Следующее</a> ||
	<? else: ?>
		Следующее
	<? endif; ?>
	</div> 
	<table width=100% border=1 cellspacing=0 cellpadding=7px>
		<tr>
			<td width=100px><?=(empty($mess['name']) ? $_SERVER['HTTP_HOST'] : $mess['name'])?></td>
			<td width=10px><?=strtr(date('Y.m.d H:i:s', $mess['time']), array(' '=>'&nbsp;'))?></td>
			<td><?=$mess['title']?></td>
		</tr>
		<tr height=300px>
			<td colspan=4 valign="top">
				
				<?=nl2br($mess['text'])?>
			</td>
		</tr>
	</table>
<? else: ?>
	<div style="margin: 10px;">
	<a href="/<?=$arg['modpath']?>:send">Создать</a>
	<a href="/<?=$arg['modpath']?>/read:1">Открыть все</a>
	</div> 
	<div style="margin-top: 10px;"><?=$conf['tpl']['mpager']?></div>
	<div class='messages'>
	<style>
		#mess > tr > td:nth-child(1) {white-space:nowrap;}
	</style>
		<table id="mess" border=1>
			<? foreach($conf['tpl']['mess'] as $k=>$v): ?>
			<tr valign=bottom>
				<td><?=date('Y.m.d', $v['time'])?></td>
				<td>
					<a href=/messages/del:<?=$v['id']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>>
						<img src=/img/del.png border=0>
					</a>
					<img src=/img/mail<?=$v['open']?>.png>
				</td>
				<td>
					<table style="width:100%;">
						<tr>
							<td style="text-align:right; width:45%;"><?=(empty($v['name']) ? $_SERVER['HTTP_HOST'] : $v['name'])?></td>
							<td style="width:5px;">></td>
							<td style="text-align:left; width:45%;"><?=$v['adname']?></td>
						</tr>
					</table>
				</td>
				<td>
					<a href=/messages/<?=$v['id']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>>
						<?=($v['title'] ?: 'Без заголовка')?>
					</a>
				</td>
				<? endforeach; ?>
			</tr>
		</table>
	</div>
	<div style="margin-top: 10px;"><?=$conf['tpl']['mpager']?></div>
<? endif; ?>
