<? if(!$post = $_POST):// mpre("Пост запрос не указан", $_POST) ?>

<? elseif(!$git_dir = '.git'): mpre("ОШИБКА устанвоки директории гита") ?>
<? elseif(!file_exists($d = $git_dir)): mpre("Директория контроля версий не найдена `{$d}`") ?>
<? elseif(!is_writable($d = $git_dir)): mpre("ОШИБКА доступа к диерктории гита") ?>
<? elseif(call_user_func(function($post){ # Добавление нового файла к комиту
		if(!$file = get($_POST, 'add')){// mpre("Файл добавления не указан");
		}elseif(!file_exists($file)){ mpre("Указанный файл не найден в файловой системе `{$file}`");
		}elseif(!$cmd = "git add {$file}"){ mpre("ОШИБКА установки комманды добавления файла");
		}elseif(!is_null($add_out = shell_exec($cmd))){ mpre("ОШИБКА добавления файла к версии", $cmd, $add_out);
		}else{ mpre($cmd); }
	}, $post)): mpre("ОШИБКА добавления нового файла") ?>
<? elseif(call_user_func(function($post){
		if(!array_key_exists('commit', $post)){// mpre("Комманда сохранения не задана");
		}elseif(!$pwd = "cd ; pwd;"){ mpre("ОШИБКА установки пути до файла настройки");
		}elseif(is_null($dir = trim(shell_exec($pwd)))){ mpre("ОШИБКА Установки почты", $pwd_out);
		}elseif(!$chdir = implode("/", array_slice(explode("/", __DIR__), 0, -2))){ mpre("ОШИБКА установки текущей директории");
		}elseif(!chdir($chdir)){ mpre("ОШИБКА перехода в директорию проекта");
		
		}elseif(!is_writable($dir)){ mpre("ОШИБКА доступа к домашней директории `{$dir}`");
		}elseif(!$mess = get($post, 'commit')){ mpre("Комментарий не задан");
		}elseif(!$cmd = "git commit -am \"{$mess}\"; pwd;"){ mpre("ОШИБКА установки комманды добавления файла");
//		}elseif(!$cmd = "git commit -am"){
		}elseif(!$add_out = `$cmd`){ mpre("ОШИБКА добавления коммита", $cmd, $add_out);
		}else{ mpre("Домашняя директория: `{$dir}`", $email, $email_out, $user, $user_out, "Коммит `{$mess}`", $chdir, $cmd, $add_out);
		}
	}, $post)): mpre("ОШИБКА фиксации версии") ?>
<? else:// mpre($_POST) ?>
<? endif; ?>
