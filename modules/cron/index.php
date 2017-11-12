<?
	/*
		0   = обычный текст (default color)
		1   = жирный (bold)
		4   = подчеркнутый (underlined)
		5   = мерцающий (flashing text)
		7   = reverse field
		40  = black background
		41  = red background
		42  = green background
		43  = orange background
		44  = blue background
		45  = purple background
		46  = cyan background
		47  = grey background
		100 = dark grey background
		101 = light red background
		102 = light green background
		103 = yellow background
		104 = light blue background
		105 = light purple background
		106 = turquoise background
		31  = красный (red)
		32  = зеленый (green)
		33  = оранжевый (orange)
		34  = синий (blue)
		35  = фиолетовый (purple)
		36  = голубой (cyan)
		37  = серый (grey)
		90  = темно-серый (dark grey)
		91  = светло-красный (light red)
		92  = светло-зеленый (light green)
		93  = желтый (yellow)
		94  = светло-синий (light blue)
		95  = светло-фиолетовый (light purple)
		96  = бирюзовый (turquoise) 
		31  = красный (red)
		32  = зеленый (green)
		33  = оранжевый (orange)
		34  = синий (blue)
		35  = фиолетовый (purple)
		36  = голубой (cyan)
		37  = серый (grey)
		90  = темно-серый (dark grey)
		91  = светло-красный (light red)
		92  = светло-зеленый (light green)
		93  = желтый (yellow)
		94  = светло-синий (light blue)
		95  = светло-фиолетовый (light purple)
		96  = бирюзовый (turquoise)
	*/
	$is_cli = isset($_SERVER['argv']);
	$log = function($text,$rewrite_id=false) use(&$_LOG_ID,&$_TASK_ID,&$is_cli){	
		$log_off = isset($_GET['logoff']) ? ($is_cli  ? "\033[1;31m [Ведение логов отключено!]\033[0;37m" : "<span style='color:red;'> [Ведение логов отключено!]</span>") : "";
		echo($is_cli  ? "\033[1;33m{$text}{$log_off}\n\033[0;37m" : "<span style='color:orange;'>{$text}{$log_off}</span><br/>");		
		if(isset($_GET['logoff'])){
			$logid = fk(
				'log',
				(isset($_LOG_ID) && $rewrite_id ) ? ['id'=>intval($_LOG_ID)] : null,
				[
					'index_id'=>$_TASK_ID,
					'time'=>time(),
					'value'=>$text,
				],
				['value'=>$text],
				'id'
			);
			if($rewrite_id) 
				$_LOG_ID = $logid;		
			return $logid;
		}else{
			return true;
		}
	};
	//удаляем старые логи
	qw("DELETE FROM `{$conf['db']['prefix']}{$variables['arg']['modpath']}_log` WHERE `time` < ".(time() - intval(rb('settings','name','[log_days]','value')?:30)*24*60*60));
	
	//Параметр "!" для принудительного запуска выключенной задачи
	if(!$task=rb('index','id',$_TASK_ID=intval(get($_GET,'id')))){$log("Задача {$_TASK_ID} отсутствует!",true);
	}elseif($task['hide'] and !isset($_GET['!'])){	$log("Задача {$_TASK_ID} выключенна! Для принудительного запуска используйте параметр \"!\".",true);
	}elseif(!$path = mpopendir($task['path'])){	$log("Файл {$task['path']} не найден!",true);
	}else{
		$log("Задача {$_TASK_ID} была запущена!",true);
		echo($is_cli  ? "\033[1;32m" : "<hr/><br/>");
		mp_require_once($path,true);
		echo($is_cli  ? "\033[0;37m\n" : "<br/><br/><hr/>");
		$log("Задача {$_TASK_ID} успешно отработала!",true);
	}
?>