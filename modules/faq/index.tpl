<style>
	.faq h2 {margin:20px 0;}
	.qw{
		padding:5px;
		border-top: 1px solid gray;
/*		border-bottom: 1px solid gray;*/
	}
	.qw, .ans{ margin:10px; }
	.ans { margin-left:50px; }
	.href { padding-left:50px; text-align:right; white-space:nowrap; overflow:hidden;}
</style>
<? if($cat = $tpl['cat'][ $_GET['id'] ]): ?>
	<? if(false && $v = $conf['tpl']['user']): ?>
		<div style="overflow:hidden;">
			<div style="float:right; text-align:center;">
				<div><img src="/<?=$conf['modules']['users']['modname']?>:img/<?=$v['id']?>/tn:index/w:50/h:50/c:1/null/img.jpg"></div>
				<div><a href="/<?=$conf['modules']['users']['modname']?>/<?=$c['id']?>"><?=$v['name']?></a></div>
			</div>
			<div style="margin-right:100px; float:left; padding:20px 50px;">
				<h1>Часто задаваемые вопросы</h1>
			</div>
		</div>
	<? endif; ?>
	<? foreach(rb($tpl['index'], "cat_id", "id", $cat['id']) as $index): ?>
		<div>
			<div class="qw"><?=$index['qw']?></div>
			<div class="ans"><?=$index['ans']?></div>
			<? if($index['href']): ?>
				<div class="href"><a href="<?=$index['href']?>">http://<?=mpidn($_SERVER['HTTP_HOST'])?><?=$index['href']?></a></div>
			<? endif; ?>
		</div>
	<? endforeach; ?>
	<div><!-- [settings:comments] --></div>
<? else: ?>
	<ul class="faq">
		<? foreach($conf['tpl']['cat'] as $cat): ?>
			<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_cat&where[id]={$cat['id']}")?>
			<li>
				<h2><a href="/<?=$arg['modpath']?>/<?=$cat['id']?>"><?=$cat['name']?> [<?=count(rb($tpl['index'], "cat_id", "id", $cat['id']))?>]</a></h2>
				<ul>
					<? foreach(rb($tpl['index'], "cat_id", "id", $cat['id']) as $index): ?>
						<div class="qw"><?=$index['qw']?></div>
						<div class="ans"><?=$index['ans']?></div>
					<? endforeach; ?>
				</ul>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>