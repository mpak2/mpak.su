<?
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

if($_FILES && ($arg['admin_access'] > 1)){
	mpre($_FILES);
}elseif($_GET['id'] || $_GET['cat_id']){
	$img = array('jpg', 'jpeg', 'gif', 'png');
	if($v = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE activ=1". ($_GET['cat_id'] ? " AND cat_id=". (int)$_GET['cat_id']. " ORDER BY RAND() LIMIT 1" : ""). ($_GET['id'] ? " AND id=".(int)$_GET['id'] : "")), 0)){
			include_once(mpopendir('modules/files/defaultmimes.php'));
			mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_files SET count=count+1 WHERE id=".(int)$v['id']);
			$ext = strtolower(array_pop(explode('.', $v['name'])));
			if($v['description']){
				header("Content-Disposition: attachment; filename=\"".$v['description'].".{$ext}\"");
			} header("Content-Type: ". ($conf['defaultmimes'][$ext] ? $conf['defaultmimes'][$ext] : "application/$ext"));
			if(array_search($ext, $img) !== false){
				echo mprs(mpopendir("include/". $v['name']), $_GET['w'] ? $_GET['w'] : $v['w'], $_GET['h'] ? $_GET['h'] : $v['h'], isset($_GET['c']) ? $_GET['c'] : $v['c']);
			}else{
				readfile(mpopendir("include/". $v['name']));
			}
	} exit;
}


$tpl['cat'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"));
$tpl['files'] = mpqn(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_files"). mpwr($tn). " AND ". ($arg['admin_access'] <= 3 ? "uid=". (int)$conf['users']['uid'] : "1")), 'cat_id', 'id');

//mpre($tpl['files']);

?>
