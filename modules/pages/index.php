<? die;

// ----------------------------------------------------------------------
// mpak Content Management System
// Copyright (C) 2007 by the mpak.
// (Link: http://mp.s86.ru)
// ----------------------------------------------------------------------
// LICENSE and CREDITS
// This program is free software and it's released under the terms of the
// GNU General Public License(GPL) - (Link: http://www.gnu.org/licenses/gpl.html)http://www.gnu.org/licenses/gpl.html
// Please READ carefully the Docs/License.txt file for further details
// Please READ the Docs/credits.txt file for complete credits list
// ----------------------------------------------------------------------
// Original Author of file: Krivoshlykov Evgeniy (mpak) +7 3462 634132
// Purpose of file:
// ----------------------------------------------------------------------

if($arg['access'] >= 4 && is_numeric($_GET['del'])){
	mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=".(int)$_GET['del']." LIMIT 1");
}elseif($arg['access'] >= 4 && isset($_GET['new'])){
	if($max = max($_GET['id'], $_GET['pid'])){
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET id=".(int)$max. ", uid=".(int)$conf['user']['uid']. ", time=". time().", name=\"Заголовок страницы\", text=\"Текст страницы\"");
		if($id = mysql_insert_id()){
			header("Location: /?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[id]=". (int)$id. "&edit=".(int)$id);
		}
	}else{
		header("HTTP/1.1 404 Not Found");
	}
}

if($_GET['id'] == 5){
	header("Location: /news"); exit;
}else if($_GET['id']){
	$tpl['page'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id = ".(int)max($_GET['pid'], $_GET['id'])), 0);
	$conf['settings']['title'] = $tpl['page']['name']; # Заголовок страницы
	if($tpl['page']['keywords']) $conf['settings']['keywords'] = $tpl['page']['keywords']; # Ключевые слова страницы
	if($tpl['page']['description']) $conf['settings']['description'] = $tpl['page']['description']; # Описание страницы

	if($arg['access'] > 4 && $_GET['sm']){ # Установка заглавной страницы
		$sm = "array://".serialize(array("m"=>array($arg['modpath']=>""), "id"=>$tpl['page']['id']));
		mpqw("UPDATE {$conf['db']['prefix']}settings SET value='$sm' WHERE name='start_mod'");
		header("Location: /");
	}
}else{
	$tpl['cat'] = mpqn(mpqw("SELECT c.*, COUNT(DISTINCT id.id) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON c.id=id.cat_id WHERE 1". ($_GET['cat_id'] ? " AND cat_id=". (int)$_GET['cat_id'] : ""). " GROUP BY c.id"));
	$tpl['pages'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE cat_id IN (". implode(",", array_keys($tpl['cat'] ?: array(0))). ")"), "cat_id", "id");
}

?>