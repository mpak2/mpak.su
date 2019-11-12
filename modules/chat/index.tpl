<? if(!$usr = fk("usr", $w = ["uid"=>$conf["user"]["uid"]], $w+= ["time"=>time()])): mpre("ОШИБКА обновления информации о пользователе") ?>
<? elseif(call_user_func(function(){ // Добавление сообщения
		if(!$_POST){ //mpre("Не запрос");
		}else if(!$text = get($_POST, "text")){ mpre("Сообщение не найдено");
		}else if(!$index = fk("{$arg['modpath']}-index", null, $w = ["name"=>get($_POST,'text')])){ mpre("ОШИБКА добавления сообщения");
		}else{ mpre("Сообщение добавлено", $index);
		}
	})): mpre("ОШИБКА добавления сообщения") ?>
<? elseif(!is_array($USR = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_usr WHERE time>". (time()-$conf['settings']['sess_time'])))): mpre("ОШИБКА получения списка пользователей") ?>
<? elseif(!is_array($INDEX = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY id DESC LIMIT ". (intval(get($_GET,'cnt')) ?: 20)))): mpre("ОШИБКА выборки списка сообщений") ?>
<? elseif(!$uid = qn("SELECT * FROM {$conf['db']['prefix']}users WHERE id IN (". in(rb($USR, "uid")). ") OR id IN (". in(rb($INDEX, "uid")). ")")): mpre("ОШИБКА получения пользователя") ?>
<? elseif(!ksort($INDEX)): mpre("ОШИБКА обратной сортировки списка сообщений") ?>
<? elseif(isset($_GET['chat'])): # Чат ?>
	<div class="table">
		<div>
			<span>
				<div class="table">
					<? foreach($INDEX as $index): //mpre($index); ?>
						<div>
							<span style="width:50px;">
								<nobr>
									<div title="<?=date('d.m.Y', $index['time'])?>"><?=date('Y.m.d H:i', $index['time'])?></div>
								</nobr>
							</span>
							<span style="width:50px; text-align:right;">
								<? if($index['uid'] > 0): ?>
									<nobr>
										<a href="/users/<?=$index['uid']?>">
											<?=(($chat_usr = get($uid, $index['uid'], 'chat_usr')) ? "<b>{$chat_usr}</b>" : $uid[ $index['uid'] ]['name'])?>
										</a>
									</nobr>
								<? else: ?>
									<?=$conf['settings']['default_usr']. $index['uid']?>
								<? endif; ?>
							</span>
							<span style="width:10px">&nbsp;</span>
							<span>
								<? //if($personal = $tpl['usr:personal'][ $index['chat_usr'] ]): ?>
									<span style="font-weight:bold; font-style:italic;"> &raquo;
										<? //if($personal['uid'] > 0): ?>
											<?//=($uid[ $personal['uid'] ]['chat_usr'] ?: $uid[ $personal['uid'] ]['name'])?>
										<? //else: ?>
											<?//=$conf['settings']['default_usr']. $personal['uid']?>
										<? //endif; ?>
									</span>
								<? //endif; ?>
								<?=$index['name']?>
							</span>
						</div>
					<? endforeach; ?>
				</div>
			</span>
			<span class="usr" usr_id="">
				<? foreach($USR as $usr): ?>
					<div usr_id="<?=$usr['id']?>" class="<?=(rb($USR, "uid", "usr_id", $conf['user']['uid'], $usr['id']) ? "active" : "")?>">
						<a class="personal" href="javascript:">
							<? if($usr['uid'] > 0): ?>
								<nobr><?=get($uid, $usr['uid'], 'name')?></nobr>
							<? else: ?>
								<?=$conf['settings']['default_usr']. $usr['uid']?>
							<? endif; ?>
						</a>
					</div>
				<? endforeach; ?>
			</span>
		</div>
	</div>
<? else: ?>
	<div id='chat' style='border:0px solid #000; padding:2px;'></div>
	<style>
		.usr > div { padding-left:20px; }
		.usr > div.active { background-image:url(/<?=$arg['modname']?>:img/w:20/h:20/null/checked.png); background-repeat:no-repeat; }
	</style>
	<form method='post' enctype='multipart/form-data' onsubmit='return false;'>
		<table width=100% border=0>
			<tr>
				<td>
					<input type='text' id='str' style='width: 100%' onKeyPress='if(microsoftKeyPress()) {this.onchange();}' onchange="$.post('/?m[<?=$arg['modpath']?>]', {text: this.value}, function(response){ if(response){ update(false) } } ); this.value='';" <?=($arg['admin_access'] >= 2 ? "" : "disabled")?>>
				</td>
				<td width=1>
					<input type='hidden' id='upl'>
				</td>
				<td width=1>
					<input type='button' value='Добавить' <?=($arg['admin_access'] >= 2 ? "" : "disabled")?>>
				</td>
			</tr>
		</table>
	</form>
	<script type='text/javascript'>
		$(function(){
			$("#chat").on("click", "a.personal", function(){
				var is_active = $(this).parents("div[usr_id]").is(".active");
				console.log("is_active:", is_active);
				var id = $(this).parents("span[usr_id]").attr("usr_id");
				var usr_id = (!is_active ? $(this).parents("div[usr_id]").attr("usr_id") : 0);
				$.post("/<?=$arg['modname']?>:ajax/class:usr", {id:id, usr_id:usr_id}, $.proxy(function(data){
					if(isNaN(data)){ alert(data) }else{
						$(this).parents("span[usr_id]").find("div[usr_id]").removeClass("active");
						if(!is_active) $(this).parents("div[usr_id]").addClass("active");
					}
				}, this));
			});
		})
		$.ajaxSetup({url: '/xmlhttp/',cache:false});
		function update(sto){
			$('#chat').load('/?m[<?=$arg['modpath']?>]&null&chat');
			if(sto) setTimeout("update(true); ",3000);
		} update(true);
		function netscapeKeyPress(e){
			if (e.which == 13) alert('Enter pressed');
		}
		function microsoftKeyPress(){
			if (window.event.keyCode == 13)
			return true;
		}
		if (navigator.appName == 'Netscape') {
			window.captureEvents(Event.KEYPRESS);
			window.onKeyPress = netscapeKeyPress;
		}
	</script>
<? endif; ?>
