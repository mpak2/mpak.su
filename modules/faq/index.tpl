<style>
	.faq h2 {margin:20px 0;}
	.qw{
		padding:5px;
		font-weight:bold;
		border-top: 1px solid gray;
	}
	.qw, .ans{ margin:10px; }
	.text { margin-left:50px; }
	.href { padding-left:50px; text-align:right; white-space:nowrap; overflow:hidden;}
</style>
<? if($cat = rb("cat", "id", $_GET['cat'])): ?>
	<h2><?=$cat['name']?></h2>
	<? foreach(rb("index", "cat_id", "id", $cat['id']) as $index): ?>
		<div>
			<div class="qw"><?=$index['qw']?></div>
			<div class="text"><?=$index['text']?></div>
			<? if($index['href']): ?>
				<div class="href"><a href="<?=$index['href']?>">http://<?=mpidn($_SERVER['HTTP_HOST'])?><?=$index['href']?></a></div>
			<? endif; ?>
		</div>
	<? endforeach; ?>
	<div><!-- [settings:comments] --></div>
<? else: ?>
	<ul class="faq">
		<? foreach(rb("cat") as $cat): ?>
			<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_cat&where[id]={$cat['id']}")?>
			<li>
				<h2>
					<a href="/<?=$arg['modpath']?>/cat:<?=$cat['id']?>">
						<?=$cat['name']?> [<?=count(rb("index", "cat_id", "id", $cat['id']))?>]
					</a>
				</h2>
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
