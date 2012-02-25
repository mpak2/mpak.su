<? if(empty($_POST)): ?>
	<script>
		$(function(){
			$("#backfid .toggle").live("click", function(){
				$(this).parents("[backfid_id]").find("ul").slideToggle();
			});
			$("#backfid .add input[type=button]").click(function(){
				text = $(this).parents(".add").find("textarea").val();// alert(text);
				$(this).parents(".add").find("textarea").val("");
				$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=(int)$_GET['id']?>/null", {index_id:<?=(int)$_GET['id']?>, text:text}, function(data){
					if(isNaN(data)){// alert(data) }else{
						$(".add").find(".toggle").hide();
						html = $("<div>").html(data).find("#backfid").html()
						$("#backfid > .add").after(html);
						
					};
				});
			});
			$("#backfid .add a").live("click", function(){
				$(this).parents(".add").find(".toggle").slideToggle();
			});
			$(".answers a.add").live("click", function(){
				$(this).parents(".answers").find(".answer").slideToggle();
			});
			$(".answers input[type=submit]").live("click", function(){
				backfid_id = $(this).parents("[backfid_id]").attr("backfid_id");// alert(backfid_id);
				text = $(this).parents(".answers").find("textarea").val();// alert(text);
				$(this).parents(".answers").find("textarea").val("");
				$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=(int)$_GET['id']?>/null", {backfid_id:backfid_id, text:text}, function(data){
					text = $("<div>").html(data).find("ul").html();// alert(text);
					$("[backfid_id="+backfid_id+"]").find("ul").show().prepend(text);
					$("[backfid_id="+backfid_id+"] .answers").find(".answer").hide(300);
				});
			});
		});
	</script>
<? endif; ?>
<div><?=$conf['tpl']['mpager']?></div>
<div id="backfid">
	<? if(empty($_POST)): ?>
		<div class="add">
			<a href="javascript:return false;">Добавить отзыв</a>
			<div id="text_ot" class="toggle" style="display:none;">
				<div><textarea style="width:100%;"></textarea></div>
				<div style="text-align:right;"><input type="button" value="Добавить отзыв"></div>
			</div>
		</div>
	<? endif; ?>
	<? foreach($conf['tpl']['backfid'] as $k=>$v): ?>
		<div backfid_id="<?=$v['id']?>">
			<div class="comment" style="margin:10px 0;">
				<div id="top">
					<b>Отзыв на</b> → <a href="/<?=$arg['modpath']?>/<?=$conf['tpl']['page']['id']?>/<?=mpue($conf['tpl']['page']['name'])?>"><b><?=$conf['tpl']['page']['name']?></b></a> (<a href="/users/<?=$conf['tpl']['page']['uid']?>"><?=$conf['tpl']['page']['fm']?> <?=$conf['tpl']['page']['im']?></a>)
				</div>
				<div id="center"><?=$v['text']?></div>
				<div id="bottom">
					<img align="left" alt="" src="/users:img/<?=$v['uid']?>/tn:index/w:24/h:24/c:1/null/img.jpg">
					<b><?=$v['fm']?> <?=$v['im']?></b><br>
					<span style="color:#adadad;font-size:11px;">Написано <?=date("d.m.Y", $v['time'])?> в <?=date("H:i", $v['time'])?></span>
				</div>
			</div>
			<div class="answers">
				<span style="float:right;"><a class="toggle" href="javascript: return false;">Ответов <?=count($conf['tpl']['answers'][ $v['id'] ])?></a></span>
				<span><a class="add" href="javascript:return false;">Ответить</a></span>
				<div class="answer" style="display:none; margin-left:80px;">
					<textarea style="width:100%;"></textarea>
					<div style="text-align:right;"><input type="submit" value="Добавить ответ"></div>
				</div>
				<ul style="display:none;">
					<? if($conf['tpl']['answers'][ $v['id'] ]): ?>
						<? foreach($conf['tpl']['answers'][ $v['id'] ] as $a): ?>
							<div class="comment" style="margin:10px 0 0 80px;;">
								<div id="top">
									<b>Ответ</b> → <a href="/users/1"><?=$a['fm']?> <?=$a['im']?></a>
								</div>
								<div id="center"><?=$a['text']?></div>
								<div id="bottom">
									<img align="left" alt="" src="/users:img/<?=$a['uid']?>/tn:index/w:24/h:24/c:1/null/img.jpg">
									<b><?=$a['fm']?> <?=$a['im']?></b><br>
									<span style="color:#adadad;font-size:11px;">Написано <?=date("d.m.Y", $a['time'])?> в <?=date("H:i", $a['time'])?></span>
								</div>
							</div>
						<? endforeach; ?>
					<? endif; ?>
				</ul>
			</div>
		</div>
	<? endforeach; ?>
</div>
<div><?=$conf['tpl']['mpager']?></div>
