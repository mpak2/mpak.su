<?
if(get($_GET,'id')){	
	$file = rb(get($_GET,'tn'),'id',intval(get($_GET,'id')));
	$ext = last(explode(".", $file['file']));	
	include_once(mpopendir('modules/files/defaultmimes.php'));
	file_download(
		mpopendir('include/'.get($file,get($_GET,'fn'))),//путь
		(get($file,'name')?:get($_GET,'')).".".$ext,//имя
		get($conf['defaultmimes'],$ext)?:"application/{$ext}"//mime
	);	
}
?>
