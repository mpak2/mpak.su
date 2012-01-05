<? if (isset($_GET['chat'])): # Чат ?>
	<table border=0 width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing=0 cellpadding=0 border=0>
		<? foreach($conf['tpl']['data'] as $k=>$v): ?>
<!--			<tr valign=top>
				<td width=1>
					<nobr>
						<div title="<?=date('d.m.Y', $v['time'])?>"><?=date('H:i', $v['time'])?></div>
					</nobr>
				</td>
				<td width=10>&nbsp;</td>
				<td width=1>
					<?=($v['uid'] > 0 ? "<nobr><a href=\"/{$conf['modules']['users']['modname']}/{$v['uid']}\">{$v['name']}</a></nobr>" : $conf['settings']['default_usr']. "_".abs($v['uid']))?>
				</td>
				<td width=10>&nbsp;</td>
				<td><?=htmlspecialchars($v['text'])?>
				</td>
			</tr> -->
			<div style="margin-top:5px;">
				<span title="<?=date('d.m.Y', $v['time'])?>" style="font-weight:bold;"><?=date('H:i', $v['time'])?></span>
				<?="<span><a target=_blank href=\"http://{$v['host']}/{$conf['modules']['users']['modname']}/{$v['usr']}\">{$v['uname']}</a>@<a target=_blank href=\"http://{$v['host']}\">{$v['host']}</a></span>"?>
				<?=$v['text']?>
			</div>
		<? endforeach; ?>
	</table></td><td valign=top><table width=100% cellspacing=0 cellpadding=0 border=0>
	<? foreach($conf['tpl']['users'] as $k=>$v): ?>
		<tr align=right>
			<td>
				<?="<a href=\"http://{$v['host']}/{$conf['modules']['users']['modname']}/{$v['usr']}\"". (($v['uid'] == $conf['user']['uid']) && ($v['host'] == $_GET['host']) && ($v['uname'] == $_GET['uname']) ? " style=\"font-weight:bold;\"" : ""). "><nobr>{$v['uname']}</nobr></a>"?>
			</td>	
		</tr>
	<? endforeach; ?>
	</table></td></tr></table>
<? else: ?>
	<? if($conf['settings']["{$arg['modpath']}_title"]): ?>
	<div style="padding:5px; text-align:center; font-weight:bold; border-bottom:1px dashed #444; color:#C90000;"><!-- [settings:<?=$arg['modpath']?>_title] --></div>
	<? endif; ?>
	<div id='chat' style='border:0px solid #000; padding:2px;'></div>
	<form method='post' enctype='multipart/form-data' onsubmit='return false;'>
		<table width=100% border=0>
			<tr>
				<td>
					<input type='text' id='str' style='width: 100%' onKeyPress='if(microsoftKeyPress()) {this.onchange();}' onchange="$.post('/?m[<?=$arg['modpath']?>]=all', {uname:'<?=$conf['user']['uname']?>', host:'<?=$_SERVER['HTTP_HOST']?>', usr:'<?=$conf['user']['uid']?>', text: this.value}, function(response){ if(response){ update(false) } } ); this.value='';" <?=($conf['user']['uid']<0 ? "disabled" : "")?>>
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
			$('#chat').load('/?m[<?=$arg['modpath']?>]=all&usr=<?=$conf['user']['uid']?>&uname=<?=$conf['user']['uname']?><?=($conf['user']['uid'] < 0 ? "_{$conf[user]['sess']['id']}" : '')?>&host=<?=$_SERVER['HTTP_HOST']?>&null&chat');
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