<?

include "/srv/www/vhosts/mpak.cms/include/parse/simple_html_dom.php";
include "/srv/www/vhosts/mpak.cms/include/mpfunc.php";
include "/srv/www/vhosts/mpak.cms/include/func.php";
//require_once "/srv/www/vhosts/mpak.cms/include/idna_convert.class.inc";

include __DIR__. "/../../../config/config.php";
$html = new simple_html_dom();

$conf['db']['conn'] = mysql_connect($conf['db']['host'], $conf['db']['login'], $conf['db']['pass']); # Соединение с базой данных

$arg['modpath'] = basename(dirname(dirname(__FILE__)));
$conf['fs']['path'] = dirname(dirname(dirname(dirname(__FILE__))));
chdir(dirname(__FILE__)); # Изменяем текущую директорию для запуска из крона

if (strlen($conf['db']['error'] = mysql_error())){
#		echo "Ошибка соединения с базой данных<p>";
}else{
	mysql_select_db($conf['db']['name'], $conf['db']['conn']);
	mpqw("SET NAMES 'utf8'");
} unset($conf['db']['pass']); $conf['db']['sql'] = array();

###################################################################################################################################################

include "/srv/www/vhosts/mpak.cms/include/class/jsonrpcphp/jsonRPCClient.php";

$bitcoind = new jsonRPCClient('http://bitcoinrpc:616gPjeDkRxPxXbtsYzBJZMez3dJDkRxPxXbtsYzLzf@localhost:8332/');
mpre($bitcoind->getinfo());

$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE status_id=0");

$tpl['address_index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_address_index WHERE index_id IN (". in($tpl['index']). ")");
$tpl['index_address'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index_address WHERE index_id IN (". in($tpl['index']). ")");

$tpl['address'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_address
	WHERE id IN (". in(rb($tpl['address_index'], "address_id")). ")
	OR id IN (". in(rb($tpl['index_address'], "address_id")). ")"
);// mpre($tpl['address']);

foreach($tpl['index'] as $index){
	if((count($address_index = rb($tpl['address_index'], "index_id", "id", $index['id'])) == 1) && ($fromaccount = $tpl['address'][ array_shift(array_column($address_index, "address_id")) ])){
		if((count($index_address = rb($tpl['index_address'], "index_id", "id", $index['id'])) == 1) && ($tobitcoinaddress = $tpl['address'][ array_shift(array_column($index_address, "address_id")) ])){
//			$index_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_index", array("id"=>$index['id']), null, array("status_id"=>1));
//			$tx = $bitcoind->sendtoaddress($fromaccount['name'], $tobitcoinaddress['name'], (float)array_shift(array_column($index_address, "name")));
//			$tx = $bitcoind->sendmany($fromaccount['name'], json_encode(array($tobitcoinaddress['name']=>array_shift(array_column($index_address, "name")))));

			$cmd = "bitcoind sendfrom {$fromaccount['name']} {$tobitcoinaddress['name']} ". array_shift(array_column($index_address, "name")). "\n";
			echo $tx = `$cmd`;
			$index_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_index", array("id"=>$index['id']), null, array("name"=>$tx));
		}else{
			echo "sendmany <fromaccount> {address:amount,…}\n";
		}
	}
}