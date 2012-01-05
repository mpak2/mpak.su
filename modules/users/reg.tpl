<? if(empty($_POST)): ?>
	<style>
		.wt {
			width:100%;
		}
	</style>
	<center><b>Регистрация нового пользователя</b></center><br>
	<form id="reg" method='post'>
	<table width='100%' cellspacing='5' cellpadding='3' border='0'>
	<tr><td align='right' width='30%'>Логин доступа</td><td><input name='name' type='text' class='wt' required></td></tr>
	<tr><td align='right'>E-mail</td><td><input name='email' type='text' class='wt'></td></tr>
	<tr><td align='right'>Пароль</td><td><input name='pass' type='password' class='wt' required></td></tr>
	<tr><td align='right'>Повторите пароль</td><td><input name='pass2' type='password' class='wt' required></td></tr>
	<? foreach($conf['tpl']['fields'] as $k=>$v): ?>
		<tr>
			<td align='right'>
				<?=(empty($conf['settings']["users_field_$k"]) ? "users_field_$k" : $conf['settings']["users_field_$k"])?>
			</td>
			<td>
				<? if(substr($k, -3) == '_id' && $conf['tpl'][$k]): ?>
					<select name="<?=$k?>">
						<? foreach($conf['tpl'][$k] as $k=>$v): ?>
							<option value="<?=$k?>"><?=$v?></option>
						<? endforeach; ?>
					</select>
				<? else: ?>
					<input name='<?=$k?>' type='text' class='wt'>
				<? endif; ?>
			</td>
		</tr>
	<? endforeach; ?>
	<? if($conf['settings']['users_reg_page']): ?>
		<td align='right' valign="top"></td>
		<td>
			<div style="width:100%; height:250px; padding:5px; border:2px solid #ddd;overflow:auto;">
				<?=$conf['tpl']['users_reg_page']['text']?>
			</div>
<!--			<iframe src="<?=$conf['settings']['users_reg_page']?>" style="width:100%;"></iframe>-->
			<div style="margin-top:10px;">
				<script>
					$(function(){
						$("form#reg").find("input[name=add]").attr("disabled", "disabled");
						$("#users_reg_page").change(function(){
							checked = $(this).is(":checked");// alert(checked);
							if(checked){
								$("form#reg").find("input[name=add]").removeAttr("disabled");
							}else{
								$("form#reg").find("input[name=add]").attr("disabled", "disabled");
							}
						});
					});
				</script>
				<input id="users_reg_page" type="checkbox">
				<span>Я согласен</span>
			</div>
		</td>
	<? endif; ?>
	<tr><td>&nbsp;</td><td><input name='add' type='submit' value='Зарегистрироваться'></td></tr>
	</table>
	</form>
<? elseif($conf['tpl']['reg']): ?>
	<? if(!empty($conf['settings']['users_ppact_link'])): ?>
		<img src="<?=$conf['settings']['users_ppact_link']?>">
	<? endif; ?>
	<p><p><center><font color=green>Пользователь зарегистрирован</font>
	<p>Добро пожаловать на сайт <?=$_POST['name']?> <a href=/<?=$arg['modname']?>:edit>Свойства пользователя</a></center>
<? endif; ?>