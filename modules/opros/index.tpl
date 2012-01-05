<? if($_GET['id']): ?>
	<style>
		.opros_form {
			margin:20px;
			padding:20px;
			border:1px solid gray;
			-moz-border-radius:30px;
			-webkit-border-radius:30px;
			border-radius:30px;
//			color:white;
			<? if($conf['settings']['opros_background']): ?>
			background-color:<?=$conf['settings']['opros_background']?>;
			<? endif; ?>
		}
	</style>
	<? if($_POST): ?>
		<? if($conf['settings']['opros_company_id'] && $conf['tpl']['order_id']): ?>
			<img src="http://a69.troywell.ru/track/id/<?=$conf['tpl']['order_id']?>/key/<?=md5($conf['settings']['opros_company_id'].$conf['tpl']['order_id'])?>/target/t1">
		<? endif; ?>
		<? if($conf['settings']['opros_ppact_drive'] && $conf['tpl']['order_id']): ?>
			<div style="text-align:center; margin:100px;">Спасибо за участие. Информация сохранена. <a href=>На главную</a></div>
		<? endif; ?>
		<div style="text-align:center; margin-top: 100px;">Спасибо за участие. <a href=/>На главную</a></div>
	<? else: ?>
		<div class="opros_form">
			<div style="text-align:center; padding:0 50px;"><?=$conf['tpl']['opros']['description']?></div>
			<form method="post">
				<? foreach($conf['tpl']['vopros'] as $k=>$v): ?>
					<div style="margin:5px 0 0 10px;"><?=$v['vopros']?>: </div>
					<div>
						<? if($v['type'] == 'select'): ?>
							<select name="<?=$v['id']?>" style="width:100%;">
								<? foreach($conf['tpl']['select'] as $n=>$z): if($z['vid'] != $v['id']) continue; ?>
									<option value="<?=$z['id']?>"<?=($z['id'] == $conf['tpl']['result'][ $v['id'] ] ? ' selected' : '')?>><?=$z['variant']?></option>
								<? endforeach; ?>
							</select>
						<? elseif($v['type'] == 'radio'): ?>
							<? foreach($conf['tpl']['select'] as $n=>$z): if($z['vid'] != $v['id']) continue; ?>
								<div>
									<input type="radio" name="<?=$v['id']?>" value="<?=$z['id']?>"<?=($z['id'] == $conf['tpl']['result'][ $v['id'] ] ? ' checked' : '')?>>
									<?=$z['variant']?>
								</div>
							<? endforeach; ?>
						<? elseif($v['type'] == 'textarea'): ?>
							<textarea name="<?=$v['id']?>" style="width:100%; height:150px;"><?=htmlspecialchars($conf['tpl']['result'][ $v['id'] ])?></textarea>
						<? else: ?>
							<input type="text" name="<?=$v['id']?>" style="width:100%;" value="<?=htmlspecialchars($conf['tpl']['result'][ $v['id'] ])?>">
						<? endif; ?>
					</div>
				<? endforeach; ?>
				<div style="text-align:right;margin-top:10px;"><input type="submit" value="Отправить заявку"></div>
			</form>
		</div>
	<?	endif;  ?>
<? else: ?>
	<ul>
	<? foreach($conf['tpl']['list'] as $k=>$v): ?>
		<li>
			<a alt="<?=$v['description']?>" title="<?=$v['description']?>" href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?></a>
		</li>
	<? endforeach; ?>
	</ul>
<? endif; ?>