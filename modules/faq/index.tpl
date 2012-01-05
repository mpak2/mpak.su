<style>
	.qw{
		padding:5px;
		border-top: 1px solid gray;
		border-bottom: 1px solid gray;
	}
	.qw, .ans{ margin:10px; }
	.ans { margin-left:50px; }
	.href { padding-left:50px; text-align:right; white-space:nowrap; overflow:hidden;}
</style>
<? if($conf['tpl']['faq']): ?>
	<? if($v = $conf['tpl']['user']): ?>
		<div style="overflow:hidden;">
			<div style="float:right; text-align:center;">
				<div><img src="/<?=$conf['modules']['users']['modname']?>:img/<?=$v['id']?>/tn:index/w:50/h:50/c:1/null/img.jpg"></div>
				<div><a href="/<?=$conf['modules']['users']['modname']?>/<?=$v['id']?>"><?=$v['name']?></a></div>
			</div>
			<div style="margin-right:100px; float:left; padding:20px 50px;">
				<h1>Часто задаваемые вопросы</h1>
			</div>
		</div>
	<? endif; ?>
	<? foreach((array)$conf['tpl']['faq'] as $k=>$v): ?>
		<div>
			<div class="qw"><?=$v['qw']?></div>
			<div class="ans"><?=$v['ans']?></div>
			<div class="href"><a href="<?=$v['href']?>">http://<?=mpidn($_SERVER['HTTP_HOST'])?><?=$v['href']?></a></div>
		</div>
	<? endforeach; ?>
	<div><!-- [settings:comments] --></div>
<? else: ?>
	<ul>
		<? foreach($conf['tpl']['cat'] as $k=>$v): ?>
		<li><a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?> [<?=$v['cnt']?>]</a></li>
		<? endforeach; ?>
	</ul>
<? endif; ?>