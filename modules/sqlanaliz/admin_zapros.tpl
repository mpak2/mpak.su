<? if($_POST['tn'] && $_POST['join']): ?>
	<div style="margin-top:20px;"><?=$conf['tpl']['tpl']?></div>
	<? if($_POST['p']): ?>
		<br />$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/<?=(int)$_POST['p']?> AS cnt"), 0, 'cnt'));
	<? endif; ?>
	<div style="margin-top:10px;">Количество записей: <?=$conf['tpl']['count']?></div>
	<div style="margin-top:20px;"><? mpre($conf['tpl']['zapros']) ?></div>
<? elseif($_POST['modules_id']): ?>
	<script>
		$(function(){
			$("form#tab").iframePostForm({
				complete:function(data){
					$("#zapros").html(data);
				}
			})
		});
	</script>
	<form id="tab" method="post" action="/<?=$arg['modname']?>:<?=$arg['fe']?>/null" style="margin-top:10px;">
		<input type="hidden" name="modules_id" value="<?=$_POST['modules_id']?>">
		<span><input type="checkbox" name="tpl" checked="checked"> Шаблон</span>
		<span> по <input type="text" name="p" style="width:50px;"> на странице</span>
		<div class="tn">
			<select name="tn[]" style="width:50%;">
				<? foreach($conf['tpl']['mod'] as $k=>$v): ?>
					<option><?=$k?></option>
				<? endforeach; ?>
			</select>
			<select name="join[]" style="width:20%;">
				<option>LEFT JOIN</option>
				<option>INNER JOIN</option>
			</select>
			&nbsp; <a class="join" href="javascript: return false;"><img src="/sqlanaliz:img/null/plus.png"></a>
			&nbsp; <a class="unjoin" href="javascript: return false;"><img src="/sqlanaliz:img/null/del.png"></a>
		</div>
		<div style="margin-top:10px;">WHERE <input type="text" name="where" style="width:60%;"></div>
		<div style="width:50%; margin-top:10px;">
			<input type="submit" value="Сформировать запрос">
		</div>
	</form>
<? else: ?>
	<style>.tn { margin-top:5px; }</style>
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
/*			$(".tn select[name='tn[]']").live("change", function(){
				$(this).parents(".tn").find(".join").click();
			});*/
			var tmp = 0;
			$(".join").live("click", function(){
				html = $(this).parents(".tn").html();// alert(html);
				tn = $("<div>").addClass('tn').html(html);
				$(this).parents(".tn").after(tn);
			});
			$(".unjoin").live("click", function(){
				$(this).parents(".tn").hide(300).remove();
			});
			$("#modules_id").change(function(){
				if(modules_id = $(this).find("option:selected").val()){
					$.post("/<?=$arg['modname']?>:<?=$arg['fe']?>/null", {modules_id:modules_id}, function(data){
						$("#data").html(data);
					});
				}
			}).change();
		});
	</script>
	<div>
		<select id="modules_id">
			<option></option>
			<? foreach($conf['tpl']['modules'] as $k=>$v): ?>
				<option value="<?=$v['id']?>"><?=$v['name']?></option>
			<? endforeach; ?>
		</select>
	</div>
	<div id="data"></div>
	<div id="zapros"></div>
<? endif; ?>