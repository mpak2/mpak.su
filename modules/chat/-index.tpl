<? if (isset($_GET['chat'])): # Чат ?>
	<table border=0 width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing=0 cellpadding=0 border=0>
		<? foreach($tpl['index'] as $index): ?>
			<tr valign=top>
				<td width=1>
					<nobr>
						<div title="<?=date('d.m.Y', $index['time'])?>"><?=date('H:i', $index['time'])?></div>
					</nobr>
				</td>
				<td width=10>&nbsp;</td>
				<td width=1>
					<?=($index['uid'] > 0 ? "<nobr><a href=\"/users/{$index['uid']}\">". (/*$tpl['uid'][ $index['uid'] ]['chat_usr'] ?:*/ $tpl['uid'][ $index['uid'] ]['name']). "</a></nobr>" : $conf['settings']['default_usr']. $index['uid'])?>
				</td>
				<td width=10>&nbsp;</td>
				<td><?=htmlspecialchars($index['text'])?>
				</td>
			</tr>
		<? endforeach; ?>
	</table></td><td valign=top><table width=100% cellspacing=0 cellpadding=0 border=0>
	<? foreach($tpl['usr'] as $usr): ?>
		<tr align=right>
			<td><?=($usr['uid'] > 0 ? "<a href=\"/users/{$usr['uid']}\"><nobr>". (/*$tpl['uid'][ $usr['uid'] ]['chat_usr'] ?:*/ $tpl['uid'][ $usr['uid'] ]['name']). "</nobr></a>" : "гость". $usr['uid'])?></td>
		</tr>
	<? endforeach; ?>
	</table></td></tr></table>
<? else: ?>
	<div id='chat' style='border:0px solid #000; padding:2px;'></div>
	<form method='post' enctype='multipart/form-data' onsubmit='return false;'>
		<table width=100% border=0>
			<tr>
				<td>
					<input type='text' id='str' style='width: 100%' onKeyPress='if(microsoftKeyPress()) {this.onchange();}' onchange="$.post('/?m[<?=$arg['modpath']?>]', {text: this.value}, function(response){ if(response){ update(false) } } ); this.value='';" <?=($arg['access'] >= 2 ? "" : "disabled")?>>
				</td>
				<td width=1>
					<input type='hidden' id='upl'>
				</td>
				<td width=1>
					<input type='button' value='Добавить' <?=($arg['access'] >= 2 ? "" : "disabled")?>>
				</td>
			</tr>
		</table>
	</form>
	<script type='text/javascript'>
		$.ajaxSetup({url: '/xmlhttp/',cache:false});
		function update(sto){
			$('#chat').load('/?m[<?=$arg['modpath']?>]&null&chat');
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