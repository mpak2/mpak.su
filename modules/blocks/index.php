<?

if(get($_SERVER, 'HTTP_REFERER') && ($gt = mpgt("/". implode("/", array_slice(explode("/", $_SERVER['HTTP_REFERER']), 3)))) && (get($gt, 'r') == "{$conf['db']['prefix']}{$arg['modpath']}_index") && ($conf['settings']['theme/*:admin'] != $conf['settings']['theme'])){
	exit(header("Location: /blocks/theme:zhiraf/{$_GET['id']}"));
}elseif(($blocknum = get($_GET, "id")) && ($index = rb("index", "id", $blocknum))){
	inc("modules/". $index['src'], array("arg"=>array("blocknum"=>$blocknum)+$arg));
}elseif((get($_GET, '')) && ($index = rb("index", "alias", "[{$_GET['']}]"))){ mpre("Доступ к выводу блока по алиасу (временно выключен)");
	inc("modules/". $index['src'], array("arg"=>array("alias"=>$_GET[''])+$arg));
}
