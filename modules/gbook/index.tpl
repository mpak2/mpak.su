<p><center><b><?=$conf['settings']['gbook_title']?></b></center>
<div class="gbook" style="margin-left:10%;">
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script src="/include/jquery/toggleformtext.js"></script>
	<script>
		$(function(){
			$(".gbook form").iframePostForm({
				complete:function(data){
					if(isNaN(data)){ alert(data); }else{
						alert("Информация сохранена");
						window.document.location.href = "/";
					}
				}
			});
		});
	</script>
	<form method='post' style="width:70%;">
		<input type='hidden' name='gbook[md5]' value='<?=$conf['tpl']['md5']?>'>
		<input type='text' name='gbook[name]' style='width:100%;' value='<?=$conf['user']['uname']?>'>
		<span style="float:right; margin-top:3px;"><img src='/<?=$arg['modname']?>:captcha/null/cod.png' border=1></span>
		<span style="padding-top:20px;">
			<input type='text' name='gbook[kod]' style="width:250px;" title="Введите код">
			<a href="">Обновить</a>
		<span>
		<textarea style="width:60%; height:70px;" name="gbook[text]" title="Комментарий"></textarea>
		<input type='submit' value='Добавить'>
	</form>
</div>
<? foreach($conf['tpl']['mess'] as $k=>$v): ?>
	<p><table border=0 width=100%>
		<tr valign=top>
			<td colspan=2>
				<?=($conf['tpl']['admin'] ? "<a onclick=\"javascript: if (confirm('Вы уверенны?')){return obj.href;}else{return false;}\" href=?m[{$arg['modpath']}]=admin&del={$v['id']}><img src=/img/del.png border=0></a>&nbsp;
			<a href=?m[{$arg['modpath']}]=admin&edit={$v['id']}><img src=/img/edit.png border=0></a>&nbsp;" : '')?>
				<span style="float:right;"><?=mptс($v['time'])?> назад</span>
				<font color=blue><b><?=(strlen($v['name']) ? strtr($v['name'], array(' '=>'&nbsp;')) : $v['uname'])?></b></font>:
				<?=$v['text']?>
			</td>
		</tr>
		<tr>
			<td>
				<div style='margin-left: 100px;'>
					<?(strlen($v['otvet']) ? "&nbsp;<font color=blue><b>{$conf['settings']['gbook_admin_site']}</b></font>".($v['otime'] ? date(' (d.m.Y H:i)', $v['otime']) : '').": " : "")?><i><?=$v['otvet']?></i>
				</div>
			</td>
		</tr>
	</table>
<? endforeach; ?>