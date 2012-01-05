<? die; # Последнее фото

//if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
//	return;
//} 
$sql = "SELECT f.* FROM {$GLOBALS['conf']['db']['prefix']}foto_cat as c, {$GLOBALS['conf']['db']['prefix']}foto_img as f WHERE f.kid=c.id GROUP BY c.id ORDER BY id DESC";
foreach(mpql(mpqw($sql)) as $k=>$img){
  echo "<div align=center>{$img['description']}<p><img src=/foto:img/{$img['id']}/w:200/h:200/img.jpg border=0></div>";	
}

?>