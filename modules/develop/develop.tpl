<? if($arg['admin_access'] >= 4): ?>
	<div class="cont">
		<div class="TicketBlock">
			<h1>Задинамить новую админку</h1>
			<p class="sence">История портальной системы началась как обычно с папки в которой располагались файлы библиотек, модули, конфигурационные файлы и скрипт установки. В таком состоянии система развивалась какое то время.</p>
			<ul class="nl CommentsList">
				<? foreach($conf['tpl']['dev'] as $k=>$v):// mpre($v); ?>
				<li>
					<img src="/themes/zhiraf/i/user_und.gif" alt="" class="av" />
					<div><?=$conf['tpl']['users'][$v['uid']]?></div>
					<span><?=date('d.m.Y H:i:s', $v['time'])?></span>
					<p><?=$v['plan']?></p>

				</li>
				<? endforeach; ?>
			</ul>
			<form method="post">
				<textarea name="" rows="" cols="" placeholder="Добавить комментарий"></textarea>
				<a href="#" class="addLink">Добавить файл</a>
				<div class="button"><input type="submit" value="отправить" /></div>
				<div class="cb" style="margin:0 0 25px 0;"></div>
			</form>
		</div>
	</div>
	<div style="padding:5px;"><? echo mpager($conf['tpl']['pcount']); ?></div>
<? endif; ?>