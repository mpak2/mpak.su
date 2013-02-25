<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		"Количество символов"=>0,
		"Курс доллара"=>30,
		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
	);

?>
		<!-- Настройки блока -->
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
		$(function(){
			<? foreach($klesh as $k=>$v): ?>
				<? if(gettype($v) == 'array'): ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>", function(){
					}, <?=json_encode($v)?>);
				<? else: ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>");
				<? endif; ?>
			<? endforeach; ?>
		});
	</script>
	<div style="margin-top:10px;">
		<? foreach($klesh as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div style="width:200px; float:left; padding:5px; text-align:right; font-weight:bold;"><?=$k?> :</div>
				<? if(gettype($v) == 'array'): ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=$v[ $param[$k] ]?></div>
				<? else: ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=($param[$k] ?: $v)?></div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? return;

}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];


mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']}_projects_works SET duration=3600*4 WHERE duration=0 AND time+3600*4<". (int)time());

$worker = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_workers WHERE uid=". (int)($_POST['uid'] && ($arg['access'] >= 3) ? $_POST['uid'] : $conf['user']['uid'])), 0);

$projects_works = mpqn(mpqw("SELECT pw.*, t.name AS tasks_name FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works AS pw LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks AS t ON (pw.tasks_id=t.id) WHERE pw.uid=". (int)$worker['uid']. " ORDER BY pw.id DESC LIMIT 3"));

ksort($projects_works);
$projects_work = array_pop($tmp = $projects_works);

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && array_diff_key($_POST, array_flip(array("uid")))){
	if($_POST['check']){
		$projects_works_id =  mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_projects_works",
			array("id"=>$_POST['check']), null, array("duration"=>(time()-$projects_work['time']))
		);// exit($projects_works_id);
	}else{ # Редактирование учета времени включение выключение
		if($_POST['routine'] == "on"){
			if(empty($projects_work) || $projects_work['duration']){
				$pw = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works WHERE uid=". ($arg['access'] > 3 ? $_POST['uid'] : $conf['user']['uid']). " ORDER BY id DESC LIMIT 1"), 0);
				if(($c = (time()-$pw['time']+$pw['duration'])/60) < $_POST['current']){
					exit("Пересечение с предыдущей задачей (Макс: ". number_format($c, 2). ")" );
				}else{
					$current_time = time()-$_POST['current']*60;
				}// exit("Не найдено открытых задачь");
			}else if((time() - $projects_work['time']) < ($_POST['current']*60)){
				exit("Время предыдущей задачи меньше времени отката");
			}else{# Установка времени в задачу
				mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_projects_works SET duration=". (time()-$projects_work['time']-$_POST['current']*60). " WHERE id=". (int)$projects_work['id']);

				$projects_works[ $projects_work['id'] ]['duration'] = time() - $projects_works[ $projects_work['id'] ]['time'] - $_POST['current']*60;
				$current_time = time()-$_POST['current']*60;
			}
		} mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_projects_works SET duration=". (int)time(). "-time WHERE duration=0 AND uid=". (int)$_POST['uid']); # Закрытие всех открытых задач

		$time = ($current_time ?: time());
		$period_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_period", $w = array("year"=>date("Y", $time), "month"=>date("m", $time), "week"=>date("W", $time)), $w);
		$projects_work_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_projects_works",
			null, array("time"=>$time, "period_id"=>$period_id, "uid"=>($arg['access'] > 3 ? $_POST['uid'] : $conf['user']['uid']))+$_POST
		);
		if(count($projects_works) >= 3) $projects_works = array_slice($projects_works, 1);
		$projects_works += array($projects_work_id=>array("id"=>$projects_work_id, "time"=>($current_time ?: time()))+$_POST);
	}
};

$workers = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_workers"), 'uid');

$works = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_works"));

$tasks = mpqn(mpqw("SELECT t.* FROM {$conf['db']['prefix']}{$arg['modpath']}_tasks AS t INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks_status AS ts ON (t.tasks_status_id=ts.id) WHERE ts.hide=0 ORDER BY t.id DESC"));// mpre($tasks);

$projects = array(0=>array("id"=>0, "name"=>""))+mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_projects ORDER BY up DESC"));

