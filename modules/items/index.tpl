<!-- [settings:foto_lightbox] -->
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
			<? endforeach; ?>
	</div>
	<div><!-- [settings:comments] --></div>
<? else: ?>
	<div style="overflow:hidden;">
		<div style="font-size:180%; font-weight:bold; text-align:center; border-bottom:1px solid gray; margin-bottom:20px;"><?=$conf['tpl']['type']['name']?></div>
		<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
			<div style="overflow:hidden; width:33%; float:left; text-align:center; margin:10px 0; position:relative;<?=($k%3 == 0 ? ' clear:both;' : '')?>">
				<? if($arg['access'] >= 3): ?>
				<? endif; ?>
				<? if($v['stock']): ?>
					<div style="position:absolute; top:10px; left:20px;"><img src="/themes/null/images/stock.gif"></div>
				<? endif; ?>
				<? if($v['hit']): ?>
					<div style="position:absolute; top:50px; left:20px;"><img src="/themes/null/images/hit.gif"></div>
				<? endif; ?>
				<div id="gallery" style="text-align:center; margin:3px 10px; height:200px;">
					<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:600/h:500/null/img.jpg">
						<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:200/h:200/null/img.jpg">
					</a>
				</div><div style="clear:both;"></div>
				<div style="margin:0 5px; font-weight:bold; background-color:#444; color:#eee;"><?=$v['name']?></div>
				<div style="height:5px; margin:0 5px; background-color: rgb(207, 207, 207);"></div>
				<!--div style="margin:0 5px; background-color: red; color:#eee;"><?=$v['price']?> рублей.</div-->
			</div>
		<? endforeach; ?>
		<div style="margin:10px; clear:both;"><?=$conf['tpl']['mpager']?></div>
	</div>
<? endif; ?>

<a href='/pages/pid:10' id='i-kontakts'>Контакты</a>
<h3 class='blockhiddenH10' style='margin-top:10px; padding-left:50px;'>Сделать заказ</h3>
	<div class='obert blockhidden10'>
	<div class="b1"><b></b></div>
	<div class="b2"><b><i></i></b></div>
	<div class="b3"><b><i></i></b></div>
	<div class="b4"><b></b></div>
	<div class="b5"><b></b></div>
	<form method="post">
		<div class='cnt'>
					<div style="margin-top:0px;">
						<span style="display:block;float:left;">Контактное лицо: </span><input type=text name=contact style="margin-left:37px; width: 485px;">
					</div>
					<div style="margin-top:5px;">
						<span style="display:block;float:left;">Номер телефона: </span><input type=text name=number style="margin-left:35px; width: 485px;">
					</div>
					<div style="margin-top:5px;">
						<span style="display:block;float:left;">e-mail: </span><input type=text name=email style="margin-left:100px; width: 485px;">
					</div>
					<div style="width:620px; text-align:right;">
						<span style="display:block; width:30px;">Комментарий: </span>
						<textarea name="description" style="width:100%; height:30px;" title="Комментарии к заказу"></textarea>
					</div>
					<div style="margin:10px 30px 0; text-align:right;">
						<input type="submit" name="submit" value="Заказать" >
					</div>
			</div>
	</form>
	<div class="b5"><b></b></div>
	<div class="b4"><b></b></div>
	<div class="b3"><b><i></i></b></div>
	<div class="b2"><b><i></i></b></div>
	<div class="b1"><b></b></div>
	</div>
