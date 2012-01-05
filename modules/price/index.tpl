<? if($_POST['submit']): ?>
	<meta http-equiv="refresh" content="3">
	<div style="margin:100px 0px; text-align:center;">Ваш заказ принят.</div>
<? else: ?>
	<? if($arg['access'] <= 1): ?>
		<div style="font-size:150%; color:red; text-align:center;">Для заказа <a href="/users:reg">зарегистрируйтесь</a> и авторизуйтесь</div>
	<? endif; ?>
	<form method="post">
		<script>
			jQuery(document).ready(function(){
				jQuery('input.check').attr('checked', false);
				jQuery('input.count').attr('disabled', true);
				jQuery('input.count').change(function(){
					jQuery(this).parent().find('.check').change();
				});
				jQuery('input.check').change(function(){
					var price = parseInt(jQuery(this).parent().parent().find('#price').text());
					var sum = parseInt(jQuery('#itog').text());
					var count = parseInt(jQuery(this).parent().find('.count').val());
					if(jQuery(this).attr('checked')){
						jQuery(this).parent().find('.count').removeAttr('disabled');
//						jQuery('#itog').text(sum+price*count);
					}else{
						jQuery(this).parent().find('.count').attr('disabled', true);
//						jQuery('#itog').text(sum-price);
					}
					var sum = 0;
					jQuery('.check').each(function(){
						if(jQuery(this).attr('checked')){
							var price = parseInt(jQuery(this).parent().parent().find('#price').text());
							var count = parseInt(jQuery(this).parent().find('.count').val());
							sum += price*count;
						}
						jQuery('#itog').text(sum);
					});
				});
			});
		</script>
		<div>
			<? foreach($conf['tpl']['type'] as $n=>$t): ?>
				<h2 style="margin-left:60px; color:blue;"><?=$t['name']?></h2>
				<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): if($v['type_id'] != $t['id']) continue; ?>
					<div style="margin:5px;">
						<div style="width:300px; font-weight:bold; float:left;"><?=$v['name']?></div>
						<div style="width:50px; font-weight:bold; float:left;"><?=$v['weight']?></div>
						<? if($arg['access'] > 1): ?>
							<div style="width:90px; float:right;">
								<input class="count" name="count[<?=$v['id']?>]" type="text" style="width:40px;" value=1 style="position:relative; top:-10px;disabled:disabled;">
								<input class="check" name="check[<?=$v['id']?>]" type="checkbox">
							</div>
						<? endif; ?>
						<div id="price" style="width:70px; font-weight:bold; float:right;"><?=$v['price']?> р.</div>
						<!-- <div style="margin:10px;"><?=$v['description']?></div> -->
					</div><div style="clear:both;"></div>
				<? endforeach; ?>
			<? endforeach; ?>
			<? if($arg['access'] > 1): ?>
				<div style="margin-top:20px;">
					<h3 style="width:80px; float:left;">Итого:</h3>
					<div style="float:right; margin: 20px 50px; font-weight:bold; font-size:120%;" id="itog">0 <span>р.</span></div>
				</div>
				<div style="width:620px; text-align:left; clear:both;">
					<textarea name="description" style="width:70%; height:30px;" title="Комментарии к заказу"></textarea>
				</div>
				<div style="margin:10px 80px; text-align:right;">
					<input type="submit" name="submit" value="Заказать" >
				</div>
			<? endif; ?>
		</div>
	</form>
<? endif; ?>