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
			
			#step2 {
				background-position: -59px -57px;
			    height: 48px;
			    width: 48px;
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
			
			#step3_a {
				background-position: 0 -110px;
			    height: 48px;
			    width: 54px;
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
				width: 750px;
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
   		<b id="step2" class="cart_ico"></b>
	   	   </td>
	   <td>
	   	<b id="arr" class="cart_ico"></b>
		</td>
	   
	      <td>
	   	<b id="step3_a" class="cart_ico"></b>
	   	<h1 class="redHeader2">Списки заказов</h1>
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
         <table cellspacing="0" cellpadding="0" border="0" class="tt_rb"><tbody><tr><td class="l_rb"></td><td id="h_rb">Списки заказов</td><td class="r_rb"></td>
            </tr>
         </tbody></table>
      </div>
   </div>
   <div style="padding: 15px 0pt;" class="content_rb">
		<div align="center" style="width: 100%;">
			<div style="padding: 7px 5px;" class="grey">
				<h1>Ваш баланс: <?=$conf['tpl']['sum']?> <!-- [settings:onpay_currency] --></h1>
	<h2><a href=/onpay>Управление балансом</a></h2>
	<div style="margin:5px;"><? echo mpager($conf['tpl']['pcount']); ?></div>
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
				<th>№ заказа</th>
				<th>Ваши данные</th>
				<th>Цена</th>
				<th>Cписок</th>
				<th>Cтатус</th>
				<th>Оплатить</th>
			</tr>
	<? foreach($conf['tpl']['basket'] as $k=>$v): ?>
		<tr>
			<td># <?=$v['id']?></td>
			<td><?=$v['fm']?></td>
			<!--td><?=$v['im']?></td>
			<td><?=$v['ot']?></td>
			<td><?=$v['sity']?></td>
			<td><?=$v['addr']?></td-->
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
			</div>		
		</div>
   </div>
   <div class="b_rb"><div class="l_rb"></div><div class="r_rb"></div></div>
</div>
<? endif; ?>
