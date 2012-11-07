<? die; #Логин

if ((int)$arg['confnum']) return;

if ($conf['user']['uname'] != $conf['settings']['default_usr']){
	$gname = array_flip($conf['user']['gid']);
	$gid  = $conf['user']['gid'];
}

$anket = mpql(mpqw("SELECT id, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_anket"), 0);

?>
<? if($conf['user']['flush']): ?>
	<script type="text/javascript" src="/include/jquery/simplemodal-demo-basic/js/jquery.simplemodal.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.newpass').modal();
			$('#editpass').click(function(){
				$.post('/users:pass/<?=(int)$conf['user']['uid']?>/null', {"new":$("#new").val(), "ret":$("#ret").val()}, function(data){
					$('#passresult').html(data);
				});
			});
		});
	</script>
	<style>
		.newpass {
			display:none;
			background-color:#444;
			width:260px;
			height:120px;
			-webkit-border-radius: 20px;
			-moz-border-radius: 20px;
			border-radius: 20px;
			padding:20px;
			color: white;
		}
	</style>
	<div class="newpass">
		<table>
			<tr><td colspan="2">Усновите свой новый пароль:</td></tr>
			<tr>
				<td>Пароль</td>
				<td><input id="new" type="text" name="new"></div>
			</tr>
			<tr>
				<td>Повторите пароль</td>
				<td><input id="ret" type="text" name="ret"></td>
			</tr>
			<tr>
				<td><div id="passresult" style="color:white;"></div></td>
				<td>
					<input id="editpass" type="submit" value="Изменить">
				</td>
			</tr>
		</table>
	</div>
<? endif; ?>

<? if($conf['user']['uname'] != $conf['settings']['default_usr']): ?>
	<div class="user_links">
		<a href='/?logoff'>Выход</a> <a href="<?=($conf['settings']["{$arg['modpath']}_edit_link"] ?: "/{$arg['modname']}")?>">Кабинет</a>
		<?// if($conf['modules'][ $conf['modules']['admin']['id'] ]['access']	|| ($conf['user']['uname'] == $conf['settings']['admin_usr'] )): ?>
		<? if(array_search($conf['settings']['admin_grp'], $conf['user']['gid'])): ?>
			<a href='/admin'>Управление</a>
		<? endif; ?>
		<div class="user_hello">Добро пожаловать: <?=$conf['user']['uname']?></div>
		<div class="user_grp">Группа:
			<? foreach($gid as $k=>$v): ?>
				<?=$v?>
			<? endforeach; ?>
		</div>
	</div>
<? else: ?>
	<form action='' method='post'>
		<table border=0>
			<input type='hidden' name='reg' value='Аутентификация'>
			<!-- <tr><td>OpenID: </td><td><img src="http://wiki.openid.net/f/openid-16x16.gif"> <a href="/users:openid">Представиться</a></td></tr> -->
			<tr><td>Логин: </td><td><input class='login' type='text' name='name'></td></tr>
			<tr><td>Пароль: </td><td><input class='login' type='password' name='pass'></td></tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<a href='<?=($conf['settings']["{$arg['modpath']}_reg_link"] ?: "/{$arg['modname']}:reg")?>'>Регистрация</a>
					<br /><a href="/users:resque">Восстановление</a> |
					<input type='submit' value='Войти'>
				</td>
			</tr>
		</table>
	</form>
<? endif; ?>
