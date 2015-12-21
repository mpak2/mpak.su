<? die; # ХТМЛКод

if ((int)$arg['confnum']){
	# Сохранение и востановление параметров модуля
	if (isset($_POST[ $arg['confnum'] ]['text'])){
		mpqw("UPDATE {$conf['db']['prefix']}blocks_index SET param = '".mpquot($param = $_POST[ $arg['confnum'] ]['text'])."' WHERE id = {$arg['confnum']}");
	}else{
		$param = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['confnum']}"), 0, 'param');
	}

	echo "<form action='{$_SERVER['REQUEST_URI']}' method='post'>";
	echo "<textarea name='{$arg['confnum']}[text]' style='width:100%; height: 400px'>".htmlspecialchars(stripslashes($param))."</textarea>";
	echo "<p><input type='submit' value='Сохранить'>";
	echo "</form>";
	return;
}

echo mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['blocknum']}"), 0, 'param');

?>
