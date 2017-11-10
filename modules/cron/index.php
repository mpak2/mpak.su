<?
	$log = function($text,$rewrite_id=false) use(&$_LOG_ID,&$_TASK_ID){
		echo("\033[33m{$text}\n\033[37m");
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
	};
	//удаляем старые логи
	qw("DELETE FROM `{$conf['db']['prefix']}{$variables['arg']['modpath']}_log` WHERE `time` < ".(time() - intval(rb('settings','name','[log_days]','value')?:30)*24*60*60));
	
	//Параметр "!" для принудительного запуска выключенной задачи
	if(!$task=rb('index','id',$_TASK_ID=intval(get($_GET,'id')))){$log("Задача {$_TASK_ID} отсутствует!",true);
	}elseif($task['hide'] and !isset($_GET['!'])){	$log("Задача {$_TASK_ID} выключенна!",true);
	}elseif(!$path = mpopendir($task['path'])){	$log("Файл {$task['path']} не найден!",true);
	}else{
		$log("Задача {$_TASK_ID} была запущена!",true);
		echo("\033[32m");
		mp_require_once($path,true);
		echo("\033[37m\n");
		$log("Задача {$_TASK_ID} успешно отработала!",true);
	}
?>