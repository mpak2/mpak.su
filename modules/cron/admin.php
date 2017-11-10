<?	
	include_once(mpopendir('modules/admin/admin.php'));
	
	/*
	 № | В БД | В Кроне | Действие
	---|------|---------|---------
	 1 |  ON  |   Yes   |   -/+
	 2 |  ON  |    No   |   Add
	 3 |  Off |   Yes   |   Dell
	 4 |  Off |    No   |    -
	 5 |  No  |   Yes   |   Dell	
	*/
	$tasks = rb('index'); //все задачи в БД
	$is_cron = function($cron){
		return boolval(preg_match(
			"/^((\*|[0-5]?[0-9]|\*\/[0-9]+)\s+(\*|1?[0-9]|2[0-3]|\*\/[0-9]+)\s+(\*|[1-2]?[0-9]|3[0-1]|\*\/[0-9]+)\s+(\*|[0-9]|1[0-2]|\*\/[0-9]"
			."+|jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s+(\*\/[0-9]+|\*|[0-7]|sun|mon|tue|wed|thu|fri|sat)\s*(\*\/[0-9]+|\*|[0-9]+)?)$/i", 
		$cron));
	};
	if(array_key_exists("null", $_GET) and $_POST){
		
		$settings = rb('settings','name');//настройки
		$hash = hash('crc32',$_SERVER['DOCUMENT_ROOT']);//хеш проекта	
		
		$cronText = shell_exec("crontab -l");//читаетм крон
		preg_match_all("@(#MPCron-{$hash}-(\d+)-(\w+)\s[^\n]+)\n(.*)@iu",$cronText,$matches);//ищем только наши задачи
		
		//функция удаления задачи
		$del_task = function($id) use(&$matches){
			$find_id = array_search($id,$matches[2]);
			if(isset($matches[1][$find_id])){
				exec("(crontab -l | grep -v '{$matches[1][$find_id]}' | crontab -)");
				exec("(crontab -l | grep -v '{$matches[4][$find_id]}' | crontab -)");
			}
		};	
		$translit = function ($string){
			$converter = [
				'а' => 'a',   'б' => 'b',   'в' => 'v', 'г' => 'g',	'д' => 'd',   'е' => 'e', 'ё' => 'e',   'ж' => 'zh',  'з' => 'z', 'и' => 'i',
				'й' => 'y',   'к' => 'k', 'л' => 'l',   'м' => 'm',	'н' => 'n',	  'о' => 'o',   'п' => 'p',   'р' => 'r',	'с' => 's', 'т' => 't',
				'у' => 'u', 'ф' => 'f',   'х' => 'h',   'ц' => 'c',	'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch', 'ь' => '\'',	'ы' => 'y',	'ъ' => '\'',
				'э' => 'e',   'ю' => 'yu',  'я' => 'ya', 'А' => 'A','Б' => 'B',   'В' => 'V', 'Г' => 'G',  'Д' => 'D',   'Е' => 'E', 'Ё' => 'E', 
				'Ж' => 'Zh',  'З' => 'Z', 'И' => 'I',   'Й' => 'Y',   'К' => 'K', 'Л' => 'L',	 'М' => 'M',   'Н' => 'N', 'О' => 'O',  'П' => 'P',	
				'Р' => 'R', 'С' => 'S',   'Т' => 'T',   'У' => 'U','Ф' => 'F',	'Х' => 'H',   'Ц' => 'C', 'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
				'Ь' => '\'','Ы' => 'Y',   'Ъ' => '\'', 'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
			];return strtr($string, $converter);
		};
		//Задачи найденные в Кроне
		foreach($matches[2] as $TaskID){
			//Ситуация №3 & №5 (удаляем)
			if(!isset($tasks[$TaskID]) OR get($tasks,$TaskID,'hide')){
				$del_task($TaskID);
			}
		}
		//Задачи найденные в БД
		foreach($tasks as $TaskID=>$data){
			$data['cron'] = trim($data['cron']);
			$update = false;//флаг обновления задачи
			$name = $translit(trim(preg_replace("#[\"'\\\/`]*#iu",'',$data['name']))); //имя задачи (вырезаем все лишнее)
			$bin  = get($settings,'bin','value')?:"php"; //какой будем использовать бинарник
			$cron_cmd  = "{$data['cron']} {$bin} -f {$_SERVER['DOCUMENT_ROOT']}index.php cron {$TaskID}"; //крон задача
			$cron_hash =  hash('crc32',$cron_cmd.$name);//хеш задачи
			$cron_id   = "#MPCron-{$hash}-{$TaskID}-{$cron_hash} {{$name}}";//индификатор задачи
			
			//если задча активна и она записанна но синтаксис не актуален - то обновляем
			if(is_numeric($find_id = array_search($TaskID,$matches[2])) AND !$data['hide'] AND $matches[3][$find_id]!=$cron_hash){
				$del_task($TaskID);
				$update = true;
			}
			//если крон верный
			if($is_cron($data['cron'])){
				//Ситуация №2 или №1 (добавляем)
				if((!in_array($TaskID,$matches[2]) AND !$data['hide']) OR $update){
					exec("(crontab -l ; echo '{$cron_id}') | crontab -");
					exec("(crontab -l ; echo '{$cron_cmd}') | crontab -");
				}
			}
		}		
	}else{
		//not ajax
		foreach($tasks as $TaskID=>$data){
			if(!$is_cron(trim($data['cron'])) AND !$data['hide']){
				pre("Ошибка установки Cron [id:{$TaskID}]: некорректная запись \"{$data['cron']}\"!");
			}
			if(!$data['hide'] and !mpopendir($data['path'])){
				pre("Предупреждение Cron [id:{$TaskID}]: Нет файла {{$data['path']}}");
			}
		}
	}	
?>