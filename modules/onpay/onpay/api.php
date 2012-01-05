<?
include 'onpay_functions.php';
include 'my_functions.php';

$db_link = db_connect();
echo process_api_request();
db_disconnect($db_link);
?>