<? if($_GET['set']): ?>
	<? if($_POST): ?>
		<meta http-equiv="refresh" content="3;url=http://shop.mpak.su/users/0">
		<div style="text-align:center; margin:100px;">Заказ принят <a href="/">На главную</a></div>
	<? else: ?>
		<style>
			.ar {
				text-align:right;
				width:30%;
			}
			.fw {
				width:70%;
			}
			
			.fw {
				border:1px solid black;
			}
			
			#step2_a {
				background-position: -2px -57px;
			    height: 48px;
			    width: 48px;
			}
			
			.cart_ico {
				background: url("/themes/null/img/cart_ico.gif") no-repeat scroll 0 0 transparent;
			    display: block;
			    float: left;
			}
			
			.redHeader2 {
				display: block;
			    float: left;
			    padding: 17px 0 0 10px;
			    color: #DD0000;
			    font: bold 11px Tahoma,Arial,Helvetica,sans-serif;
			}
			
			#arr {
				background-position: 0 -270px;
			    height: 20px;
			    margin: 0 10px;
			    width: 25px;
			}
			
			#step3 {
				background-position: -62px -110px;
			    height: 48px;
			    width: 48px;
			}
			
			#step4 {
				background-position: -60px -166px;
				height: 48px;
				width: 51px;
			}
			
			#step5 {
				background-position: -60px -222px;
				height: 48px;
				width: 48px;
			}
			
			.rb {
				width: 450px;
				margin: 40px auto 0pt;
			}
			
			.rb .t_rb div, .rb .b_rb div, .rb .tt_rb .l_rb, .rb .tt_rb .r_rb {
				background: url("/themes/null/img/rb_corners.gif") no-repeat scroll 0 0 transparent;
				margin: 0;
				padding: 0;
				position: absolute;
			}
			
			.rb .content_rb {
				border: 1px solid #CACACA;
				padding: 20px 10px 10px;
				text-align: center;
			}
			
			.rb .t_rb .l_rb {
				background-position: -42px 0;
				height: 22px;
				left: 0;
				top: 0;
				width: 21px;
			}
			
			.rb .t_rb .r_rb {
				background-position: -63px 0;
				height: 22px;
				right: 0;
				top: 0;
				width: 21px;
			}
			
			.rb .t_rb div#rb_title, .rb .t_rb div.rb_title {
				background: none repeat scroll 0 0 transparent;
				top: -12px;
				width: 100%;
			}
			
			.rb .tt_rb, .rb .tt_rb h1, .rb .tt_rb h2, .rb #h_rb_s.tt_rb {
				color: #ED1C29;
				font: bold 11px Tahoma;
				margin: 0;
				padding: 0;
			}
			
			.rb .tt_rb .l_rb {
				background-position: 0 -22px;
				height: 24px;
				position: relative;
				width: 13px;
			}
			
			.rb #h_rb {
				background: url("/themes/null/img/bgs.gif") repeat-x scroll 0 -21px transparent;
				padding: 0 10px 3px;
				position: relative;
			}
			
			.rb .tt_rb .r_rb {
				background-position: -13px -22px;
				height: 24px;
				position: relative;
				width: 12px;
			}
			
			.rb .b_rb .l_rb {
				background-position: 0 0;
				bottom: 0;
				height: 22px;
				left: 0;
				width: 21px;
			}
			
			.rb .b_rb .r_rb {
				background-position: -21px 0;
				bottom: 0;
				height: 22px;
				right: 0;
				width: 21px;
			}
			
			.rb .b_rb {
				position: relative;
			}
			
			.rb .t_rb {
				position: relative;
			}
			
		</style>
		<table width="60%" cellspacing="0" cellpadding="0" border="0" class="cart_bcrumbs">
		<tbody><tr>
      
	   <td>
   		<b id="step2_a" class="cart_ico"></b>
	   	<h1 class="redHeader2">Информация о Вас</h1>
	   	   </td>
	   <td><b id="arr" class="cart_ico"></b></td>
	   
	      <td>
	   	<b id="step3" class="cart_ico"></b>
	   	   </td>
	   <td><b id="arr" class="cart_ico"></b></td>
	      
	   <td>
	   	<b id="step4" class="cart_ico"></b>
	   	   </td>
	   <td><b id="arr" class="cart_ico"></b></td>
	   
	   <td>
	   	<b id="step5" class="cart_ico"></b>
	   	   </td>
		</tr>
		</tbody></table>
		
		
		<div class="rb">
   <div align="center" class="t_rb">
      <div class="l_rb"></div><div class="r_rb"></div>
      <div align="center" id="rb_title">
         <table cellspacing="0" cellpadding="0" border="0" class="tt_rb"><tbody><tr><td class="l_rb"></td><td id="h_rb">Информация о вас</td><td class="r_rb"></td>
            </tr>
         </tbody></table>
      </div>
   </div>
   <div style="padding: 15px 0pt;" class="content_rb">
		<div align="center" style="width: 100%;">
			<div style="padding: 7px 0pt;" class="grey">
				<form method="post">
			<input type="hidden" name="id" value="<?=$conf['tpl']['basket']['id']?>">
			<div>
				<table class="zakaz" width=90% border=0 cellpadding=3px;>
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
						<td><input type="submit" name="submit" value="Сделать заказ" style="border:0 solid black; color:white; font-weight:bold; padding: 0 0 4px; cursor:pointer; width:141px; height:20px; background:url(/themes/null/img/buttontrash.gif) no-repeat;"></td>
					</tr>
				</table>
			</div>
		</form>
			</div>		
		</div>
   </div>
   <div class="b_rb"><div class="l_rb"></div><div class="r_rb"></div></div>
