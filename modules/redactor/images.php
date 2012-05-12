<? die;

//$tpl['images'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));

$tpl['images'] = mpql(mpqw("SELECT CONCAT('/redactor:img/', id, '/tn:index/fn:file/w:50/h:50/c:1/null/img.jpg') AS thumb, CONCAT('/redactor:img/', id, '/tn:file/fn:img/w:250/h:250/null/img.jpg') AS image FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));

?>