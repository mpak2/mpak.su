<? die;

$tn = $conf['db']['prefix']. $arg['modpath']. substr($arg['fn'], strlen("admin_interface"));
header("location: /?m[{$arg['modpath']}]=admin&r=". $tn); exit;

?>