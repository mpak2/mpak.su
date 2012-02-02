<? if($_POST): ?>
	<div style="text-align:center; margin:100px;">
		Сообщение отправлено
		<!-- <a href="/<?=$arg['modpath']?>/anket_id:<?=$conf['tpl']['anket_id']?>">Смотреть</a> -->
		<a href="/<?=$arg['modpath']?>/<?=$_GET['id']?>">Продолжить</a>
	</div>
<? else: ?>
	<? if($_GET['id']): ?>
		<script language="javascript">
			$(function(){
				$('#nav').spasticNav();
				$("#sel").change(function(){
					var cat_id = $(this).find("option:selected").val();// alert(cat_id);
					document.location.href = "/<?=$arg['modpath']?>/"+cat_id;
				});
				<? if($_GET['anket_id']): ?>
					$(".enb").attr("disabled", "disabled");
				<? endif; ?>
			});
		</script>
		<style>
			.el {width:100%;}
			.ta {height:160px;}
			input:disabled, textarea:disabled {background-color:#eee; color:#444;}
		</style>
		<div style="margin:10px 0; padding:10px; border-radius:10px; border:1px solid #ddd;">
			<div><?=$conf['settings']["{$arg['modpath']}_title"]?></div>
			<? if($conf['settings']["{$arg['modpath']}_user_view"]): ?>
				<div style="overflow:hidden;">
					<div style="float:right; width:100px; text-align:center;">
						<a href="/users/<?=$conf['tpl']['index'][ $_GET['id'] ]['uid']?>">
							<img src="/users:img/<?=$conf['tpl']['index'][ $_GET['id'] ]['uid']?>/tn:index/w:50/h:50/c:1/null/img.jpg">
							<div><?=$conf['tpl']['index'][ $_GET['id'] ]['uname']?></div>
						</a>
					</div>
					<div style="margin:5px; text-align:center;"><?=$conf['tpl']['index'][ $_GET['id'] ]['description']?></div>
				</div>
			<? endif; ?>
			<div style="font-family:sans-serif;">
				<form method="post" enctype="multipart/form-data">
					<? foreach($conf['tpl']['vopros'] as $tid=>$vopros): ?>
						<? if($conf['tpl']['type'][ $tid ]['name']): ?>
							<fieldset>
							<legend><?=$conf['tpl']['type'][ $tid ]['name']?></legend>
						<? endif; ?>
							<? foreach($vopros as $vid=>$v): ?>
								<div><?=$v['name']?></div>
								<? if($v['type'] == 'textarea'): ?>
									<textarea class="el enb ta" name="<?=$vid?>"><?=$conf['tpl']['result'][ $vid ][ 0 ]['val']?></textarea>
								<? elseif($v['type'] == 'text'): ?>
									<input class="el enb" type="text" name="<?=$vid?>" value="<?=$conf['tpl']['result'][ $vid ][ 0 ]['val']?>">
								<? elseif($v['type'] == 'file'): ?>
									<input class="el enb" type="file" name="<?=$vid?>[file]">
								<? elseif($v['type'] == 'select'): ?>
									<select class="enb" name="<?=$vid?>">
										<? foreach($conf['tpl']['variant'][ $vid ] as $vtid=>$vt): ?>
											<option value="<?=$vt['id']?>" <?=($conf['tpl']['result'][ $vid ][ $vt['id'] ]['id'] ? "selected" : "")?>><?=$vt['name']?></option>
										<? endforeach; ?>
									</select>
								<? elseif($v['type'] == 'radio'): ?>
									<ul style="overflow:hidden;">
										<? foreach($conf['tpl']['variant'][ $vid ] as $vtid=>$vt): ?>
											<li style="float:<?=($v['float'] ? "none" : "left")?>; list-style-type:none;">
												<input class="el enb" type="radio" name="<?=$vid?>" value="<?=$vt['id']?>" <?=($conf['tpl']['result'][ $vid ][ $vt['id'] ]['id'] ? "checked" : "")?>>
												<?=$vt['name']?>
											</li>
										<? endforeach; ?>
									</ul>
								<? elseif($v['type'] == 'spastic'): ?>
									
										<ul id="nav">
											<input type="hidden" name="<?=$vid?>">
											<? foreach($conf['tpl']['variant'][ $vid ] as $vtid=>$vt): ?>
												<li id="<?=(!$t++ ? "selected" : "")?>"><?=$vt['name']?></li>
											<? endforeach; ?>
										</ul>
										<div style="clear:both;"></div>
								<? elseif($v['type'] == 'check'): ?>
									<ul style="overflow:hidden;">
										<? foreach((array)$conf['tpl']['variant'][ $vid ] as $vtid=>$vt): ?>
											<li style="float:<?=($v['float'] ? "none" : "left")?>; list-style-type:none;">
												<input class="el enb" type="checkbox" name="<?=$vid?>[<?=$vt['id']?>]" <?=($conf['tpl']['result'][ $vid ][ $vt['id'] ]['id'] ? "checked" : "")?>>
												<?=$vt['name']?>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>
							<? endforeach; ?>
						<? if($conf['tpl']['type'][ $tid ]['name']): ?>
							</fieldset>
						<? endif; ?>
					<? endforeach; ?>
					<div style="text-align:right;"><input type="submit" value="Сохранить"></div>
				</form>
			</div>
		</div>
	<? else: ?>
		<ul>
			<? foreach($conf['tpl']['index'] as $k=>$v): ?>
				<li>
					<div style="margin:5px 0;">
						<div style="float:right;"><a href="/users/<?=$v['uid']?>"><?=$v['uname']?></a></div>
						<div><a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?></a></div>
					</div>
					<div style="margin-left:20px; font-style:italic;"><?=strip_tags($v['description'])?></div>
				</li>
			<? endforeach; ?>
		</ul>
	<? endif; ?>
<? endif; ?>