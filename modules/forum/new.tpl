<style>
	.el {margin:3px;}
	.fw {width:80%;}
</style>
<script language="javascript">
	$(function(){
		$("select[name=aid]").change(function(){
			var val = $(this).find("option:selected").val();// alert(val);
			$("textarea[name=text]").css("display", (val == 3 ? "block" : "none"));
		});
	});
</script>
<div>
	<div style="margin:10px;">
		<a href="/<?=$arg['modpath']?>">Весь форум</a>
	</div>
	<form method="post">
		<? if(true || abs($_GET['id'])%2): ?>
			<select name="aid">
				<option value="2">Тема обсуждения</option>
				<option value="3" selected>Обсуждения</option>
			</select>
		<? endif; ?>
		<div class="el"><input class="fw" type="text" name="name" title="Заголовок"></div>
		<div class="el"><input class="fw" type="text" name="description" title="Описание"></div>
		<div class="el" style="margin-top:10px;"><textarea class="fw" name="text" title="Сообщение"></textarea></div>
		<div class="el"><input type="submit" value="Созать тему"></div>
	</form>
</div>