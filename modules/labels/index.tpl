<? if($$arg['fe'] = $tpl[ $arg['fe'] ][ $_GET['id'] ]): ?>
	<div class="labels">
		<? if($arg['access'] > 3): ?>
			<script>
				$(function(){
					$(".labels").on("click", ".index img", function(event){
						console.log("event:", event);
						if(confirm("Точка "+event.offsetX+"x"+event.offsetY+" Добавить метку?")){
							if(name = prompt("Укажите название метки")){
								$.post("/<?=$arg['modname']?>:ajax/class:points", {name:name, left:event.offsetX, top:event.offsetY, index_id:<?=$index['id']?>}, function(data){
									if(isNaN(data)){ alert(data) }else{
										document.location.href = "/?m[<?=$arg['modname']?>]=admin&r=<?=$conf['db']['prefix']?><?=$arg['modname']?>_points&where[id]="+data;
									}
								});
							}
						}
					}).find(".index img").attr("title", "Клик для установки метки").css("cursor", "pointer");
					
				});
			</script>
		<? endif; ?>
		<span style="float:right;"><?=aedit("/?m[{$arg['modname']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}&where[id]={${$arg['fn']}['id']}")?></span>
		<div>
			<div>
				<h2><?=${$arg['fe']}['name']?></h2>
			</div>
			<div style="font-weight:bold; margin:20px 0;"><?=${$arg['fe']}['description']?></div>
			<div class="index">
				<style>
					.labels .index { position:relative; }
					.labels .index .points { position:absolute; }
					.labels .index .points .bg {
						position:relative;
						margin-left:-40%;
						display: block;
						font-size: 20px;
						text-shadow: 3px #fff;
						text-decoration: none;
						color: #422a2a;
						white-space: nowrap;
						text-shadow: 0 0 2px #fff,  -1px -1px 0 #fff, -2px -2px 1px #fff, -2px -2px 2px #fff;
					}
				</style>
				<img src="/labels:img/<?=$index['id']?>/tn:index/fn:img/null/img.png">
				<? foreach(rb($tpl['points'], "index_id", "id", $index['id']) as $points): ?>
					<div class="points" style="top:<?=$points['top']?>px; left:<?=$points['left']?>px;" title="<?=$points['description']?>">
						<div class="bg"><?=$points['name']?></div>
						<img src="/<?=$arg['modname']?>:img/<?=$points['id']?>/tn:points/fn:img/w:<?=$points['w']?>/h:<?=$points['h']?>/null/img.png">
					</div>
				<? endforeach; ?>
			</div>
			<div><?=${$arg['fe']}['text']?></div>
		</div>
	</div>
<? else: ?>
	<div><?=$tpl['mpager']?></div>
	<? foreach($tpl[ $arg['fe'] ] as $$arg['fe']): ?>
		<div><a href="/<?=$arg['modname']?><?=($arg['fe'] == "index" ? "" : ":{$arg['fe']}")?>/<?=${$arg['fe']}['id']?>"><?=${$arg['fe']}['name']?></a></div>	
	<? endforeach; ?>
	<div><?=$tpl['mpager']?></div>
<? endif; ?>
