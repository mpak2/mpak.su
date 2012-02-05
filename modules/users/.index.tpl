<? if(array_key_exists('id', $_GET)): ?>
	<table style="width:100%;">
		<tr>
			<td valign=top width=50%>
				<!-- [blocks:5] -->
			</td>
			<td valign=top>
				<!-- [blocks:6] -->
			</td>
		</tr>
	</table>
	<div><!-- [settings:comments] --></div>
<? else: ?>
	<? foreach($conf['tpl']['users'] as $k=>$v): ?>
	<a href="/<?=$arg['modpath']?>/<?=$v['id']?>" style="float:left; margin:3px; padding:3px; background-color:#eee; border: 1px solid gray;">
		<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:100/h:100/c:1/null/img.jpg">
	</a>
	<? endforeach; ?>
	<div style="clear:both;">
		<?=$conf['tpl']['mpager']?>
	</div>
<? endif; ?>