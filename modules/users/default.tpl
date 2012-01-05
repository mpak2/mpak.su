<div>
	<script>
		$(document).ready(function(){
			$("#block_users > div[blocknum] > div:nth-child(1)").click(function(){
				$(this).parents("[blocknum]").find("div:nth-child(2)").slideToggle();
			});
		});
	</script>
	<div id="block_users" style="background-color:#EEEEEE; border-radius:10px;">
		<? if($conf['settings'][$f = "{$arg['modpath']}_{$arg['fn']}"]): ?>
			<!-- [blocks:<?=$conf['settings'][$f]?>] -->
		<? else: ?>
			&#60;!-- [settings:<?=$f?>] --&#62;
		<? endif; ?>
	</div>
</div>