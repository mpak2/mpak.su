<div class="admin_install">
	<style>
		.admin_install .table.brd {border-collapse:collapse;}
		.admin_install .table.brd > div > span {border:1px solid #ccc; padding:2px; width:10%;}
	</style>
	<script sync>
		(function($, script){
			$(script).parent().on("click", "a.install", function(e){
				var modpath = $(e.currentTarget).attr("modpath");
				$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/null", {install:modpath}, function(response){
					if(isNaN(response)){
						alert(response);
						document.location.reload(true);
					}else{
						console.log("response:", response);
					}
				})
			}).on("click", "a.uninstall", function(e){
				var modpath = $(e.currentTarget).attr("modpath");
			}).trigger("init")
		})(jQuery, document.scripts[document.scripts.length-1])
	</script>
	
	<div style="width:80%; margin-left:20px;">
		<? $tpl['admin'] = rb("{$conf['db']['prefix']}admin") ?>
		<h1>Разделы установленные на сайте</h1>
		<div class="table brd">
			<div class="th">
				<span>Директория</span>
				<span>Название</span>
				<span>Автор</span>
				<span>Версия</span>
				<span>Раздел</span>
				<span>Удаление</span>
			</div>
			<? foreach($tpl['modules'] = rb("index") as $modules): ?>
				<div>
					<span><?=$modules['folder']?></span>
					<span><?=$modules['name']?></span>
					<span><?=$modules['author']?></span>
					<span><?=$modules['version']?></span>
					<span>
						<? if($admin = rb($tpl['admin'], "id", $modules['admin'])): ?>
							<?=$admin['name']?>
						<? else: ?>
							<span style="color:red;"><?=$modules['admin']?></span>
						<? endif; ?>
					</span>
					<span><a class="uninstall" modpath="<?=$modules['folder']?>" href="javascript:void(0)">Удалить</a></span>
				</div>
			<? endforeach; ?>
		</div>
		<h1>Доступные для устновки разделы сайта</h1>
		<div class="table brd">
			<div class="th">
				<span>Директория</span>
				<span>Название</span>
				<span>Автор</span>
				<span>Версия</span>
				<span>Раздел</span>
				<span>Установка</span>
			</div>
			<? foreach(array_diff_key(array_flip(mpreaddir("modules", 1)), rb("index", "folder")) as $m=>$k): ?>
				<div>
					<? inc("modules/{$m}/info.php") ?>
					<span><?=$m?></span>
					<span><?=get($conf, 'modversion', 'name')?></span>
					<span><?=get($conf, 'modversion', 'author')?></span>
					<span><?=get($conf, 'modversion', 'version')?></span>
					<span>
					<? if($admin = rb($tpl['admin'], "id", get($conf, 'modversion', 'admin'))): ?>
						<?=$admin['name']?>
					<? else: ?>
						<span style="color:red;"><?=get($conf, 'modversion', 'admin')?></span>
					<? endif; ?>
					</span>
					<span><a class="install" modpath="<?=$m?>" href="javascript:void(0)">Установить</a></span>
				</div>
			<? endforeach; ?>
		</div>
	</div>
</div>