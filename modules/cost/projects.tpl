<? include "menu.tpl"; ?>
<? if($p = $conf['projects'][ $_GET['id'] ]): # Страница конкретного проекта ?>
	<? if($arg['access'] >= 3): ?>
		<span style="float:right;">
			<a href="/?m[<?=$arg['modpath']?>]=admin&r=mp_cost_projects&where[id]=<?=$p['id']?>"><img src="/img/aedit.png"></a>
		</span>
	<? endif; ?>
	<span style="float:right;">
		<span style="display:inline-block;"><div projects_id="<?=$p['id']?>" class="klesh hide"><?=$tpl['hide'][ $p['hide'] ]['name']?></div></span>
		<span style="display:inline-block; vertical-align:top; padding:3px;">
			<?=number_format($tpl['sum'][ $p['id'] ]['sum'], 2, '.', '')?>&nbsp;/&nbsp;
			<b title="<?=$p['premium']?>%"><?=number_format($tpl['projects_works'][0]['sum'], 2, '.', '')?></b> <?=$tpl['projects_works'][0]['length']?>
		</span>
	</span>
	<h1><?=$p['name']?></h1>
	<div class="work" projects_id="<?=$p['id']?>">
		<? if(!array_key_exists("null", $_GET)): # Основная страница. (не аякс) ?>
			<script src="/include/jquery/toggleformtext.js"></script>
			<script src="/include/jquery/jquery.iframe-post-form.js"></script>
			<script src="/include/jquery/my/jquery.klesh.js"></script>
			<script>
				$(function(){
/*					$(".work a.activation").on("click", function(){
						tasks_id = $(this).parents("[tasks_id]").attr("tasks_id");// alert(tasks_id);
					});*/
					$(".work form.add").iframePostForm({
						complete:function(data){
							if(html = $("<div>").html(data).find(".tasks").html()){
								$(".tasks").prepend(html);
								$(".work form.add").find("input[type='text']").val("");
								$(".work form.add").find("textarea").val("");

								name = $(html).find(".name").text();// alert(name);
								tasks_id = $(html).find("[tasks_id]").attr("tasks_id");// alert(tasks_id);
								projects_id = $(".work").attr("projects_id");// alert(projects_id);
								$("<option>").attr("value", tasks_id).attr("projects_id", projects_id).text(name).appendTo(".coast select[name='tasks_id']");
							}else{ alert(data) }
						}
					});
					$(".klesh.hide").klesh("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", function(){
					}, <?=json_encode($tpl['hide'])?>).attr("f", "hide");
					$(".work .klesh.tasks_status").klesh("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", function(){
					}, <?=json_encode($tpl['tasks_status'])?>).attr("f", "tasks_status_id");
					$(".work .klesh.tags").klesh("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", function(){
					}, <?=json_encode($tpl['tags'])?>).attr("f", "tags_id");
					$(".tasks").on("click", ".activation", function(){
//						tasks_id = $(this).parents("[tasks_id]").attr("tasks_id");// alert(tasks_id);
						tasks_id = $(this).parents("[tasks_id]").attr("tasks_id");// alert(tasks_id);
						$(".coast select[name='tasks_id'] option[value="+tasks_id+"]").attr("selected", true);
						$(".coast select[name='tasks_id']").change();
						$(".coast .current input[type='submit']").parents("form").submit();
						$(".tasks .activation img").attr({"src":"/cost:img/w:15/h:15/null/play.png"});
						$(this).find("img").attr("src", "/cost:img/null/sload.gif");
					});
					$(".tasks_comments form.com").iframePostForm({
						complete:complete = function(data){
							if(html = $("<div>").html(data).find(".tasks_comments .list").html()){
								tasks_id = $("<div>").html(data).find("[tasks_id]").attr("tasks_id");// alert(tasks_id);
								$(".tasks [tasks_id="+tasks_id+"] .list").prepend(html);
								$(".tasks [tasks_id="+tasks_id+"] form.com").hide();
								$(".tasks [tasks_id="+tasks_id+"] form.com input[type='text']").val("");
								$(".tasks [tasks_id="+tasks_id+"] form.com input[type='file']").val("");
								$(".tasks [tasks_id="+tasks_id+"] form.com textarea").val("");
							}else{ alert(data); }
						}
					});
					$(".tasks_comments a.toggle").click(function(){
						$(this).parents(".tasks_comments").find("form").slideToggle();
					});
					$(".estimate a").click(function(){
						estimate = $(this).parents(".estimate").find("b").text();
						post = {estimate:parseInt(estimate)+1};
						post.tasks_id = $(this).parents("[tasks_id]").attr("tasks_id"); console.log(post);
						$("[tasks_id="+post.tasks_id+"] .estimate b").text(post.estimate);
						$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$p['id']?>/null", post, function(data){
							if(isNaN(data)){
								$("[tasks_id="+post.tasks_id+"] .estimate b").text("Ошибка");
								alert(data);
							}else{}
						});
					});
				});
			</script>
		<? endif; ?>
		<form class="add" action="/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$p['id']?>/null" method="post">
			<div><input type="text" name="name" style="width:100%;" title="Заголовок"></div>
			<div><textarea name="description" style="width:100%;" title="Описание"></textarea></div>
			<div style="overflow:hidden;">
				<select name="workers_id">
					<? foreach($tpl["workers"] as $v): # Список исполнителей ?>
						<option value="<?=$v['id']?>" <?=($v['uid'] == $conf['user']['uid'] ? "selected" : "")?>><?=$v['uname']?></option>
					<? endforeach; ?>
				</select>
				<a href="/<?=$arg['modname']?>:tasks/projects_id:<?=$p['id']?>">Все задачи</a>
				<input type="submit" value="Добавить задачу" style="float:right;">
			</div>
		</form>
		<div class="tasks">
			<style>
				.tasks .list span { color:#888; }
				.tasks .list > div { margin-top:10px; }
			</style>
			<? foreach($tpl['tasks'] as $v): # Список задач проекта ?>
				<div tasks_id="<?=$v['id']?>" style="margin-top:10px;">
					<div>
						<span class="activation" style="float:right;">
							<? if($tpl['tasks_works'][ $v['id'] ]): # Задача в работе ?>
								<span><a href="/<?=$arg['modname']?>/uid:<?=$v['projects_works_uid']?>"><?=$v['uname']?></a></span>
								<span class="time" duration="<?=(int)$v['duration']?>"><?=mptс(time()-$v['duration'], 1)?></span>
								<span><img src="/cost:img/null/sload.gif"></span>
							<? else: # Задача не в работе ?>
								<span class="time" duration="<?=(int)$v['duration']?>"><?=mptс(time()-$v['duration'], 1)?></span>
								<a href="javascript:">
									<span><img src="/cost:img/w:15/h:15/null/play.png"></span>
								</a>
							<? endif; ?>
						</span>
						<span style="display:inline-block; vertical-align:top; padding:3px;"><?=date('Y.m.d H:i:s', $v['time'])?></span>
						<span style="display:inline-block;">
							<span style="display:inline-block;"><div tasks_id="<?=$v['id']?>" class="klesh tasks_status"><?=$tpl['tasks_status'][ $v['tasks_status_id'] ]['name']?></div></span>
							<span style="display:inline-block;"><div tasks_id="<?=$v['id']?>" class="klesh tags"><?=$tpl['tags'][ $v['tags_id'] ]['name']?></div></span>
						</span>
					</div>
					<div style="overflow:hidden;">
						<div>
							<div class="estimate" style="float:right; text-align:center; padding:10px 23px; border:1px solid #eee; border-radius:10px;">
								<div style="font-size:30px; font-weight:bold; margin-top:10px;"><b><?=(int)$v['estimate']?></b></div>
								<div><a href="javascript:">Голосовать</a></div>
							</div>
							<div class="name">
								<span>
									<? if($arg['access'] > 3): # Доступ пользователя к разделу модератор и выше ?>
										<a href="/?m[<?=$arg['modpath']?>]=admin&r=<?=$conf['db']['prefix']?><?=$arg['modpath']?>_tasks&where[id]=<?=$v['id']?>"><img src="/img/aedit.png"></a>
									<? endif; ?>
								</span>
								<span style="font-weight:bold;"><?=$tpl["worker"][ $v['workers_id'] ]['uname']?></span>
								<span><?=$v['name']?></span>
							</div>
							<div class="description"><?=html_entity_decode($v['description'])?></div>
						</div>
					</div>
					<div class="tasks_comments" style="margin-left:20px;">
						<div style="text-align:right;"><a class="toggle" href="javascript:">Комментарии [<?=(int)count($tpl['tasks_comments'][ $v['id'] ])?>]</a></div>
						<form class="com" action="/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$p['id']?>/null" method="post" enctype="multipart/form-data" style="display:none;">
							<input type="hidden" name="tasks_id" value="<?=$v['id']?>">
							<div style="display:none;"><input type="text" name="name" style="width:100%;"></div>
							<div><textarea name="description" style="width:100%;" placeholder="Ваш комментарий"></textarea></div>
							<div>
								<input type="submit" style="float:right;" value="Добавить комментарий">
								<input type="file" name="file">
							</div>
						</form>
						<div class="list">
							<? if($tpl['tasks_comments'][ $v['id'] ]) foreach($tpl['tasks_comments'][ $v['id'] ] as $c): # Комментарии к задаче ?>
								<div>
									<div style="font-weight:bold; overflow:hidden;">
										<span style="float:right;"><?=date("Y.m.d H:i:s", $c['time'])?></span>
										<span style="font-weight:bold;"><?=$c['uname']?></span><span><?=$c['name']?></span>
									</div>
									<div style="color:#555;">
										<span style="float:right;">
											<a href="/<?=$arg['modname']?>:file/<?=$c['id']?>/tn:tasks_comments/fn:file/null/<?=$c['name']?>"><?=$c['name']?></a>
										</span>
										<span><?=html_entity_decode($c['description'])?></span>
									</div>
								</div>
							<? endforeach; ?>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	</div>
	<? foreach(array_diff_key($tpl['projects_works'], array("")) as $work_id=>$users): ?>
		<div>
			<div style="margin-top:15px; background-color:#888; color:white; padding:5px; border-radius:5px;">
				<span style="float:right;">
					<b><?=$users[0]['cost']?></b>&nbsp;<?=$users[0]['length']?>
				</span>
				<h3><?=$tpl["works"][ $work_id ]['name']?></h3>
			</div>
			<? foreach(array_diff_key($users, array("")) as $uid=>$projects_works): ?>
				<div style="margin:10px 0 0 20px;">
					<div>
						<span style="float:right;"><b><?=$projects_works[0]['cost']?></b> <?=$projects_works[0]['length']?></span>
						<span><b><?=$tpl["workers"][ $uid ]['uname']?></b> (<?=count(array_diff_key($projects_works, array_flip(array(""))))?>)</span>
					</div>
					<? foreach(array_diff_key($projects_works, array("")) as $id=>$v): ?>
						<div style="margin-left:20px;">
							<span style="float:right;" title="<?=date('d.m.Y H:i:s', $v['time'])?>">
								<b><?=$v['cost']?></b>&nbsp;<?=$v['length']?>
							</span>
							<div>
								<? if($arg['access'] > 3): ?>
									<span><a href="/?m[cost]=admin&r=mp_cost_projects_works&where[id]=<?=$v['id']?>"><img src="/img/aedit.png"></a></span>
									<span><?=date("Y.m.d H:i:s", $v['time'])?></span>
								<? endif; ?>
								<div style="font-weight:bold;">
									<a href="/<?=$arg['modname']?>:tasks/<?=$v['tasks_id']?>" title="<?=strip_tags($v['tasks_description'])?>">
										<?=$v['tasks_name']?>
									</a>
									<? if(array_key_exists("description", $_GET)): ?>
										<div style="font-weight:normal; margin:20px;"><small><?=strip_tags($v['tasks_description'])?></small></div>
									<? endif; ?>
								</div>
								<div><?=$v['description']?></div>
							</div>
						</div>
					<? endforeach ?>
				</div>
			<? endforeach ?>
		</div>
	<? endforeach ?>
<? else: # Страница со списком проектов ?>
	<div class="projects list">
		<style>
			.projects.list .projects_sum > span {display:inline-block; min-width:50px; text-align:right;}
			.projects.list > div > span {display:inline-block; min-width:15px;}
		</style>
		<? foreach($conf['projects'] as $v): ?>
			<div>
				<span style="float:right;" class="projects_sum">
					<span><?=($tpl['tasks_uid'][ $v['id'] ]['cnt'] ?: "")?></span>
					<span><?=($tpl['tasks_cnt'][ $v['id'] ]['cnt'] ?: "")?></span>
					<span><?=(int)$tpl['psum'][ $v['id'] ]['sum']?></span>
					<span><?=(int)$tpl['wsum'][ $v['id'] ]['duration']?></span>
				</span>
				<span>
					<? if($v['uid'] == $conf['user']['uid']): ?>
						<a href="/<?=$arg['modname']?>:params/<?=$v['id']?>">
							<img src="/img/edit.png">
						</a>
					<? endif; ?>
				</span>
				<span>
					<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$v['id']?>"><?=$v['name']?></a>
				</span>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>
