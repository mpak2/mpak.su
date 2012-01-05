<style>
	.ta {
		text-align:right;
		width:30%;
		padding-right:15px;
	}
</style>
<? $close = array('0'=>'Формируется', '1'=>'Оформлен', '2'=>'Оплачен'); ?>
<? if($_GET['id']): ?>
	<h1>Счет #<?=$conf['tpl']['basket']['id']?></h1>
	<div><a href="/<?=$arg['modpath']?>:basket">Список заказов</a></div>
	<table cellpadding=3px width=100% border=0>
		<tr>
			<td class="ta">Сумма заказа:</td>
			<td><?=$conf['tpl']['basket']['sum']?> <!-- [settings:onpay_currency] --></td>
		</tr>
		<tr>
			<td class="ta">Статус:</td>
			<td><?=$close[$conf['tpl']['basket']['close']]?></td>
		</tr>
		<tr>
			<td class="ta">Фамилия:</td>
			<td><?=$conf['tpl']['basket']['fm']?></td>
		</tr>
		<tr>
			<td class="ta">Имя:</td>
			<td><?=$conf['tpl']['basket']['im']?></td>
		</tr>
		<tr>
			<td class="ta">Отчество:</td>
			<td><?=$conf['tpl']['basket']['ot']?></td>
		</tr>
		<tr>
			<td class="ta">Отчество:</td>
			<td><?=$conf['tpl']['basket']['sity']?></td>
		</tr>
		<tr>
			<td class="ta">Адрес:</td>
			<td><?=$conf['tpl']['basket']['addr']?></td>
		</tr>
		<tr>
			<td class="ta">Мобильный телефон:</td>
			<td><?=$conf['tpl']['basket']['mtel']?></td>
		</tr>
		<tr>
			<td class="ta">Рабочий телефон:</td>
			<td><?=$conf['tpl']['basket']['rtel']?></td>
		</tr>
		<tr>
			<td class="ta">Рабочий телефон:</td>
			<td><?=$conf['tpl']['basket']['email']?></td>
		</tr>
		<tr>
			<td class="ta">Адрес ICQ клиента:</td>
			<td><?=$conf['tpl']['basket']['icq']?></td>
		</tr>
		<tr>
			<td class="ta">Дополнительные данные:</td>
			<td><?=$conf['tpl']['basket']['description']?></td>
		</tr>
	</table>
<? else: ?>
	<h1>Ваш баланс: <?=$conf['tpl']['sum']?> <!-- [settings:onpay_currency] --></h1>
	<h2><a href=/onpay>Управление балансом</a></h2>
	<div style="margin:5px;"><? echo mpager($conf['tpl']['pcount']); ?></div>
	<table border=1 cellspacing=0 cellpadding=3 width=100%>
	<? foreach($conf['tpl']['basket'] as $k=>$v): ?>
		<tr>
			<td># <?=$v['id']?></td>
			<td><?=$v['fm']?></td>
			<td><?=$v['im']?></td>
			<td><?=$v['ot']?></td>
			<td><?=$v['sity']?></td>
			<td><?=$v['addr']?></td>
			<td><?=$v['sum']?> <!-- [settings:<?=$arg['modpath']?>_currency] --></td>
			<td><a href="/<?=$arg['modpath']?>:order/<?=$v['id']?>">Список</a></td>
			<td>
				<!-- <a href="/<?=$arg['modpath']?>:basket/<?=$v['id']?>"> -->
					<span title="<?=date('Y.m.d H:i:s', $v['time'])?>" alt="<?=date('Y.m.d H:i:s', $v['time'])?>">
						<?=$close[ $v['close'] ]?>
					</span>
				<!-- </a> -->
			</td>
			<td>
				<? if($v['close'] == 0): ?>
				<? elseif($v['close'] == 1): ?>
					<? if($conf['tpl']['sum'] >= $v['sum']): ?>
						<a href="/services:basket/set:<?=$v['id']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>">
							Оплатить
						</a>
					<? else: ?>
							Мало средств
					<? endif; ?>
				<? endif; ?>
			</td>
		</tr>
	<? endforeach; ?>
	</table>
<? endif; ?>