<? die;

$tpl['geoname'] = mpql(mpqw("SELECT g1.id AS g1_id, g2.id AS g2_id, g1.lat AS g1_lat, g2.lat AS g2_lat, g1.lng AS g1_lng, g2.lng AS g2_lng FROM {$conf['db']['prefix']}{$arg['modpath']}_geoname AS g1 INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_geoname AS g2 WHERE g1.id<>g2.id"));

foreach($tpl['geoname'] as $v){
	$sqrt = sqrt(pow($v['g1_lat'] - $v['g2_lat'], 2) + pow($v['g1_lng'] - $v['g2_lng'], 2));
	mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_geoname_geoname",
		$w = array("geoname_id"=>$v['g1_id'], "{$arg['modpath']}_geoname"=>$v['g2_id']),
		$w += array("hypotenuse"=>$sqrt, "distance"=>$sqrt*81,3344), $w
	);
}
