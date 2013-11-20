<? if($options_proposals = $tpl['options_proposals'][ $_GET['id'] ]): ?>
	<div style="margin:10px 0;">
		<span style="float:right;">
			<a href="/users/<?=$options_proposals['uid']?>">
				<?=$tpl['uid'][ $options_proposals['uid'] ]['name']?>
			</a>
		</span>
		<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_options_proposals&where[id]={$options_proposals['id']}")?>
		<span><?=date("d.m.Y", $options_proposals['time'])?></span>
	</div>
	<div><?=$options_proposals['description']?></div>
	<div class="options_commentary">
		<? foreach(rb($tpl['options_commentary'], "options_proposals_id", "id", $options_proposals['id']) as $options_commentary): ?>
			<div style="border-top:1px dashed gray;">
				<div style="margin:10px 0;">
					<span style="float:right;">
						<a href="/users/<?=$options_commentary['uid']?>">
							<?=$tpl['uid'][ $options_commentary['uid'] ]['name']?>
						</a>
					</span>
					<span><?=date("d.m.Y", $options_commentary['time'])?></span>
				</div>
				<h5><?=$options_commentary['name']?></h5>
				<div><?=$options_commentary['description']?></div>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>