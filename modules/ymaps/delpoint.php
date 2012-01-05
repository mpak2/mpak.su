<? die;

mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_point WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['id']);
echo mysql_affected_rows() == 1 ? 'deleted' : 'Ошибка удаления';

?>