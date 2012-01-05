<? die;

if (array_key_exists('null', $_GET)){
	header("Content-type: text/xml");
}

$conf['tpl']['status'] = array('0'=>'new', '1'=>'wait', '2'=>'done', '3'=>'cancel');
$conf['tpl']['items'] = array();
if(!empty($_POST['xml'])){
	$xml = new SimpleXMLElement(stripslashes($_POST['xml']), LIBXML_NOCDATA);
	$rs = $xml->xpath('/items/item');
	foreach($rs as $k=>$v){
		$conf['tpl']['items'][] = (int)$v;
	}
}

$conf['tpl']['basket'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE id IN (".implode(', ', $conf['tpl']['items']). ")"));

?>