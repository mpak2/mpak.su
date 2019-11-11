<?

if(get($_GET, 'tn')){
	if(!$tn = urldecode($_GET['tn'])){ mpre("ОШИБКА получения имени таблицы");
	}elseif(!$fn = $_GET['fn']){ mpre("Имя таблицы в адресе не задано");
	}elseif(!$index = rb($tn, 'id', (int)$_GET['id'])){ mpre("ОШИБКА получения записи таблицы `{$tn}`");
	}elseif(!$file_name = (($img = get($index, $fn)) ? mpopendir("include"). "/{$img}" : mpopendir("img/no.". ($exp = "png")))){ mpre("ОШИБКА получения пути до файла");
	}elseif(!array_search($exp = strtolower(last(explode('.', $file_name))), array(1=>"jpg", "jpeg", "png", "gif"))){
		$file_name = mpopendir($f = "img/no.". ($exp = "png"));
	}else if(!$img = mprs($file_name, (get($_GET, "w") ?: 100), (get($_GET, "h") ?: 100), (get($_GET, "c") ?: 0))){ mpre("ОШИБКА формирования изображения");
	}else{ header("Content-type: image/{$exp}");
		echo $img;
	}
}elseif(array_key_exists('', $_GET) && ($file_name = mpopendir("modules/{$arg['modpath']}/img/". basename($_GET[''])))){
	if(!$img = mprs($file_name, (get($_GET, "w") ?: 100), (get($_GET, "h") ?: 100), (get($_GET, "c") ?: 0))){ mpre("ОШИБКА формирования статисческого изображения");
	}else if(!$exp = last(explode('.', $file_name))){ mpre("ОШИБКА получения расширения файла");
	}else{ header ("Content-type: image/{$exp}");
		echo $img;
	}
}else{
	echo "<ul>";
	foreach(mpreaddir("modules/{$arg['modpath']}/img", true) as $n=>$img){
		echo "<li style=\"display:inline-block; width:80px; overflow:hidden; text-align:center;\"><img src=\"/{$arg["modpath"]}:{$arg['fn']}/w:50/h:50/null/{$img}\"><div><a href=\"/{$arg["modpath"]}:{$arg['fn']}/null/{$img}\" title=\"{$img}\">{$img}</a></div></li>";
	} echo "</ul>";
}
