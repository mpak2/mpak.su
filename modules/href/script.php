<? die;

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_stat SET name=\"". mpquot($_SERVER['HTTP_REFERER']). "\", count=1, time=". time(). " ON DUPLICATE KEY UPDATE count=count+1, time=". time());

$conf['tpl']['cat'] = mpqn(mpqw("SELECT c.*, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON c.id=id.cat_id WHERE c.cat_id>0 GROUP BY c.id ORDER BY c.sort"), 'cat_id', 'id');
$conf['tpl']['top'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat WHERE id IN (". implode(', ', array_keys($conf['tpl']['cat'])). ") ORDER BY sort"), 'id'); 
$conf['tpl']['href'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"), 'cat_id', 'id');

header("Content-type: application/x-javascript");

?>