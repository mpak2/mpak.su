<? include "menu.tpl"; ?>
<script src="/include/jquery/my/jquery.klesh.js"></script>
<script>
	$(function(){
		$(".tasks_comments a.toggle").click(function(){
			$(this).parents(".tasks_comments").find("form").slideToggle();
		});
		$(".work .klesh.tasks_status").klesh("/<?=$arg['modname']?>:projects/null", function(){
		}, <?=json_encode($tpl['tasks_status'])?>).attr("f", "tasks_status_id");
		$(".work .klesh.tags").klesh("/<?=$arg['modname']?>:projects/null", function(){
		}, <?=json_encode($tpl['tags'])?>).attr("f", "tags_id");
	});
</script>
<div class="work">
	<? foreach($tpl['tasks'] as $v): ?>
		<div tasks_id="<?=$v['id']?>" style="margin-top:10px;">
			<div>
				<span style="float:right;">
					<?=mptс(time()-$v['duration'], 1)?>
				</span>
				<span style="display:inline-block; vertical-align:top; padding:3px;"><?=date('Y.m.d H:i:s', $v['time'])?></span>
				<span style="display:inline-block;">
					<span style="display:inline-block;"><div tasks_id="<?=$v['id']?>" class="klesh tasks_status"><?=$tpl['tasks_status'][ $v['tasks_status_id'] ]['name']?></div></span>
					<span style="display:inline-block;"><div tasks_id="<?=$v['id']?>" class="klesh tags"><?=$tpl['tags'][ $v['tags_id'] ]['name']?></div></span>
				</span>
			</div>
			<div class="name" style="font-weight:bold;">
				<span style="float:right;"><a href="/<?=$arg['modname']?>:projects/<?=$v['projects_id']?>"><?=$v['projects_name']?></a></span>
				<span><?=$v['name']?></span>
			</div>
			<div class="description"><?=html_entity_decode($v['description'])?></div>
			<div class="tasks_comments" style="margin-left:20px;">
				<div style="text-align:right;"><a class="toggle" href="javascript:">Комментарии [<?=(int)count($tpl['tasks_comments'][ $v['id'] ])?>]</a></div>
				<form class="com" action="/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$p['id']?>/null" method="post" enctype="multipart/form-data" style="display:none;">
					<input type="hidden" name="tasks_id" value="<?=$v['id']?>">
					<div style="display:none;"><input type="text" name="name" style="width:100%;"></div>
					<div><textarea name="description" style="width:100%;"></textarea></div>
					<div>
						<input type="submit" style="float:right;" value="Добавить комментарий">
						<input type="file" name="file">
					</div>
				</form>
				<div class="list">
					<? if($tpl['tasks_comments'][ $v['id'] ]) foreach($tpl['tasks_comments'][ $v['id'] ] as $c): ?>
						<div>
							<div style="font-weight:bold;">
								<span style="float:right;"><?=date("Y.m.d H:i:s", $c['time'])?></span>
								<span><?=$c['name']?></span>
							</div>
							<div style="color:#555;">
								<span style="float:right;"><a href="/<?=$arg['modname']?>:file/<?=$c['id']?>/tn:tasks_comments/fn:file/null/<?=$c['name']?>"><?=$c['name']?></a></span>
								<span style="color:#888888;"><?=html_entity_decode($c['description'])?></span>
							</div>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		</div>
	<? endforeach; ?>
</div>