$check = "data:image/jpeg;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADzElEQVQ4ja2VXWgcVRTH/+fO7OzsTvOxm7SuzSZZI22a2Fq1BNY2pFEpCIpViKVWBe1K8aN9UFTEB8EXH3wpok+CeZFK2bq0CF1pqdqIDUVrjUlqFG2TNKaJzewm2ezn7Mw9PmR33W6gT144MPcM9z+/e+65/yFmxv851PJDxs4gWTABAMwMIgIRVebMDNR8mxkAA4ZqoNlYvyrIzAIAzs6exuGLETAYBSsPTdNgGAaICIVCAdlsFnbGBgsACgACOId7RUpVn2ofuDy4/wsAgCAieZsdrHlHgCQAxBS+v6Hn60j3y2cuTf7YNzbzK4gIomahrInaHBgQAIW73NtiYfeuQH4l70+nM7HHPtjTNzo5sipYqtXtSKsJw1vcW2M7vX2BxWISZ66dxjIt+VVHOzHw/pNt5UMpk3oBtgDYVXSiSi/c6bknttPXG0g5SzifOoeFhnkEG9vhz9xxrqWpNSmYGYmciY/GPgx16JuHPOT9GIBeW0ciCm/ydcUebO8NrBgpDFtDWGlYQj01InFh+bhXN458+sZnaZHImWLfV4+HMrnMydc3v/3AwfZXD6nQjhLIW6ITBApv8XXH+u9+OGDrFsasy7ANCw3ZJix+kz4e9AePnHjvVLLOWw/l544fWjp9Xade6H5puyQJw+3FxrrWHRMr482qqnwnhLKjfd1dsd6W/kBRsXClMIq8Kwcxp+H6udmok7cP7972UHJ//wEQkRS/z0xsYIuCn18fxCIlYLkshPwh7Nv07CEXu4/tbRs4ubvtkQDrElfxB7BOQl3SMTk2HZWN9msAkgBkuf3E0b2fjF6cuBDJTuXNY3ODmHGmwBoj6G/Fc50Hn/A1+jbAw5hWrgF1DEqrmLw6FRWtTlmsPAQAIZ6+74D91p534yN//RK5MX7THM4P4Tc5Aul24Pa7QB7GrDYNUQewpeDv+dmoN+SqkFX6k1kysxQA8EzP8/LNR9+J/5OZi0z/dMNMaCYm1FHk9Qzm9VmIOkIhZ2MuMRf1t9RXk4naECUTkKGmDglFxpddZuTKt38mXR4VpucmdK+O4jJjcXkpGtwYqCW75TYRkaxuWgnAJhfiti/74qWz42YLt0EuKHClPV9u79haLYaadRVnKgv+h02Qii7iWoAj358fTs6kZqLhjp5XCLTmAKqiklNLBivBENVbcBtaXG+jXXc2r58vopiqIVtz75l5lVTK1fbJFrMwcwsQKkEIAUVRVkMocEFDkS3Y0objOHAcB9KW4CIAG/DqBprrmwEAVP4FlI223KClucStnikqJKWa1eb+BYQRtP/naFqTAAAAAElFTkSuQmCC";
$stop = "data:image/jpeg;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAEzUlEQVQ4jX2UW2xURRjH/2fm3LZ7305LaZe2VOlCoSsNosjloQoiEIIEb6lJTWzUhEB8MJEnoWIUY3zSB2MMRkgEw4MhXAqBcou13AoVKIWilQLb0stuS2F3z2XPmfHBbgFDnGQyk28mv5lv8s1P6uzshGma+J9GnhDjT9qo6zqkWCyGnp6exxYkgMyfWrJ45aKFr8dnzHjWFwgGuG1jZGhw+Hx3d/v+8x27b2ay3f8Fx2IxSLFYjDwKrCmMxLd98P53dYsWLxAuB3ccCMd5OOZycDMZ3tJ6ZO8Xhw5/OJpzBh4FUsaYSKVSEgCsqZn5xvZt2/aXTa+q5LYN17LgWha4aYLn55YF7rpSVXTarBXVTzecu369PWXZCQBgjIEyxpBKpUR9ZcWKb7d8uoeqqsonIK5twxi86/z9542B8eGhrFeSCrjrSnmwrum++ukVa1q7ug6kXTfJGOMyAIRlufirjRt3QAjZzWQm07tyuu2vxp0/rzaBBAAECSl9Mz7nvXfrX9ygarqOXA4hj5dtXrJo+/rWE/UATMoYw9vxOc0L5j+/NH+yO5EiK2SRiGUEfrt9Z58AspYQYxcGh1rPXutuX15Zvk6FpMK2MdXnj169dfOK4fVdo6VFRfpHLy/7UdV0n2ua4NnMvw9vWRB2DrPKK56ZYpvlpxIDLQAcAGLYsm6NDg8l6yunr4ZlQeRy0NxcsDPn7CbVfm9NIBQpcTIZONksWk6eOPlry4E93DAgDAMia2DtgoWNW+bN/YEAer4u9/Xf/Wl04E6CmCaoaWJeMPScDARINBypzhkGnGwWrmHgXG/vic/a2t/Zd/zoLhgGJNOEZJh4rW5eY3N89vcSoE5UtnMx0d9GJ4CFhITClERlWaYFbjYL7roQjoPxTHqYA+bWsx1Nkm3j1fjcBmckCZ5KYZUrGhHyyc330k0cMMeTI0mheSCpKmQqQZckn2xks/ecbHayeIs8BWUAiADMrZ2Xm5zeXiyXaINCZSi6jjWFxQ1E07B5KNVUQmmplE6D2zYcN8fTBd77NBIIiCWMbXAtG9yyoNqmfigxsAOAKwCnzcq1lKukqiYQrFX9fqh+P2qLimvLFVS/VFK6VC/w6kRRMJizRvZK9EuqhsL36oT7VoGsFNojI/B0dZX1OXZXPxfXAEgCcE/ZzsFpulIVL57yEFpSNtvj8+tUVSFRipbk8NHLiraLFjKG0f47ymxCl6fOnAGxbbygyMtuQHQMcXFz4pvmTmaMlqhGq+qmldeq/gBUnw+yxwOiKBDC5Zu6rn4sQuEeyhgTp/tuXaobGVzrddwihRB4JKKv0tSGqEIqssCYDRgBQvymEOll06Ir/YVFqhoIQPZ4IFGKnV2X9u8eGPqaMWbn9UXKCeLNMj0WAI3olCLfC7wF0EJBrodCRIswaOEw9HAYWjAIAPj9j44bKw8eXmFw0ReLxSblyW9zXP7c4avuU5F4zKQCECBESPRhUAgIznHk0oWLaw8dWWdw0TfhRp63DQGAMYH+Yy7/xUul4FOUztQkolBCIGsaqKqCqCqoTJEYSyY/OX70m01nOtYbXNzOe5kxJj1q7MnbAiA+CdGFqvxKjaLMj3g8jGg6khCDHQ8enD47nm7NAcMTf5vkzR2LxfAPHbJgjybTFREAAAAASUVORK5CYII=";

