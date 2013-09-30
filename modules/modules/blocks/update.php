<? die; # Заголовка блока
################################# php код #################################

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	$tpl['reg'] = qn("SELECT * FROM {$conf['db']['prefix']}blocks_reg");
	mpqw("DELETE FROM mp_blocks_reg_modules");
	foreach($tpl['reg'] as $reg){
		if($reg['mid']){
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}blocks_reg_modules SET reg_id=". (int)$reg['id']. ", modules_index=". (int)$reg['mid']);
			mpqw("UPDATE mp_blocks_reg SET term=1 WHERE id=". (int)$reg['id']);
		}else{
			mpqw("UPDATE mp_blocks_reg SET term=0 WHERE id=". (int)$reg['id']);
		}
	}
	if(empty($tpl['reg'][-1])){
		mpqw("INSERT INTO mp_blocks_reg_modules SET reg_id=-1, theme='zhiraf'");
	} if(empty($tpl['reg'][-2])){
		mpqw("INSERT INTO mp_blocks_reg_modules SET reg_id=-2, theme='zhiraf'");
	}
	mpqw("UPDATE mp_blocks SET rid=-1 WHERE file='admin/blocks/modlist.php'");
	mpqw("UPDATE mp_blocks SET rid=-1 WHERE file='admin/blocks/search.php'");
 exit; exit(mpre($tpl['reg']));
}

//$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}");

################################# верстка ################################# ?>
<div class="modules_update">
	<h1>Обновить</h1>
	<div>
		<script>
			$(function(){
				$(".modules_update").on("click", "input[type=button]", function(){
					$.post("/blocks/<?=$arg['blocknum']?>/null", {"post":1}, function(data){
						if(isNaN(data)){ alert(data) }else{
						}
					});
				}).on("click", "a", function(){
					$("#right").hide();
				});
			});
		</script>
		<input type="button" value="Обновить">
		<a>Скрыть</a>
	</div>
</div>