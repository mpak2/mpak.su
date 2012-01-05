<? die;

$mod = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE id=".(int)$_GET['id']), 0);
$file_name = mpopendir("modules/{$mod['folder']}/img/admin.png");
if (empty($file_name)) $file_name = "img/admin.png";
header ("Content-type: image/". array_pop(explode('.', $file_name)));
header("Cache-Control: public");
header("Expires: ". date("r", time() + 24*3600)); 
echo mprs($file_name);

?>