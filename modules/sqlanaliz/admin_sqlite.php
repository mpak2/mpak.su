<?

if(!$strtr = array("varchar(255)"=>"TEXT", "int(11)"=>"INTEGER", "smallint(6)"=>"INTEGER", "text"=>"TEXT")){ mpre("Ошибка создания типов данных");
}elseif(!$conf['db']['type'] == "mysql"){ mpre("Данный скрипт доступен только при работе с БД mysql");
}elseif(!$db = mpopendir($f = ".htdb2")){ mpre("База для выгрузки не найдена", $f);
}elseif(!is_writable($db)){ mpre("Файл {$db} недоступен для изменений");
}elseif(!$conn = new PDO($info = "sqlite:$db")){
}elseif(array_key_exists("drop", $_GET) && ($f = mpopendir(".htdb2")) && file_put_contents($f, "")){ mpre("Ошибка обнуления БД");
}else{ mpre("<a href='/{$arg['modname']}:{$arg['fn']}/drop'>drop</a>");
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	foreach(tables() as $table){
		$fields = array_map(function($name, $type) use($strtr){
			return "  `{$name}` ". ($strtr[$type] ?: "[$type]"). ($name == "id" ? " PRIMARY KEY" : "");
		}, array_keys($fild = array_column(fields($tab = first($table)), "Type", "Field")), array_values($fild));

		mpre($create = "CREATE TABLE {$tab} (\n". implode(",\n", $fields). "\n)");
		$conn->exec($create);
		if(($INDEXES = qn("SHOW INDEXES IN {$tab}", "Key_name")) && ($INDEXES = array_diff_key($INDEXES, array_flip(['PRIMARY'])))){
			foreach(rb($INDEXES, "Index_type", "Key_name", "[BTREE]") as $indexes){
				$conn->exec(mpre("CREATE INDEX IF NOT EXISTS {$indexes['Key_name']} ON {$tab} ({$indexes['Column_name']});", $indexes));
			}// exit(mpre($INDEXES));
		}

		foreach(rb($tab) as $t){
			$t = array_map(function($val){
				return strtr(SQLite3::escapeString($val), array("?"=>"??", "'"=>"''", "\""=>"\"\"", "\n"=>"\\n"));
			}, $t);


			if(($tab == "mp_users") || ($tab == "mp_users_mem")){
				mpre("Не добавляем пользователей", $t);
			}else if(($tab == "mp_settings") && ($t['name'] == "admin_usr")){
				mpre("Пропускаем администратора", $t);
			}else if($sql = "INSERT INTO `{$tab}` (`". implode("`, `", array_keys($t)). "`) VALUES (\"". implode("\", \"", array_values($t)). "\")"){
				pre($t); $conn->exec(mpre($sql));
			}
		}
	}
}

