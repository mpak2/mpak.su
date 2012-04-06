<? die;

$tn = $conf['db']['prefix']. $arg['modpath']. substr($arg['fn'], strlen("admin_interface"));
header("location: /?m[users]=admin&r=". $tn); exit;

?>