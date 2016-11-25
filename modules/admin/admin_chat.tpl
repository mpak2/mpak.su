<h1>АдминЧат</h1>
<? if(!$SESS = qn("SELECT * FROM `{$conf['db']['prefix']}sess` WHERE `last_time`>". (time()-abs(get($conf, 'settings', 'sess_time'))))): mpre("Активные сессии не найдены") ?>
<? elseif($_POST && !(call_user_func(function(){
		return fk("admin-chat", null, $_POST);
	}, $admin_chat))): mpre("Ошибка добавления сообщения") ?>
<? elseif(!($ADMIN_CHAT = rb("admin-chat", 40)) &0): mpre("Сообщений не найдено") ?>
<? elseif(!$USERS = rb("{$conf['db']['prefix']}users", "id", "id", rb($ADMIN_CHAT, 'uid')+rb($SESS, "uid"))): mpre("Пользователи с активными сессиями не найдены") ?>
<? else: ksort($ADMIN_CHAT) ?>
	<script sync>
		(function($, script){
			$(script).parent().on("click", "button", function(e){
				$(e.delegateTarget).find("form").trigger("submit");
			}).on("submit", "form", function(e){
				var text = $(e.currentTarget).find("input[type=text]").val();
				$(e.delegateTarget).find("input[type=hidden]").attr("value", text);
				$(e.delegateTarget).find("input[type=text]").val("");
			}).one("init", function(e){
				$(e.delegateTarget).find("input[type=text]").focus();
				var forms = $(e.delegateTarget).find("form").attr("target", "response_"+(timeStamp = e.timeStamp));
				$("<"+"iframe>").attr("name", "response_"+timeStamp).appendTo(forms).load(function(){
					var chat = $(this).contents().find("body").find(".chat").html();
					$(e.delegateTarget).find(".chat").html(chat);
					$(e.delegateTarget).find("input[type=text]").focus();
				}).hide();
				setInterval(function(){
					$.get(document.location.href+"/null", function(response){
						var chat = $("<"+"div>").html(response).find(".chat").html()
						$(e.delegateTarget).find(".chat").html(chat);
					})
				}, 2000);
			}).ready(function(e){ $(script).parent().trigger("init"); })
		})(jQuery, document.currentScript)
	</script>
	<style>
		.admin_chat ul {font-weight:bold;}
		.admin_chat .chat .time { width:1%; color:gray; }
	</style>
	<div class="table admin_chat">
		<div>
			<span class="chat">
				<div class="table">
					<? foreach($ADMIN_CHAT as $admin_chat): ?>
					<div>
						<span><?=$admin_chat['name']?></span>
						<span><?=rb($USERS, "id", $admin_chat['uid'], "name")?></span>
						<span class="time"><?=strtr(date("m.d H:i:s", $admin_chat['time']), [' '=>'&nbsp;'])?></span>
					</div>
					<? endforeach; ?>
				</div>
			</span>
			<span style="width:1%;">
				<ul>
					<? foreach($USERS as $users): ?>
						<li><?=$users['name']?></li>
					<? endforeach; ?>
				</ul>
			</span>
		</div>
		<div>
			<span>
				<form method="post" >
					<input type="text" name="name" style="width:100%;" placeholder="Сообщение">
					<input type="hidden" name="name">
				</form>
			</span>
			<span>
				<button style="margin-left:15px;">Отправить</button>
			</span>
		</div>
	</div>
<? endif; ?>