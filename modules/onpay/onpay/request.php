<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title>Р¤РѕСЂРјР° СЃРѕР·РґР°РЅРёСЏ Р·Р°СЏРІРєРё</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
<?
include 'onpay_functions.php';
include 'my_functions.php';

$db_link = db_connect();
echo process_first_step();
db_disconnect($db_link);
?>
  </body>
</html>