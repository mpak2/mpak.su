<form action="" method="post" enctype="multipart/form-data">
	<input name="url" type="name" placeholder="Адрес изображения" value="<?=$_POST['url']?>" style="width:70%;"><br />
	<input name="base64" type="file"> <input type="submit">
	<? if($conf['tpl']['base64']): ?>
		<div style="margin:10px 0;"><img src="data:image/jpeg;base64,<?=$conf['tpl']['base64']?>"></div>
		<div style="margin:10px 0;">Вместе с изображением используйте как</div>
		<textarea style="width:100%; height:400px;">background-image: url(data:image/jpeg;base64,<?=$conf['tpl']['base64']?>)</textarea>
	<? endif; ?>
</form>