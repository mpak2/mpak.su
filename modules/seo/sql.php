<?

$seo_index_type = fk("{$conf['db']['prefix']}seo_index_type", $w = array("id"=>1, "name"=>"text/html"), $w, $w);
$seo_location_status = fk("{$conf['db']['prefix']}seo_location_status", $w = array("id"=>301, "name"=>"Moved"), $w, $w);

if($f = mpopendir("modules/{$arg['modpath']}/seo_charset.sql")){
	qw(file_get_contents($f));
}
