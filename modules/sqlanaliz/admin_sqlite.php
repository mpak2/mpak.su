<?

$strtr = array("varchar(255)"=>"TEXT", "int(11)"=>"INTEGER", "smallint(6)"=>"INTEGER", "text"=>"TEXT");

if($conn = new PDO($info = "sqlite:". $f = mpopendir(".htdb2"))){
	mpre($info);
	foreach(tables() as $table){
		$fields = array_map(function($name, $type) use($strtr){
			return "  `{$name}` ". ($strtr[$type] ?: "[$type]");
		}, array_keys($fild = array_column(fields($tab = first($table)), "Type", "Field")), array_values($fild));

		mpre($create = "CREATE TABLE `{$tab}` (\n". implode(",\n", $fields). "\n)");
		$conn->exec($create);

		foreach(rb($tab) as $t){
			$conn->exec("INSERT INTO `$tab` SET (\"". implode("\", \"", array_keys($t)). "\") VALUES (\"". implode("\", \"", array_values($t)). "\")");
		}
	}
}


