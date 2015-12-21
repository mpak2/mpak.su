<? # Чат

if ((int)$arg['confnum']){
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
	<div id='chat' style='border:0px solid #000; padding:2px 0;'></div>
	<div style="text-align:right; margin-top:-10px;"><a href="/chat:all">Полный экран</a></div>
	<div style="clear:both;">
		<form method='post' enctype='multipart/form-data' onsubmit='return false;'>
			<table width=100% border=0>
				<tr>
					<td>
						<input type='text' id='str' style='width: 100%' onKeyPress='if(microsoftKeyPress()) {this.onchange();}' onchange="$.post('/?m[<?=$arg['modpath']?>]=all', {uname:'<?=$conf['user']['uname']?>', host:'<?=$_SERVER['HTTP_HOST']?>', usr:'<?=$conf['user']['uid']?>', text: this.value}, function(response){ if(response){ update(false) } } ); this.value='';">
					</td>
					<td width=1>
						<input type='hidden' id='upl'>
					</td>
					<td width=1>
						<input type='button' value="Добавить">
					</td>
				</tr>
			</table>
		</form>
	</div>
	<script type='text/javascript'>
		$.ajaxSetup({url: '/xmlhttp/',cache:false});
		function update(sto){
			$('#chat').load('/?m[<?=$arg['modpath']?>]=all&usr=<?=$conf['user']['uid']?>&uname=<?=$conf['user']['uname']?><?=($conf['user']['uid'] < 0 ? "_{$conf[user]['sess']['id']}" : '')?>&host=<?=$_SERVER['HTTP_HOST']?>&null&cnt=7&chat');
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
