<? /*if(!is_array(call_user_func(function(){
		if(!$_POST){ mpre("Пост запрос не указан", $_POST);
		}elseif(!array_key_exists('null', $_GET)){ mpre("Не аякс запрос");
		}else{
		} return [];
	}))): mpre("ОШИБКА сохранения данных пост запроса") ?>
<? else*/if(!$format = '{%n  "commit": "%H",%n  "abbreviated_commit": "%h",%n  "tree": "%T",%n  "abbreviated_tree": "%t",%n  "parent": "%P",%n  "abbreviated_parent": "%p",%n  "refs": "%D",%n  "encoding": "%e",%n  "subject": "%s",%n  "sanitized_subject_line": "%f",%n  "body": "%b",%n  "commit_notes": "%N",%n  "verification_flag": "%G?",%n  "signer": "%GS",%n  "signer_key": "%GK",%n  "author": {%n    "name": "%aN",%n    "email": "%aE",%n    "date": "%aD"%n  },%n  "commiter": {%n    "name": "%cN",%n    "email": "%cE",%n    "date": "%cD"%n  }%n}'): mpre("ОШИБКА установки формата ответа") ?>
<? elseif(is_string($email_out = `git config --global user.email "www-data@{$_SERVER['HTTP_HOST']}"`)): mpre("ОШИБКА комманды на установку емаила", $email_out) ?>
<?// elseif(!is_null($email_out = shell_exec($email))): mpre("ОШИБКА Установки почты", $cmd) ?>
<? elseif(is_string($user_out = `git config --global user.name "www-data"`)): mpre("Ошибка комманды на имя пользователя", $user_out) ?>
<?// elseif(!is_null($user_out = shell_exec($user))): mpre("ОШИБКА Установки почты", $cmd) ?>
<? elseif(!$cmd = "git log --pretty=format:'{$format},'"): mpre("Комменда получения истории версий") ?>
<? elseif(!$out = shell_exec($cmd)): mpre("ОШИБКА получения списка изменений (Если git проект не создан добавьте его в директории >#apt install -y git-core; git init; git commit -am \"my project\")", $cmd) ?>
<? elseif(!$out_json = json_decode($o = "[{$out}null]", true)): mpre("ОШИБКА получения массива изменений", $o) ?>
<? elseif(!$INDEX = array_filter(array_map(function($json){ # Добавление версии в базу
		if(!$json){// mpre("Пустая версия (пропускаем)");
		}elseif(!$_author = get($json, 'author')){ mpre("ОШИБКА получения автора", $json);
		}elseif(!$author = fk('author', ['email'=>$_author['email']], $_author)){ mpre("ОШИБКА добавления автора в базу");
		}elseif(!$_commiter = get($json, 'commiter')){ mpre("ОШИБКА получения комментатора", $json);
		}elseif(!$commiter = fk('author', ['email'=>$_commiter['email']], $_commiter)){ mpre("ОШИБКА добавления комментатора в базу");
		}elseif(!$index = fk('index', $w = ['commit'=>$json['commit']], $w = ($w + $json + ['name'=>$json['sanitized_subject_line'], 'author_id'=>$author['id'], 'git-author'=>$commiter['id']]), $w)){ mpre("ОШИБКА добавления версию в базу");
		}else{ return $index; }
	}, $out_json))): mpre("ОШИБКА получения списка версий базы") ?>
<? elseif(!$cmd = 'git config status.short true; git status; git config status.short false;'): mpre("ОШИБКА устанвоки комманды получения статуса") ?>
<? elseif(!$status_out = shell_exec($cmd)): mpre("ОШИБКА получения списка изменений (Если git проект не создан добавьте его в директории ># git init; git commit -am \"my project\")", $cmd) ?>
<? elseif(!$status_list = array_filter(explode("\n", $status_out))): mpre("ОШИБКА разбивки статуса по строкам") ?>
<? else:// mpre($status_list) ?>

	<h2>История изменений</h2>
	<div class="commit">
		<style>
			.commit .table > div > span {padding:2px;}
			.commit .cell {display:table-cell; width:50%; padding:5px;}
		</style>
		<script async>
			(function($, script){
				$(script).parent().on("click", "button[name=commit]", function(e){
					if(mess = prompt("Комментарий")){ $(e.currentTarget).attr("value", mess);
					}else{ alert("Комментарий не задан");
						return false;
					}
				}).one("init", function(e){
				}).ready(function(e){ $(script).parent().trigger("init"); })
			})(jQuery, document.currentScript)
		</script>
		<span class="cell">
			<h1>Текущая файловая система</h1>
			<form method="post">
				<div class="table border">
					<div class="th">
						<span>Добавлен</span>
						<span>Изменен</span>
						<span>Файл</span>
						<span>Управление</span>
					</div>
					<? foreach($status_list as $line): ?>
						<? if(!preg_match($r = "#([MA\s\?]+)?\s(.*)$#", $line, $match)): mpre("ОШИБКА разбора регулярным выражением `{$r}`") ?>
						<? elseif(!$split = explode(" ", $line)): mpre("ОШИБКА разделеления строки") ?>
						<? elseif(!$stat = get($match, 1)): mpre("ОШИБКА получения статуса файла", $split) ?>
						<? elseif(!is_string($added = trim(substr($stat, 0, 1)))): mpre("ОШИБКА поулчения статуса добавления") ?>
						<? elseif(!is_string($edited = trim(substr($stat, 1, 1)))): mpre("ОШИБКА поулчения статуса изменения") ?>
						<? elseif(!$file = get($match, 2)): mpre("ОШИБКА получения имени файла", $split) ?>
						<? else:// mpre($line, $match, "`$stat`") ?>
							<div>
								<span style="color:green;" title="<?=("A" == $added ? "Новый файл" : "Изменения")?>"><?=($added ? "Добавлен" : "")?> <?=$added?></span>
								<span style="color:red;"><?=($edited ? "Изменен" : "")?> <?=$edited?></span>
								<span><?=$file?></span>
								<span><button name="add" value="<?=$file?>">Добавить</button></span>
							</div>
						<? endif; ?>
					<? endforeach; ?>
				</div>
				<p><button name="commit">Сохранить</button>
			</form>
		</span>
		<span class="cell">
			<h1>Точки возврата истории</h1>
			<form method="post">
				<div class="table border" style="width:auto;">
					<div class="th">
						<span>Версия</span>
						<span>Имя</span>
						<span>Автор</span>
						<span>Коментатор</span>
						<span>Управление</span>
					</div>
					<? foreach($INDEX as $index):// mpre($index) ?>
						<? if(!$author = rb('author', 'id', $index['author_id'])): mpre("ОШИБКА получения автора версии") ?>
						<? elseif(!$_author = rb('author', 'id', $index['git-author'])): mpre("ОШИБКА получения автора версии") ?>
						<? else: ?>
							<div>
								<span><?=$index['commit']?></span>
								<span><?=$index['name']?></span>
								<span><?=$author['name']?></span>
								<span><?=$_author['name']?></span>
								<span><button>Вернуть</button></span>
							</div>
						<? endif; ?>
					<? endforeach; ?>
				</div>
			</form>
		</span>
	</div>
<? endif; ?>
