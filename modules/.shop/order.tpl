<? if($_GET['set']): ?>
	<? if($_POST): ?>
		<div style="text-align:center; margin:100px;">Заказ принят <a href=/<?=$arg['modpath']?>:basket>Список заказов</a></div>
	<? else: ?>
		<style>
			.ar {
				text-align:right;
				width:30%;
			}
			.fw {
				width:70%;
			}
		</style>
		<form method="post">
			<input type="hidden" name="id" value="<?=$conf['tpl']['basket']['id']?>">
			<div>
				<table width=90% border=0 cellpadding=3px;>
					<tr>
						<td class="ar">Фамилия</td>
						<td><input class="fw" type="text" name="fm" value="<?=$conf['user']['fm']?>"></td>
					</tr>
					<tr>
						<td class="ar">Имя</td>
						<td><input class="fw" type="text" name="im" value="<?=$conf['user']['im']?>"></td>
					</tr>
					<tr>
						<td class="ar">Отчество</td>
						<td><input class="fw" type="text" name="ot" value="<?=$conf['user']['ot']?>"></td>
					</tr>
					<tr>
						<td class="ar">Город</td>
						<td><input class="fw" type="text" name="sity" value="<?=$conf['user']['sity']?>"></td>
					</tr>
					<tr>
						<td class="ar">Адрес</td>
						<td><input class="fw" type="text" name="addr" value="<?=$conf['user']['addr']?>"></td>
					</tr>
					<tr>
						<td class="ar">Телефон</td>
						<td><input class="fw" type="text" name="tel" value="<?=$conf['user']['mtel']?>"></td>
					</tr>
					<tr>
						<td class="ar">Рабочий телефон</td>
						<td><input class="fw" type="text" name="rtel" value="<?=$conf['user']['rtel']?>"></td>
					</tr>
					<tr>
						<td class="ar">Почта</td>
						<td><input class="fw" type="text" name="mail" value="<?=$conf['user']['email']?>"></td>
					</tr>
					<tr>
						<td class="ar">ICQ</td>
						<td><input class="fw" type="text" name="icq" value="<?=$conf['user']['icq']?>"></td>
					</tr>
					<tr valign="top">
						<td class="ar">Описание</td>
						<td><textarea class="fw" name="description" title="Дополнительные сведения о заказе"></textarea></td>
					</tr>
					<tr valign="top">
						<td class="ar"></td>
						<td><input type="submit" name="submit" value="Оформить заказ"></td>
					</tr>
				</table>
			</div>
		</form>
	<? endif; ?>
<? else: ?>
	<!-- [settings:foto_lightbox] -->
	<? if(empty($conf['tpl']['order'])): ?>
		<div style="text-align:center; margin:100px;">
			Ваша корзина пуста <a href="/<?=$arg['modpath']?>">Список категорий</a>
		</div>
	<? else: ?>
		<table width=100% border=1 cellspacing=0 cellpadding=10>
			<tr>
				<th>Фото</th>
				<th>Наименование</th>
				<th>Цена</th>
				<th>Количество</th>
				<th>Сумма</th>
			</tr>
			<? foreach($conf['tpl']['order'] as $k=>$v): ?>
			<? $itog += $v['count']*$v['price']; ?>
			<tr style="vertical-align:top;">
				<td style="width:120px; text-align:right;">
					<div id="gallery">
						<a  href="/<?=$arg['modpath']?>:img/<?=$v['did']?>/tn:1/w:600/h:500/null/img.jpg">
							<img src="/<?=$arg['modpath']?>:img/<?=$v['did']?>/tn:1/w:100/h:100/null/img.jpg"></td>
						</a>
					</div>
				<td>
					<?=$v['name']?>
					<div style="margin-left:15px;"><?=$v['description']?></div>
				</td>
				<td><?=$v['price']?>&nbsp;<!-- [settings:onpay_currency] --></td>
				<td><input type="text" value="<?=$v['count']?>" style="width:50px;"<?=($conf['tpl']['basket'] ? " disabled" : '')?>></td>
				<td><?=($v['count'] * $v['price'])?>&nbsp;<!-- [settings:onpay_currency] --></td>
			</tr>
			<? endforeach; ?>
		</table>
		<div style="margin:10px; text-align:right; font-size:24px;">
			Итого: <?=$itog?>&nbsp;<!-- [settings:onpay_currency] --> <input id="zakaz" type="button" value="Оформить заказ" onClick="javascript: location.href='/<?=$arg['modpath']?>:order/set:1';">
		</div>
	<? endif; ?>
<? endif; ?>