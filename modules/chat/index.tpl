<? if (isset($_GET['chat'])): # Чат ?>
	<table border=0 width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing=0 cellpadding=0 border=0>
		<? foreach($conf['tpl']['data'] as $k=>$v): ?>
			<tr valign=top>
				<td width=1>
					<nobr>
						<div title="<?=date('d.m.Y', $v['time'])?>"><?=date('H:i', $v['time'])?></div>
					</nobr>
				</td>
				<td width=10>&nbsp;</td>
				<td width=1>
					<?=($v['uid'] > 0 ? "<nobr><a href=\"/users/{$v['uid']}\">{$v['name']}</a></nobr>" : $conf['settings']['default_usr']. "_".abs($v['uid']))?>
				</td>
				<td width=10>&nbsp;</td>
				<td><?=htmlspecialchars($v['text'])?>
				</td>
			</tr>
<!--			<div style="margin-top:5px;">
				<span title="<?=date('d.m.Y', $v['time'])?>" style="font-weight:bold;"><?=date('H:i', $v['time'])?></span>
				<?=($v['uid'] > 0 ? "<nobr><a href=\"/users/{$v['uid']}\">{$v['name']}</a></nobr>" : $conf['settings']['default_usr']. "_".abs($v['uid']))?>
				<?=htmlspecialchars($v['text'])?>
			</div> -->
		<? endforeach; ?>
	</table></td><td valign=top><table width=100% cellspacing=0 cellpadding=0 border=0>
	<? foreach($conf['tpl']['users'] as $k=>$v): ?>
		<tr align=right>
			<td>
				<?=($k > 0 ? "<a href=\"/users/{$k}\"><nobr>$v</nobr></a>" : "гость_". abs($k))?>
			</td>	
		</tr>
	<? endforeach; ?>
	</table></td></tr></table>
<? else: ?>
	<div id='chat' style='border:0px solid #000; padding:2px;'></div>
	<form method='post' enctype='multipart/form-data' onsubmit='return false;'>
		<table width=100% border=0>
			<tr>
				<td>
					<input type='text' id='str' style='width: 100%' onKeyPress='if(microsoftKeyPress()) {this.onchange();}' onchange="$.post('/?m[<?=$arg['modpath']?>]', {text: this.value}, function(response){ if(response){ update(false) } } ); this.value='';">
				</td>
				<td width=1>
					<input type='hidden' id='upl'>
				</td>
				<td width=1>
					<input type='button' value='Добавить'>
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