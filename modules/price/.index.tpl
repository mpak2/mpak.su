<? if($_POST['submit']): ?>
	<meta http-equiv="refresh" content="3">
	<div style="margin:100px 0px; text-align:center;">Ваш заказ принят.</div>
<? else: ?>
	<? if($arg['access'] <= 1): ?>
		<div style="font-size:150%; color:red; text-align:center;">Для заказа <a href="/users:reg">зарегистрируйтесь</a> и авторизуйтесь</div>
	<? endif; ?>
	<form method="post">
		<script>
			$(document).ready(function(){
				$('input.check').attr('checked', false);
				$('input.count').attr('disabled', true);
				$('input.count').change(function(){
					$(this).parent().find('.check').change();
				});
				$('input.check').change(function(){
					var price = parseInt($(this).parent().parent().find('#price').text());
					var sum = parseInt($('#itog').text());
					var count = parseInt($(this).parent().find('.count').val());
					if($(this).attr('checked')){
						$(this).parent().find('.count').removeAttr('disabled');
//						$('#itog').text(sum+price*count);
					}else{
						$(this).parent().find('.count').attr('disabled', true);
//						$('#itog').text(sum-price);
					}
					var sum = 0;
					$('.check').each(function(){
						if($(this).attr('checked')){
							var price = parseInt($(this).parent().parent().find('#price').text());
							var count = parseInt($(this).parent().find('.count').val());
							sum += price*count;
						}
						$('#itog').text(sum);
					});
				});
			});
		</script>
		<div>
			<? foreach($conf['tpl']['type'] as $n=>$t): ?>
				<h2 style="margin-left:60px; color:blue;"><?=$t['name']?></h2>
				<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): if($v['type_id'] != $t['id']) continue; ?>
					<div style="margin:5px;">
						<div style="width:450px; font-weight:bold; float:left;"><?=$v['name']?></div>
						<div style="width:50px; font-weight:bold; float:left;"><?=$v['weight']?></div>
						<div id="price" style="width:50px; font-weight:bold; float:left;"><?=$v['price']?> р.</div>
						<? if($arg['access'] > 1): ?>
							<div style="width:90px; float:left;">
								<input class="count" name="count[<?=$v['id']?>]" type="text" style="width:40px;" value=1 style="position:relative; top:-10px;disabled:disabled;">
								<input class="check" name="check[<?=$v['id']?>]" type="checkbox">
							</div>
						<? endif; ?>
						<!-- <div style="margin:10px;"><?=$v['description']?></div> -->
					</div><div style="clear:both;"></div>
				<? endforeach; ?>
			<? endforeach; ?>
			<? if($arg['access'] > 1): ?>
				<div style="margin-top:20px;">
					<h3 style="width:505px; float:left;">Итого:</h3>
					<div style="float:left; margin: 0px 5px; font-weight:bold; font-size:120%;" id="itog">0</div>
					<div>р.</div>
				</div>
				<div style="width:620px; text-align:right;">
					<textarea name="description" style="width:100%; height:30px;" title="Комментарии к заказу"></textarea>
				</div>
				<div style="margin:10px 80px; text-align:right;">
					<input type="submit" name="submit" value="Заказать" >
				</div>
			<? endif; ?>
		</div>
	</form>
<? endif; ?>