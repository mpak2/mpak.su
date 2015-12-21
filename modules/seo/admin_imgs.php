<?

if(array_key_exists("null", $_GET) && $_POST){
	include "/srv/www/vhosts/mpak.cms/include/parse/simple_html_dom.php";
	$html = new simple_html_dom();

	$index = mpqn(mpqw("SELECT id,name FROM `". mpquot($_POST['index']). "`". ($_POST['from'] ? " WHERE id>=". (int)$_POST['from'] : ""). " ORDER BY id"));
	foreach($index as $v){
		$html->load($data = file_get_contents($url = "http://images.yandex.ru/yandsearch?text=". urlencode(($_POST['prefix'] ? $_POST['prefix']. " " : ""). $v['name']). "&rpt=simage&pos=0&rpt=simage"));
		foreach($html->find(".b-images-list .b-images-item") as $e){
			if(preg_match("/ onclick=\"return (.*?)\"/", $e->outertext, $reg)){
				$json = json_decode(str_replace("&quot;", "\"", $reg[1]));
				$fk = implode("_", array_slice(explode("_", $_POST['index']), 2)). "_id";
				if($img = $json->{"b-images-item"}->dups[0]){
					if(!mpfdk($_POST['key'], array($fk=>$v['id'], "name"=>$json->{"b-images-item"}->grab))){
						echo "\n\n". $src = $img->img_href;
						$img_id = mphid($_POST['key'], "img", 0, $src);
						mpfdk($_POST['key'], array("id"=>$img_id), null, array($fk=>$v['id'], "name"=>$json->{"b-images-item"}->grab));
					}
				}else{
					echo "title exists";
				}
			}
		}
	} /*echo json_encode($imgs);*/ exit;
}

foreach(ql("SHOW TABLES") as $v){
	$tables = array_shift($v);
	$tpl['tables'][] = $tables;
}// mpre($tpl['tables']);
