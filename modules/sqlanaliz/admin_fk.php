<? die;

//$f = "series"; $s = "series_fields";
//$f = "series_fields"; $s = "desc_series_fields";
//$s = "series";// $f = "type";
//$s = "desc_series_fields";// $f = "series_fields";

$s = "desc_series_fields";// $f = "desc";

if($f && $s){
	$test = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS s.* FROM {$conf['db']['prefix']}{$arg['modpath']}_$s AS s LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_$f AS f ON f.id=s.{$f}_id WHERE f.id IS NULL LIMIT 3"));

	echo "Нарушений :". mpql(mpqw("SELECT FOUND_ROWS() AS cnt"), 0, 'cnt');

	mpre($test);

	mpqw("DELETE s FROM {$conf['db']['prefix']}{$arg['modpath']}_$s AS s LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_$f AS f ON f.id=s.{$f}_id WHERE f.id IS NULL");

	mpqw("ALTER TABLE {$conf['db']['prefix']}{$arg['modpath']}_$f ENGINE=InnoDB");
	mpqw("ALTER TABLE {$conf['db']['prefix']}{$arg['modpath']}_$s ENGINE=InnoDB");

	mpqw("ALTER TABLE {$conf['db']['prefix']}{$arg['modpath']}_$s ADD FOREIGN KEY ({$f}_id) REFERENCES {$conf['db']['prefix']}{$arg['modpath']}_$f (id) ON DELETE CASCADE;");

	//mpqw("ALTER TABLE {$conf['db']['prefix']}{$arg['modpath']}_$s DROP FOREIGN KEY `mp_shop_series_fields_ibfk_2`");
}

$show = mpql(mpqw("SHOW CREATE TABLE {$conf['db']['prefix']}{$arg['modpath']}_$s"), 0);

echo "<pre style=\"margin-top:20px;\">{$show['Create Table']}</pre>";

?>