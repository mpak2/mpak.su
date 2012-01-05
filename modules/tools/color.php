<? die;

$colors = array('ff', 'cc', '99', '66', '33', '00');

foreach($colors as $r){
	foreach($colors as $g){
		foreach($colors as $b){
			$conf['tpl']['cls'][] = "#$r$g$b";
		}
	}
}

//mpre($conf['tpl']['cls']);

?>