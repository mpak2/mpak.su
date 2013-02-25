<form action="" method="post" enctype="multipart/form-data">
	<input name="url" type="name" placeholder="Адрес изображения" value="<?=$_POST['url']?>" style="width:70%;"><br />
	<input name="base64" type="file"> <input type="submit">
	<? if($conf['tpl']['base64']): ?>
		<div style="margin:10px 0;"><img src="data:image/jpeg;base64,<?=$conf['tpl']['base64']?>"></div>
		<div style="margin:10px 0;">Вместе с изображением используйте как</div>
		<div style="margin:10px 0;">data:image/jpeg;base64,</div>
		<pre><?=$conf['tpl']['base64']?></pre>
	<? endif; ?>
</form>