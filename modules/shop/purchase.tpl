<table border=1 width=100%>
	<? foreach($conf['tpl']['purchase'] as $k=>$v): ?>
	<tr>
		<td>#<?=$v['id']?></td>
		<td><?=$v['sum']?> <!-- [settings:onpay_currency] --></td>
		<td><?=$v['close']?></td>
		<td><?=$v['fm']?></td>
		<td><?=$v['im']?></td>
		<td><?=$v['om']?></td>
		<td><?=$v['sity']?></td>
		<td><?=$v['addr']?></td>
		<td><?=$v['mtel']?></td>
		<td><?=$v['rtel']?></td>
		<td><?=$v['email']?></td>
		<td><?=$v['icq']?></td>
		<td><?=$v['description']?></td>
		<td><?=$v['count']?></td>
	</tr>
	<? endforeach; ?>
</table>