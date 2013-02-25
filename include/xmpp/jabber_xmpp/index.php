<?php
/* UTF-8 
XMPP webi
http://webi.ru/webi_files/xmpp_webi.html

*/

include_once("xmpp.class.php");
$webi = new XMPP($webi_conf);

$webi->connect(); // установка соединения...

 $webi->sendStatus('text status','chat',3); // установка статуса
 $webi->sendMessage("asd@asd.ru", "soobshenie"); // отправка сообщения


// так можно зациклить
/*

while($webi->isConnected)
{
	$webi->getXML();
}

*/


?>
