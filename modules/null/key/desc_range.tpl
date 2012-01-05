<div>
	<script>
		$(function(){
			$(".check").change(function(){
				checked = $(this).is(":checked");
				range_id = $(this).attr("range_id");
				desc_id = $(this).parents("[desc_id]").attr("desc_id");// alert(desc_id);
				$.post("/<?=$arg['modname']?>:<?=$arg['fe']?>/null", {range_id:range_id, desc_id:desc_id, checked:checked}, function(data){
					if(isNaN(data)){ alert(data); }
				});
			});
		});
	</script>
	<div><?=$conf['tpl']['mpager']?></div>
	<? foreach($conf['tpl']['desc'] as $d): ?>
		<div desc_id="<?=$d['id']?>">
			<h3><a target=blank href="/<?=$arg['modpath']?>/<?=$d['id']?>"><?=$d['name']?></a></h3>
			<div style="overflow:hidden;">
				<? foreach($conf['tpl']['range'] as $r): ?>
					<div style="width:50%; float:left;">
						<input class="check" range_id="<?=$r['id']?>" type="checkbox" <?=($conf['tpl']['desc_range'][ $d['id'] ][ $r['id'] ] ? "checked" : "")?>>
						&nbsp;<?=$r['name']?>
					</div>
				<? endforeach; ?>
			</div>
		</div>
	<? endforeach; ?>
	<div><?=$conf['tpl']['mpager']?></div>
</div>