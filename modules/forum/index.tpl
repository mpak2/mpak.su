	<div style="width:50%; float:right;">
		<? foreach((array)$conf['tpl']['last'] as $k=>$v): ?>
			<div style="margin-top:10px; overflow:hidden;">
				<div style="margin:5px 0;">
					<span style="float:right;"><a href="/users/<?=$v['uid']?>"><?=($v['uid'] > 0 ? $v['uname'] : "{$conf['settings']['default_usr']}{$v['uid']}")?></a></span>
					<span style="font-weight:bold;"><?=date('Y.m.d H:i:s', $v['time'])?></span>
				</div>
				<div>
					<div style="float:right; display:inline-block;">
						<a href="/<?=$arg['modname']?>/vetki_id:<?=$v['vetki_id']?>">
							<?=mb_substr($v['text'], 0, 120, 'utf-8')?>
						</a>
					</div>
<!--					<div style="float:left; display:inline-block; width:60px;">
						<img src="/users:img/<?=$v['uid']?>/tn:index/w:50/h:70/null/img.jpg">
					</div>-->
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<div style="padding:10px;">
		<? if($conf['tpl']['vetka']['aid'] == 2): ?>
			<div style="float:right;"><a href="/forum:new/-<?=$_GET['vetki_id']?>">Новая тема</a></div>
		<? endif; ?>
		<div>
			<a href='/<?=$arg['modname']?>'>Весь форум</a>
			<? foreach((array)$conf['tpl']['forums'] as $k=>$v): ?>
				> <a href="/<?=$arg['modname']?>/vetki_id:<?=$v['id']?>"><?=$v['name']?></a>
			<? endforeach; ?>
		</div>
	</div>

	<div style="border:0px solid red; overflow:hidden; margin:10px;">
		<ul>
			<? foreach($conf['tpl']['vetki'] as $k=>$v): ?>
				<li>
					<a href=/<?=$arg['modname']?>/vetki_id:<?=$v['id']?>><?=$v['name']?></a>
					<? if($v['count']): ?>
						[<?=$v['count']?>]
					<? endif; ?>
				</li>
			<? endforeach; ?>
		</ul>
	</div>

	<div style='margen:10px;'>
		<table width=100% cellspacing=3 cellpadding=3 style="background-color:#eee;">
			<? foreach($conf['tpl']['mess'] as $k=>$v): # Вывод сообщений ?>
				<tr valign=top>
					<td style="width:120px; text-align:center;">
						<div><?=date('Y.m.d H:i:s', $v['time'])?></div>
						<div>
							<? if(($v['uid'] == $conf['user']['uid']) || ($arg['access'] >= 4)): ?>
								<a href=/<?=$arg['modname']?>/vetki_id:<?=$_GET['vetki_id']?>/del:<?=$v['id']?> onclick="javascript: if (confirm('Вы уверенны?')){return obj.href;}else{return false;}">
									<img src=/img/del.png border=0>
								</a>
								<a href=/forum/vetki_id:<?=$_GET['vetki_id']?>/edit:<?=$v['id']?>>
									<img src=/img/edit.png border=0>
								</a>
							<? endif; ?>
							<a href="/users/<?=$v['uid']?>"><?=($v['uid'] > 0 ? $v['uname']. " [{$v['count']}]" : "{$conf['settings']['default_usr']}{$v['uid']}")?></a> 
						<div>
						<div style="margin:5px;">
							<img src="/users:img/<?=$v['uid']?>/tn:index/w:100/h:65/null/img.jpg">
						</div>
					</td>
					<td valign=middle style="text-align:center;">
						<?=nl2br($v['text'])?>
					</td>
				</tr>
			<? endforeach; ?>
			<? if($conf['tpl']['vetka']['aid'] > 2): ?>
				<tr>
					<td colspan=2>
						<form method=post>
							<input type='hidden' name='id' value="<?=(int)$conf['tpl']['edit']['id']?>">
							<div style="float:left; width:120px; text-align:center;">
								<div style="margin:5px;"><img src="/users:img/<?=$conf['user']['uid']?>/tn:index/w:100/h:65/null/img.jpg"></div>
								<div style="overflow:hidden; margin:10px;">
									<input type=submit value='Сохранить'>
								</div>
							</div>
							<div style="position:relative; margin-left:125px;">
								<textarea name='text' placeholder="Ваше сообщение" style='width:95%; height:105px;'><?=($conf['tpl']['edit']['text'] ? htmlspecialchars($conf['tpl']['edit']['text']) : '')?></textarea>
							</div>
						</form>
					</td>
				</tr>
			<? endif; ?>
		</table>
	</div>