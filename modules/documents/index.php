<? die;

if($_GET['id']){
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
	);
	$file = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE id=". (int)$_GET['id']), 0);
	mpfile($file['document'], $file['name']); //	mpre($file);
}

$conf['tpl']['index'] = mpql(mpqw($sql = "SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). mpwr($tn). " ORDER BY cat_id"));
$conf['tpl']['cat'] = spisok("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat");

//$conf['tpl']['type_id'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_type");
//$conf['tpl']['cnt'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt');
//$conf['tpl']['comments'] = spisok("SELECT u.num, COUNT(*) AS cnt FROM {$conf['db']['prefix']}comments_url AS u, {$conf['db']['prefix']}comments_txt AS t WHERE u.id=t.uid AND u.url LIKE '%/{$arg['modpath']}/%' GROUP BY u.id");

?>