</div>

	<? endif; ?>
<? else: ?>
	<!-- [settings:foto_lightbox] -->
	<? if(empty($conf['tpl']['order'])): ?>
		<div style="text-align:center; margin:100px;">
			Ваша корзина пуста <a href="/<?=$arg['modpath']?>">Список категорий</a>
		</div>
	<? else: ?>
		<style>
			.itemshop {
				border:1px solid #C9C9C9;
			}
			.itemshop tr th {
				padding: 3px 10px 2px;
				font-size:12px;
				font-weight:bold;
				color:#384666;
				background:url(/themes/null/img/bgshop.png) repeat-x;
			}
			.itemshop td {
				padding:5px 10px;
			}
		</style>
		<table class='itemshop' width=100% border=0 cellspacing=0 cellpadding=0 rules="all">
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
						<a  href="/<?=$arg['modpath']?>:img/<?=$v['desc_id']?>/tn:1/w:600/h:500/null/img.jpg">
							<img src="/<?=$arg['modpath']?>:img/<?=$v['desc_id']?>/tn:1/w:100/h:100/null/img.jpg"></td>
						</a>
					</div>
				<td>
					<?=$v['name']?>
					<div style="margin-left:15px;"><?=$v['description']?></div>
				</td>
				<td><?=$v['price']?>&nbsp;<!-- [settings:onpay_currency] --></td>
				<td>
					<div style="position:relative;">
						<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/did:<?=$v['desc_id']?>" style="float:right;">
							<img src="/img/del.png">
						</a>
					</div>
					<div style="clear:both;">
						<div style="text-align:center;margin:3px;">
							<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/did:<?=$v['desc_id']?>/count:1">
								<img src="img/up.png">
							</a>
						</div>
						<div style="text-align:center;"><input type="text" value="<?=$v['count']?>" style="width:50px;"<?=($conf['tpl']['basket'] ? " disabled" : '')?>></div>
						<div style="text-align:center;margin:3px;">
							<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/did:<?=$v['desc_id']?>/count:-1">
								<img src="img/down.png">
							</a>
						</div>
					</div>
				</td>
				<td><?=($v['count'] * $v['price'])?>&nbsp;<!-- [settings:onpay_currency] --></td>
			</tr>
			<? endforeach; ?>
		</table>
		<div style="margin:10px; text-align:right; font-size:24px;">
			Итого: <?=$itog?>&nbsp;<!-- [settings:onpay_currency] --> <input id="zakaz" type="button" style="border:0 solid black; color:white; font-weight:bold; padding: 0 0 4px; cursor:pointer; width:141px; height:20px; background:url(/themes/null/img/buttontrash.gif) no-repeat;" onClick="javascript: location.href='/<?=$arg['modpath']?>:order/set:1';">
		</div>
	<? endif; ?>
<? endif; ?>
