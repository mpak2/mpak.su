<? die;

require_once(mpopendir('include/idna_convert.class.inc'));
$IDN = new idna_convert();

if ($_REQUEST['decode'] && $_POST['submit'] == '<<<<') {
	$_REQUEST['encode'] = iconv('utf-8', 'cp1251', $IDN->decode($_REQUEST['decode']));
}elseif ($_REQUEST['encode'] && $_POST['submit'] == '>>>>') {
	$_REQUEST['decode'] = $IDN->encode($_REQUEST['encode']);
}else{
	$_REQUEST['encode'] = 'закрытый.рф';
}

?>