<? # Чат

if(array_key_exists('confnum', $arg)){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

?>
<? if($_SERVER['REQUEST_URI'] != "/{$arg['modpath']}"): ?>
	<div id='chat' style='border:0px solid #000; padding:2px;'></div>
	<div style="text-align:right; margin-top:-10px;"><a href="/chat">Полный экран</a></div>
	<div style="clear:both;">
		<form method='post' enctype='multipart/form-data' onsubmit='return false;'>
			<table width=100% border=0>
				<tr>
					<td>
						<input type='text' id='str' style='width: 100%' onKeyPress='if(microsoftKeyPress()) {this.onchange();}' onchange="$.post('/?m[<?=$arg['modpath']?>]', {text: this.value}, function(response){ if(response){ update(false) } } ); this.value='';" <?=($arg['admin_access'] > 2 ? "" : "disabled")?>>
					</td>
					<td width=1>
						<input type='button' value="Добавить" <?=($arg['admin_access'] > 2 ? "" : "disabled")?>>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<script type='text/javascript'>
		$.ajaxSetup({url: '/xmlhttp/',cache:false});
		function update(sto){
			$('#chat').load('/?m[<?=$arg['modpath']?>]&null&cnt=6&chat');
			if (sto) setTimeout("update(true); ",3000);
		} update(true);
		function netscapeKeyPress(e) { if (e.which == 13) alert('Enter pressed');}
		function microsoftKeyPress() { if (window.event.keyCode == 13) return true; }
		if (navigator.appName == 'Netscape') {
			window.captureEvents(Event.KEYPRESS);
			window.onKeyPress = netscapeKeyPress;
		}
	</script>
<? endif; ?>
