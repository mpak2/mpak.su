<table style="width:100%;">
	<tr>
		<td valign=top width=50%>
			<? if($conf['settings'][$f = "{$arg['modpath']}_{$arg['fn']}_left"]): ?>
				<!-- [blocks:<?=$conf['settings']["{$arg['modpath']}_{$arg['fn']}_left"]?>] -->
			<? else: ?>
				<?=$f?>
			<? endif; ?>
		</td>
		<td valign=top>
			<? if($conf['settings'][$f = "{$arg['modpath']}_{$arg['fn']}_right"]): ?>
				<!-- [blocks:<?=$conf['settings'][$f]?>] -->
			<? else: ?>
				<?=$f?>
			<? endif; ?>
		</td>
	</tr>
</table>
