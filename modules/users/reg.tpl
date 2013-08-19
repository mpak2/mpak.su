<? if(empty($_POST)): ?>
	<script src="/include/jquery/jquery.validate.js"></script>
	<script>
		$(function(){
			$("#reg").validate({
				rules:{
					name:"required",
					email:{
						required:true,
						email:true,
					},
					tel:"required",
					pass:"required",
					pass2:"required",
				},
				messages:{
					name:"Поле обязательно для заполнения",
					email:{
						required:"Введите адрес электронной почты",
						email:"Адрес электронной почты не корректен"
					},
					tel:"Обязательное для заполнения поле",
					pass:"Введите пароль",
					pass2:"Повторите введенный пароль",
				}
			});
			$("select[name=country_id]").change(function(){
				if(country_id = $(this).find("option:selected").val()){
					$("select[name=sity_id]").find("option").each(function(key, val){
						if($(val).attr("country_id") == country_id)
							$(val).show(); else $(val).hide();
					});
				}else{
					$("select[name=sity_id]").find("option").show();
				} $("select[name=sity_id] option:visible:first").attr("selected", "selected");
			}).prepend("<option>").find("option:first").attr("selected", "selected");
			$("select[name=sity_id]").change(function(){
				country_id = $(this).find("option:selected").attr("country_id");// alert(country_id);
				$("select[name=country_id]").find("option[value="+country_id+"]").attr("selected", "selected");
			});
		});
	</script>
	<style>
		.wt { width:95%; }
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
							<option <? foreach(array_diff_key($v, array_flip(array("id", "name", "description"))) as $n=>$z): ?> <?=$n?>="<?=$z?>"<? endforeach; ?> value="<?=$k?>">
								<?=(gettype($v) == 'array' ? $v['name'] : $v)?>
							</option>
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
<!--			<a target="blank" href="/pages/<?=$conf['tpl']['users_reg_page']['id']?>"><?=$conf['tpl']['users_reg_page']['name']?></a>-->
<!--			<iframe src="<?=$conf['settings']['users_reg_page']?>" style="width:100%;"></iframe>-->
			<div style="margin-top:10px;">
				<script>
					$(function(){
						$("form#reg").on("click", "input[name=add]", function(){
							var checked = $("#users_reg_page").is(":checked");
							if(!checked){
								alert("Для продолжения регистрации необходимо согласиться с условиями соглашения");
								return false;
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
	<? if($conf['settings']['users_activation']): ?>
		<p>Для звершения регистрации требуется подтверждение указанного при регистрации адреса электронного ящика. Для продолжения регистрации следуйте инструкции присланной на указанный электронный адрес</p>
	<? else: ?>
		<p>Добро пожаловать на сайт <?=$_POST['name']?> <a href=/<?=$arg['modname']?>:edit>Свойства пользователя</a></center>
	<? endif; ?>
<? endif; ?>
