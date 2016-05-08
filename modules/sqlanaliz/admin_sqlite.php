<?

if(array_key_exists("drop", $_GET)){
	if($f = mpopendir(".htdb2")){
		file_put_contents($f, "");
	}
}else{ mpre("<a href='/{$arg['modname']}:{$arg['fn']}/drop'>drop</a>"); }

$strtr = array("varchar(255)"=>"TEXT", "int(11)"=>"INTEGER", "smallint(6)"=>"INTEGER", "text"=>"TEXT");

if($db = mpopendir($f = ".htdb2")){
	if($conn = new PDO($info = "sqlite:$db")){
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		foreach(tables() as $table){
			$fields = array_map(function($name, $type) use($strtr){
				return "  `{$name}` ". ($strtr[$type] ?: "[$type]"). ($name == "id" ? " PRIMARY KEY" : "");
			}, array_keys($fild = array_column(fields($tab = first($table)), "Type", "Field")), array_values($fild));

			mpre($create = "CREATE TABLE {$tab} (\n". implode(",\n", $fields). "\n)");
			$conn->exec($create);

			foreach(rb($tab) as $t){
				$t = array_map(function($val){
					return strtr(SQLite3::escapeString($val), array("?"=>"??", "'"=>"''", "\""=>"\"\"", "\n"=>"\\n"));
				}, $t);

				if(($tab == "mp_users") || ($tab == "mp_users_mem")){
					mpre("Не добавляем пользователей", $t);
				}else if(($tab == "mp_settings") && ($t['name'] == "admin_usr")){
					mpre("Пропускаем администратора", $t);
				}else if(pre($sql = "INSERT INTO `{$tab}` (`". implode("`, `", array_keys($t)). "`) VALUES (\"". implode("\", \"", array_values($t)). "\")")){
					pre($t); $conn->exec($sql);
				}
			}
		}
	}
}else{ mpre("База для выгрузки не найдена", $f); }


