<script>
	$(function(){
		$(".projects .del").click(function(){
			if(confirm("Подтвердите удаление проекта")){
				var projects_id = $(this).parents("[projects_id]").attr("projects_id");// alert(projects_id);
				$.post("/<?=$arg['modname']?>:ajax/class:projects/null", {id:-projects_id}, function(data){
					if(isNaN(data)){ alert(data) }
				});
			}
		});
	});
</script>
<? if($e = $tpl['projects'][ $_GET['id'] ]):// mpre($e); ?>
<script src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js"></script>
<script id="desc" type="text/x-jquery-tmpl">
    <li>${Name} ({{html Desc}}); вычисления: ${p1 + p2 + 10}</li>
</script>
<script type="text/javascript">
	var data = [
		{ Name: "obj1", Desc: "<em>Object one</em>", p1: 1, p2: 10 },
		{ Name: "<em>obj2</em>", Desc: "<strong>Object two</strong>", p1: 3.3, p2: 5 }
	];
	var options = {
	};
	$(function() {
		$("#desc").tmpl(data, options).appendTo( $(".output").empty() );
	});
</script>
<ul class="output"></ul>
	<div class="projects" projects_id="<?=$e['id']?>">
		<span style="float:right;"><a href="/<?=$arg['modname']?>:projects/uid:<?=$conf['user']['uid']?>">Мои проекты</a></span>
		<h1><?=$e['name']?></h1>
		<script src="/include/jquery/my/jquery.klesh.js"></script>
		<script>
			$(function(){
				$(".klesh.name").klesh("/<?=$arg['modname']?>:ajax", function(){
					var val = $(this).attr("val");// alert(val);
					$(".projects h1").text(val);
				});
			});
		</script>
		<div style="float:right;"><a class="del" href="javascript:">Удалить</a></div>
		<div id="<?=$e['id']?>" class="projects name klesh"><?=$e['name']?></div>
	</div>
<? else: ?>
	<div class="projects">
		<style>
			.projects.list > div > span { display:inline-block; min-width:20px; }
		</style>
		<? foreach($tpl['projects'] as $v): ?>
			<div projects_id="<?=$v['id']?>">
				<span>
					<? if($v['uid'] == $conf['user']['uid']): ?>
						<a class="del" href="javascript:">
							<img src="/img/del.png">
						</a>
					<? endif; ?>
				</span>
				<span><a href="/cost:params/<?=$v['id']?>"><?=$v['name']?></a></span>
				<span style="float:right;">
					<?=($v['uid'] > 0 ? $v['uname'] : "{$conf['settings']['default_usr']}{$v['uid']}")?>
				</span>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>