<? die;

$conf['tpl']['delemiter'] = array(
	','=>'Запятая',
	' '=>'Пробел',
	'|'=>'Вертикальная черта',
);

if($post = $_POST['asql']){
	echo $delimiter = $conf['tpl']['delemiter'][ $post['delemiter'] ] ? $post['delemiter'] : ',';
	foreach(explode("\n", $post['text']) as $k=>$v){
		$val = array();
		foreach(explode($delimiter, $v) as $n=>$z){
			$val["{". $n. "}"] = trim($z);
		}
		$conf['tpl']['result'][] = strtr($post['sql'], $val);
	}
} //mpre($conf['tpl']['result']);

?>