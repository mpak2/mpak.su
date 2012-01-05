<!-- [settings:foto_lightbox] -->

<? if($_GET['id'] || $_GET['oid'] || $_GET['pid'] || $_GET['sity_id'] || $_GET['uid']): ?>
	<style>
		.button {
			margin:4px;
			width:163px;
			height:28px;
			border: medium none;
			background: url(/themes/null/img/dir_left_input_buy_image.gif) no-repeat scroll left top transparent;
		}
	</style>
	<div>
		<? if($conf['tpl']['obj']): ?>
		<div style="overflow:hidden;">
			<div style="float:right; margin-right:50px;"><img src="/services:img/<?=$conf['tpl']['obj']['id']?>/w:100/h:100/c:1/null/img.jpg"></div>
			<div style="font-size:19.2px;"><a href="/">На главную</a></div> 
			<h1><a href="/<?=$arg['modpath']?>/oid:<?=$conf['tpl']['obj']['id']?>"><?=$conf['tpl']['obj']['name']?></a></h1>
		</div>
		<? elseif($conf['tpl']['producer']): ?>
		<div style="overflow:hidden;">
			<div style="float:right; margin-right:50px;"><img src="/services:img/<?=$conf['tpl']['producer']['id']?>/tn:3/w:100/h:100/c:1/null/img.jpg"></div>
			<div style="font-size:19.2px;"><a href="/">На главную</a></div> 
			<h1><a href="/<?=$arg['modpath']?>:producer/<?=$conf['tpl']['producer']['id']?>"><?=$conf['tpl']['producer']['name']?></a></h1>
		</div>
		<? endif; ?>
		<div><i><?=$conf['tpl']['obj']['description']?></i></div>
	</div>
	<div style="margin-bottom:10px;"><?=$conf['tpl']['pcount']?></div>
	<? foreach($conf['tpl']['desc'] as $k=>$v): ?>
		<div style="overflow:hidden; border-top: dashed 1px blue; padding:10px;">
			<div id="gallery">
				<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:1/w:600/h:500/null/img.jpg">
					<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:1/w:200/h:150/null/img.jpg" style="float:right;">
				</a>
			</div>
			<h2>
				<a href="/<?=$arg['modpath']?>/<?=$v['id']?>" style="font-size:130%;"><?=$v['name']?></a>
				<a href="/users/<?=$v['uid']?>"><?=$v['uname']?></a>
				<?=($v['cnt'] > 1 ? "+{$v['cnt']} фото" : '')?>
			</h2>
			<div>город: <?=$conf['tpl']['sity'][ $v['sity_id'] ]?></div>
			<div>категория: <a href="/<?=$arg['modpath']?>/oid:<?=$v['obj_id']?>"><?=$conf['tpl']['objs'][ $v['obj_id'] ]?></a></div>
			<div>цена: <?=$v['price']?>&nbsp;<!-- [settings:onpay_currency] --></div>
			<div style="margin-top:10px;"><i><?=$v['description']?></i></div>
			<div style="margin-top:10px;"><?=$v['text']?></div>
			<div style="text-align:left;">
			<input type="text" id="count_<?=$v['id']?>" style="width:70px;" value="1">
			<input type="button" id="button" id="<?=$v['id']?>" class="button" onClick="javascript: location.href='/<?=$arg['modpath']?>:order/did:<?=$v['id']?>/count:'+document.getElementById('count_<?=$v['id']?>').value;">
			</div>
		</div>
	<? endforeach; ?>
	<? if($_GET['id']): ?>
		<? foreach($conf['tpl']['img'] as $k=>$v): ?>
			<div id="gallery" style="float:left;">
				<a title="<?=$v['description']?>" alt="<?=$v['description']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:2/w:600/h:500/null/img.jpg">
					<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:2/w:100/h:70/null/img.jpg" style="margin:5px;">
				</a>
			</div>
		<? endforeach; ?>
		<div style="clear:both;"></div>
		<?=$conf['settings']['comments']?>
	<? else: ?>
		<?=$conf['tpl']['pcount']?>
	<? endif; ?>
<? else: ?>
	<style>
		.serva:hover {
			background:#faf8f4;
			color:red;
		}
	</style>
	<div style="margin:10px 0 30px;">
		<a href="/users/0">
			Добавьте свой товар прямо сейчас из личного кабинета
		</a>
	</div>
	<div>
		<? foreach($conf['tpl']['obj'] as $k=>$v): if($v['obj_id']) continue; ?>
			<div style="width:32%; float:left; text-align:center;">
				<div id="gallery" style="text-align:center; margin-top:10px;">
	<!--				<a title="<?=$v['description']?>" alt="<?=$v['description']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:600/h:500/null/img.jpg">
						<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:70/h:50/c:1/null/img.jpg" style="border:1px solid gray;">
					</a>-->
						<div>
							<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:img/w:100/h:100/c:1/null/img.jpg" style="border-radius:3px;">
						</div>
					<h4><?=$v['name']?></h4>
				</div>
				<ul style="text-align:left; list-style:none;">
					<? foreach($conf['tpl']['objs'] as $n=>$z): if($z['obj_id'] != $v['id']) continue; ?>
					<li>
						<a href="/<?=$arg['modpath']?>/oid:<?=$z['id']?>" style="font-weight:normal; text-decoration:none; padding:1px 0 1px 5px; display:block;" class="serva">
							<?=$z['name']?> <?=($conf['tpl']['dcount'][$z['id']] ? "[{$conf['tpl']['dcount'][$z['id']]}]" : '')?>
						</a>
					</li>
					<? endforeach; ?>
				</ul>
			</div>
			<? if($k % 3 == 2): ?>
				<div style="clear:both;"></div>
			<? endif; ?>
		<? endforeach; ?>
	</div>
<? endif; ?>