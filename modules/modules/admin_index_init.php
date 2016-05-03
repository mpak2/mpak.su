<?

foreach(rb("index") as $modules){
//	if($init = mpopendir($f = "modules/{$modules['folder']}/init.php")){
		$settings = $init = $params = array(); $tpl['init'][ $modpath = $modules['folder'] ] = "<? # modules/{$modules['folder']}/init.php\n\n";
		foreach(ql("SHOW TABLES LIKE \"{$conf['db']['prefix']}{$modpath}%\"") as $table){
			if($table = first($table)){
				if(($create = ql($sql = "SHOW CREATE TABLE `{$table}`", 0, 'Create Table')) && ($create = preg_replace("# AUTO_INCREMENT=\d+ #", " ", $create))){
//					mpre($sql, implode("_", array_slice(explode("_", $table), 2)));
					($t = implode("_", array_slice(explode("_", $table), 2)));
					$i = "if(mpsettings(\$t = \"{$modpath}". ($t ? "_{$t}" : ""). "\", ". (($s = get($conf, 'settings', ($params[] = $tabl = "{$modpath}". ($t ? "_{$t}" : "")))) ? "\"{$s}\"" : "null"). ") && !tables(\$table = (\"{\$conf['db']['prefix']}{\$t}\"))){\nqw(\"{$create}\");\n}";
					$init[ strpos($i, "FOREIGN KEY") ? 0 : 1 ][] = strtr($i, $w = array("`{$table}`"=>"`{\$table}`", "\n"=>"\n"/*, "CREATE TABLE"=>"CREATE TABLE IF EXISTS"*/));
				}
			}
		} krsort($init); foreach($init as $i){ $tpl['init'][ $modpath ] = (get($tpl, 'init', $modpath) ? $tpl['init'][ $modpath ]. implode(" ", $i) : implode("\n", $init)). " "; } // $tpl['init'][ $modpath ] = 

		foreach(rb("{$conf['db']['prefix']}settings", "modpath", "id", array_flip([$modpath])) as $set){
			if((strpos($set['name'], $set['modpath']. "_") === 0) && (array_search(($set['name']), $params) === false)){
				$settings[] = "mpsettings(\"{\$arg['modpath']}". substr($set['name'], strlen($modpath)). "\", \"" . mpsettings($set['name']). "\")";
			}else{ /*mpre("Неверный формат свойств", $set);*/ }
		} $tpl['init'][ $modpath ] .= "\n\n". implode(";\n", $settings). ";";
//		mpre(htmlspecialchars($tpl['init'][ $modpath ]));
//	}else{ /*mpre("Файл первоначальной установки {$f} не найден");*/ }
}