?>
<div class="coast <?=$arg['blocknum']?>">
	<div>
		<span style="float:right;"><?=$conf['settings']['domens_users_balance_up']?></span>
		Баланс: <b><?=$conf['settings']['domens_users_balance']?></b>
	</div>
	<div style="overflow:hidden; padding-bottom:5px;">
		<? if($arg['access'] > 3): ?>
			<? if(!array_key_exists("null", $_GET)): ?>
				<script>
					$(function(){
						$(".workers select").on("change", function(){ // Изменение выпадающего списка исполнителей
							uid = $(this).find("option:selected").val();
							$(".coast.<?=$arg['blocknum']?> form input[type=hidden]").attr("value", uid);
							$.post("/blocks/<?=$arg['blocknum']?>/null", {"uid":uid}, function(data){
								if(html = $("<div>").html(data).find(".projects_works").html()){
									$(".projects_works").html(html);
								}else{ alert(data) }
							});
						}).change();
					});
				</script>
			<? endif; ?>
			<div class="workers">
				<select>
					<? foreach($workers as $v): ?>
						<option value="<?=$v['uid']?>" <?=(($v['uid'] == $_GET['uid']) || (!$_GET['uid'] && ($v['uid'] == $conf['user']['uid'])) ? "selected" : "")?>><?=$v['name']?></option>
					<? endforeach; ?>
				</select>
			</div>
		<? endif; ?>
	</div>
	<? if(!array_key_exists("null", $_GET)): ?>
		<script src="/include/jquery/jquery.iframe-post-form.js"></script>
		<script>
			check = "<?=$check?>";
			stop = "<?=$stop?>";
			$(function(){
				$(".coast.<?=$arg['blocknum']?> form").iframePostForm({ // Форма отправки данный на сервер.
					complete:function(data){
						if(html = $("<div>").html(data).find(".projects_works").html()){
							$(".projects_works").html(html).find("[duration]").not(":last").attr("duration", -1);
							$(".projects_works .stop").not(":last").attr("src", check).css("cursor", "auto");
							$(".coast.<?=$arg['blocknum']?> form textarea").val("");
							$(".coast.<?=$arg['blocknum']?> form input[name=routine]").attr("checked", false);

							if($(".workers.modules").length > 0){
								$(".base").load(document.location.href+"/null");
							}
						}else{ alert(data); }
					}
				});
/*				$(".coast.<?=$arg['blocknum']?> .stop").click(function(){
					alert(123);
				});*/
				$(".coast.<?=$arg['blocknum']?>").on("click", ".stop", function(){ // Остановка задачи
					projects_works_id = $(img = this).parents("[projects_works_id]").attr("projects_works_id");// alert(projects_works_id);
					uid = $(".coast.<?=$arg['blocknum']?> form input[type=hidden]").attr("value");// alert(uid);
					$.post("/blocks/<?=$arg['blocknum']?>/null", {check:projects_works_id, uid:uid}, function(data){
						if(html = $("<div>").html(data).find(".projects_works").html()){
							time = $(".projects_works").html(html).find("[duration]").attr("duration", -1).find(".time").text();
							$(".projects_works .stop:last").attr("src", check).css("cursor", "auto");
							$(".tasks .activation img").attr("src", "/cost:img/w:15/h:15/null/play.png");

							if($(".workers.modules").length > 0){
								var href = document.location.href;
								$(".base").load(href+"/null");
							}
						}else{ alert(data); }
					});
					$(img).attr("src", check);
				});
				$(".coast.<?=$arg['blocknum']?> select[name='tasks_id']").change(function(){
					projects_id = $(this).find("option:selected").attr("projects_id");// alert(projects_id);
					$(".coast.<?=$arg['blocknum']?> select[name='projects_id']").find("option[value="+projects_id+"]").attr("selected", true);
				});
				$(".coast.<?=$arg['blocknum']?> select[name='projects_id']").change(function(){
					projects_id = $(this).find("option:selected").val();
					$(".coast.<?=$arg['blocknum']?> select[name='tasks_id'] option").each(function(key, val){
						if((pid = $(val).attr("projects_id")) == projects_id || projects_id == 0){
							$(val).show();
						}else{
							$(val).hide();
						}
					}).parent().find("option:first").attr("selected", true);
				});
				clearInterval(intervalID);
				var intervalID = setInterval(function(){
					$(".projects_works > div").each(function(key, val){
						duration = $(this).attr("duration");// alert(duration);
						if(duration == 0){
							tasks_id = $(val).attr("tasks_id");// console.log("tasks_id:", tasks_id);
							time = parseInt($(this).attr("time"))+1;
							$(this).attr("time", time);
							var days = Math.floor(time/86400); if(days <= 0) days = "";
							var hours = Math.floor(time/3600)%24; if(hours <= 9) hours = "0"+hours;
							var minutes = Math.floor(time/60)%60; if(minutes <= 9) minutes = "0"+minutes;
							var seconds = time%60; if(seconds <= 9) seconds = "0"+seconds;
							$(this).find(".time").text(tm = (days + ' ' + hours + ':' + minutes + ':' + seconds));

							old = $("title").text();
							title = $(this).find(".title").text();
							$("title").text(tm+" "+title);
							if(!$("title").attr("old")) $("title").attr("old", old); 

							duration = $("[tasks_id="+tasks_id+"] .activation .time").attr("duration");// console.log("duration:", duration);
							$("[tasks_id="+tasks_id+"] .activation .time").attr("duration", (time = parseInt(duration)+1));

							var days = Math.floor(time/86400); if(days <= 0) days = "";
							var hours = Math.floor(time/3600)%24; if(hours <= 9) hours = "0"+hours;
							var minutes = Math.floor(time/60)%60; if(minutes <= 9) minutes = "0"+minutes;
							var seconds = time%60; if(seconds <= 9) seconds = "0"+seconds;
							$("[tasks_id="+tasks_id+"] .activation .time").text(tm = (days + ' ' + hours + ':' + minutes + ':' + seconds));

						}
					});
				}, 1000);
			});
		</script>
	<? endif; ?>
	<div class="projects_works">
		<? foreach($projects_works as $v): ?>
			<div projects_works_id="<?=$v['id']?>" tasks_id="<?=$v['tasks_id']?>" duration="<?=$v['duration']?>" time="<?=(time()-$v['time'])?>" style="overflow:hidden; border-top:1px dashed #666;">
				<div style="overflow:hidden;">
					<span class="time" style="float:right; font-weight:bold;"><?=($v['duration'] ? gmdate('H:i:s', $v['duration']) : gmdate('H:i:s', time() - $v['time']))?></span>
					<div><a href="/<?=$arg['modname']?>:projects/<?=$v['projects_id']?>"><?=$projects[ $v['projects_id'] ]['name']?></a></div>
				</div>
				<div>
					<span style="float:right; <?=($v['duration'] ? "" : "cursor:pointer;")?>">
						<img class="stop" src="<?=($v['duration'] ? $check : $stop)?>" title="<?=$v['id']?>">
					</span>
					<div class="title" title="<?=$v['description']?>">
						<? if($arg['access'] > 3): # Отображаем прямую ссылку на админстраницу если к блоку даны нужные права доступа ?>
							<a href="/?m[<?=$arg['modpath']?>]=admin&r=<?=$conf['db']['prefix']?><?=$arg['modpath']?>_projects_works&where[id]=<?=$v['id']?>"><img src="/img/aedit.png"></a>
						<? endif; ?>
						<b><?=$works[ $v['works_id'] ]['name']?></b>
						<div>
							<? if($v['tasks_id']): ?>
								<a href="/<?=$arg['modname']?>:tasks/<?=$v['tasks_id']?>"><?=strip_tags(nl2br($v['tasks_name']))?></a>
							<? else: ?>
								<?=$v['description']?>
							<? endif; ?>
						</div>
					</div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<form method="post" action="/blocks/<?=$arg['blocknum']?>/null">
		<input type="hidden" name="uid" value="<?=$worker['uid']?>">
		<div>
			<select name="projects_id" style="width:100%;">
				<? foreach($projects as $v): ?>
					<option value="<?=$v['id']?>"><?=$v['name']?></option>
				<? endforeach; ?>
			</select>
		</div>
		<div>
			<select name="tasks_id" style="width:100%;">
				<option projects_id="0" value="0"></option>
				<? foreach($tasks as $v): ?>
					<option projects_id="<?=$v['projects_id']?>" value="<?=$v['id']?>"><?=$v['name']?></option>
				<? endforeach; ?>
			</select>
		</div>
		<div>
			<select name="works_id" style="width:100%;">
				<? foreach($works as $v): ?>
					<option value="<?=$v['id']?>" <?=($workers[ $worker['uid'] ]['works_id'] == $v['id'] ? "selected" : "")?>><?=strip_tags(nl2br($v['name']))?></option>
				<? endforeach; ?>
			</select>
		</div>
		<div>
			<textarea name="description" placeholder="Описание" style="width:100%;"></textarea>
		</div>
		<div class="current">
			<span style="float:right;">
				<input type="submit" value="Включить">
				<br /><a href="/<?=$arg['modname']?>:projects">Подробнее</a>
			</span>
			Возврат <input type="checkbox" name="routine"><br />
			<input type="text" name="current" value="10" style="width:40px;">&nbsp;мин.
		</div>
	</form>
</div>