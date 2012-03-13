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

if($_FILES || array_key_exists("debug", $_GET)){
//	mpre(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_files"), 0));
}elseif($_GET['id'] || $_GET['cat_id']){
	$img = array('jpg', 'jpeg', 'gif', 'png');
	$defaultmimes = array(
		'aif' => 'audio/x-aiff',
		'aiff' => 'audio/x-aiff',
		'arc' => 'application/octet-stream',
		'arj' => 'application/octet-stream',
		'art' => 'image/x-jg',
		'asf' => 'video/x-ms-asf',
		'asx' => 'video/x-ms-asf',
		'avi' => 'video/avi',
		'bin' => 'application/octet-stream',
		'bm' => 'image/bmp',
		'bmp' => 'image/bmp',
		'bz2' => 'application/x-bzip2',
		'css' => 'text/css',
		'doc' => 'application/msword',
		'dot' => 'application/msword',
		'dv' => 'video/x-dv',
		'dvi' => 'application/x-dvi',
		'eps' => 'application/postscript',
		'exe' => 'application/octet-stream',
		'gif' => 'image/gif',
		'gz' => 'application/x-gzip',
		'gzip' => 'application/x-gzip',
		'htm' => 'text/html',
		'html' => 'text/html',
		'ico' => 'image/x-icon',
		'jpe' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'js' => 'application/x-javascript',
		'log' => 'text/plain',
		'mid' => 'audio/x-midi',
		'mov' => 'video/quicktime',
		'mp2' => 'audio/mpeg',
		'mp3' => 'audio/mpeg3',
		'mpg' => 'audio/mpeg',
		'pdf' => 'aplication/pdf',
		'png' => 'image/png',
		'rtf' => 'application/rtf',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'txt' => 'text/plain',
		'xml' => 'text/xml',
		'swf'=>'application/x-shockwave-flash',
	);
	if($v = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE activ=1". ($_GET['cat_id'] ? " AND cat_id=". (int)$_GET['cat_id']. " ORDER BY RAND() LIMIT 1" : ""). ($_GET['id'] ? " AND id=".(int)$_GET['id'] : "")), 0)){
			mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_files SET count=count+1 WHERE id=".(int)$v['id']);
			$ext = strtolower(array_pop(explode('.', $v['name'])));
			header("Content-Type: ". ($defaultmimes[$ext] ? $defaultmimes[$ext] : "text/$ext"));
			if(array_search($ext, $img) !== false){
				echo mprs(mpopendir("include/". $v['name']), $_GET['w'] ?: $v['w'], $_GET['h'] ?: $v['h'], isset($_GET['c']) ? $_GET['c'] : $v['c']);
			}else{
				readfile(mpopendir("include/". $v['name']));
			}
	} exit;
}


$tpl['cat'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"));
$tpl['files'] = mpqn(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_files"). mpwr($tn). " AND uid=". (int)$conf['users']['uid']), 'cat_id', 'id');

//mpre($tpl['files']);

?>