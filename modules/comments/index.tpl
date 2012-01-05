<? if(array_key_exists("null", $_GET)): ?>
	<? if (!$conf['tpl']['deny']): ?>
		<? if(!$_POST): ?>
			<!-- [settings:foto_lightbox] -->
			<script type="text/javascript">
				$("#btn").mousedown(function(){
					uname = $('#uname_<?=$arg['modpath']?>').val();// alert(uname);
					text = $('#text_<?=$arg['modpath']?>').val();// alert(text);
					if($('#text_<?=$arg['modpath']?>').attr("title") != text){
						$.post('/<?=$arg['modpath']?>/null', { uname:uname, text:text},
							function (data){
								$("#comment").prepend(data);
							}
						);
					}else{
						alert("Добавьте комментарий.");
					}
					$("#uname_<?=$arg['modpath']?>").val("").blur();
					$("#text_<?=$arg['modpath']?>").val("").blur();
				});
				$(document).ready(function(){
					$("input:text, textarea, input:password").each(function(){
						if(this.value == '')
							this.value = this.title;
					});
					$("input:text, textarea, input:password").focus(function(){
						if(this.value == this.title)
							this.value = '';
					})
					$("input:text, textarea, input:password").blur(function(){
						if(this.value == '')
							this.value = this.title;
					});
					$("input:image, input:button, input:submit").click(function(){
						$(this.form.elements).each(function(){
							if(this.type =='text' || this.type =='textarea' || this.type =='password' ){
								if(this.value == this.title && this.title != ''){
									this.value='';
								}
							}
						});
					});
				});
			</script>
			<table width=100% border=0>
				<tr>
					<td>
						<input type="text" id='uname_<?=$arg['modpath']?>' title="<?=$conf['user']['uname']?>" class="name" style="width:100%;" />
					</td>
					<td width="70px"><input id="btn" type="button" value='добавить' onClick='javascript: return false;' class="sbmt" /></td>
				</tr>
				<tr>
					<td colspan=3>
						<textarea id="text_<?=$arg['modpath']?>" title="Комментарий" style="width: 100%;"></textarea>
					</td>
				</tr>
			</table>

			<style>
				.CommentsBlock li {
					margin-top: 15px;
				}
			</style>
		<? endif; ?>
		<ul id=comment class="CommentsBlock">
			<a name="<?=$arg['modpath']?>"></a>
			<? foreach((array)$conf['tpl']['comments'] as $k=>$v): ?>
			<li style="overflow:hidden;">
				<? if($v['uid']): ?>
					<div id="gallery" style="float:left; margin:0 10px;">
						<a href="/users:img/<?=$v['uid']?>/tn:index/w:600/h:500/null/img.jpg">
							<img src="/users:img/<?=$v['uid']?>/tn:index/w:40/h:40/null/img.jpg">
						</a>
					</div>
				<? endif; ?>
				<div>
					<span style="float:right;"><?=date('Y.m.d H:i:s', $v['time'])?></span>
					<span style="font-weight:bold;">
						<? if($v['uid'] > 0): ?><a href="/users/<?=$v['uid']?>"><? endif; ?>
							<?=$v['uname']?>
						<? if($v['uid'] > 0): ?></a><? endif; ?>
					</span>
				</div>
				<div><?=$v['text']?></div>
			</li>
			<? endforeach; ?>
		</ul>
	<? endif; ?>
<? else: ?>
	<? foreach($conf['tpl']['comments'] as $k=>$v): ?>
		<div style="margin-top:10px;">
			<div>
				<span style="float:right;"><?=date("d.m.Y H:i:s", $v['time'])?></span>
				<div><a href="/<?=$conf['modules']['users']['modname']?>/<?=$v['uid']?>"><?=$v['uname']?></a></div>
			</div>
			<div><?=$v['text']?></div>
			<div style="text-align:right;"><a href="<?=$v['url']?>">На страницу комментария</a></div>
		</div>
	<? endforeach; ?>
<? endif; ?>