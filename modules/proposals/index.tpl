<? if($$arg['fe'] = $tpl[ $arg['fe'] ][ $_GET['id'] ]): ?>
	<div class="poll" index_id="<?=$index['id']?>">
<!--		<span style="float:right;">
			<a href="/<?=$arg['modname']?><?=($arg['fe'] == "index" ? "" : ":{$arg['fe']}")?>">Весь список</a>
		</span>-->
		<span style="float:right;"><?=aedit("/?m[{$arg['modname']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}&where[id]={${$arg['fn']}['id']}")?></span>
		<div>
			<h2><?=${$arg['fe']}['name']?></h2>
		</div>
		<div style="font-weight:bold; margin:20px 0;"><?=${$arg['fe']}['description']?></div>
		<div class="options">
			<script>
				$(function(){
					$(".poll").on("click", ".options [options_id] input[type=submit]", function(event){
						var text = $(this).parents("[options_id]").find("textarea").val();
						if(text.length > 0){
							var options_id = $(this).parents("[options_id]").attr("options_id");
							$.post("/<?=$arg['modname']?>:ajax/class:options_proposals", {options_id:options_id, description:text}, $.proxy(function(data){
								if(isNaN(data)){ alert(data) }else{
									$(this).parents("[options_id]").find("textarea").css("background-color", "#aaffaa");
									alert("Ваше предложение сохранено и будет передано администрации портала.");
								}
							}, this));
						}else{
							$(this).parents("[options_id]").find("textarea").css("background-color", "#ffaaaa");
						}
							setTimeout($.proxy(function(){
								$(this).parents("[options_id]").find("textarea").css("background-color", "").val("");
							}, this), 500);
					});
				});
			</script>
			<? foreach(rb($tpl['options'], "index_id", "id", $index['id']) as $options): ?>
				<div options_id="<?=$options['id']?>">
					<h4><?=(++$nn)?>. <?=$options['name']?></h4>
					<div><textarea style="width:100%;" placeholder="Напишите ваше предложение"></textarea></div>
					<input type="submit" value="Добавить">
				</div>
			<? endforeach; ?>
		</div>
		<div style="font-weight:bold; margin:20px 0;"><?=${$arg['fe']}['text']?></div>
	</div>
<? else: ?>
	<div><?=$tpl['mpager']?></div>
	<? foreach($tpl[ $arg['fe'] ] as $$arg['fe']): ?>
		<div>
			<h2><a href="/<?=$arg['modname']?><?=($arg['fe'] == "index" ? "" : ":{$arg['fe']}")?>/<?=${$arg['fe']}['id']?>"><?=${$arg['fe']}['name']?></a></h2>
		</div>	
	<? endforeach; ?>
	<div><?=$tpl['mpager']?></div>
<? endif; ?>
