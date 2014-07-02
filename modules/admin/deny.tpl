<style>
	#footer{ display:none; }
	body, html { background:none; }
</style>
<div id="authorization">
	<script>
		$(function(){
			$("#authorization form input[name=name]").focus();
		});
	</script>
	<form method="post">
		<input type="hidden" value="Аутентификация" name="reg">
		<input type="text" name="name" title="логин" />
		<input type="password" class="txt" name="pass" title="пароль" />
		<div class="button"><input type="submit" value="вход" /></div>
	</form>
</div>