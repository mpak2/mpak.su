		<ul class="<?=$arg['modname']?>_menu" style="overflow:hidden;">
			<script>
				$(function(){
					$(".<?=$arg['modname']?>_menu .new").click(function(){
						$.post("/<?=$arg['modname']?>:ajax/class:projects", {name:"Новый проект"}, function(projects_id){
							if(isNaN(projects_id)){ alert(projects_id) }else{
								document.location.href = "/<?=$arg['modname']?>:params/"+projects_id;
							}
						});
					});
				});
			</script>
			<li style="float:right;margin-left:20px;"><a href="/cost:tasks">Задачи</a></li>
			<li style="float:right;margin-left:20px;"><a href="/cost">Сотрудники</a></li>
			<li style="float:right;margin-left:20px;"><a href="/cost:projects">Проекты</a></li>
			<li><a class="new" href="javascript:">Добавить новый проект</a></li>
		</ul>
