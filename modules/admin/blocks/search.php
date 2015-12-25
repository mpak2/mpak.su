<? # Поиск

if (array_key_exists('confnum', $arg)){
	if(isset($_POST['update_param'])){
		mpqw("UPDATE {$conf['db']['prefix']}blocks_index SET param_index = '".serialize($param = $_POST['param'])."' WHERE id=". (int)$arg['confnum']);
	}else{
		if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['confnum']}"))))
			$param = unserialize($res[0]['param']);
	}
	
?>
	<script type="text/javascript" src="/include/jquery/toggleformtext.js"></script>
	<script language="javascript">
		$(document).ready(function(){
//			var adv = new Object(1);
			var adv = {
				'users': {
					name: 'Пользователи',
					query: '/?m[users]=admin&where[id]={id}',
					fields: ['name', 'email', 'icq', 'skype', 'tel'],
				},
			}
			for(key in adv){
				var idt = 'div#<?=$conf['db']['prefix']?>'+ key;
				if($(idt+' > #search_name').val() == ''){
					$(idt+' > #search_name').val(adv[key].name);
//					$(idt+' > #search_name').css('background-color', '#ffd');
				}
				if($(idt + ' > #search_query').val() == ''){
					$(idt + ' > #search_query').val(adv[key].query);
//					$(idt+' > #search_query').css('background-color', '#ffd');
				}
				for(k in adv[key].fields){
					$(idt + ' > div#' + adv[key].fields[k]).css('font-weight', 'bold');
				}
			}
		});
	</script>
	<form method='post'>
	<table align='center' border='1' cellspacing='0' cellpadding='5'>
	<? foreach(mpql(mpqw("SHOW TABLES FROM `{$conf['db']['name']}`")) as $k=>$row): ?>
		<?// mpre($row) ?>
		<? if ($tr %4 == 0): ?><tr valign='top'><? endif; ?>
		<td style="padding:5px;">
			<div id="<?=$row["Tables_in_{$conf['db']['name']}"]?>">
				<input type='text' id="search_name" name='param[search_name][<?=$row["Tables_in_{$conf['db']['name']}"]?>]' value='<?=($param['search_name'][$row["Tables_in_{$conf['db']['name']}"]] ?: $conf['settings'][$n = implode("_", array_slice(explode("_", $row["Tables_in_{$conf['db']['name']}"]), 1))] ?: $conf['settings'][ $n ])?>' style='width:100%'>
				<input type='text' id="search_query" name='param[search_query][<?=$row["Tables_in_{$conf['db']['name']}"]?>]' value='<?=($param['search_query'][$row["Tables_in_{$conf['db']['name']}"]] ?: "/". array_pop(array_slice(explode("_", $row["Tables_in_{$conf['db']['name']}"]), 1, 1)). ":admin/r:{$row["Tables_in_{$conf['db']['name']}"]}?where[id]={id}")?>' style='width:100%'>
				<center><b><?=$row["Tables_in_{$conf['db']['name']}"]?></b></center>
				<? foreach(mpql(mpqw("SHOW COLUMNS FROM ". $row['Tables_in_'. $conf['db']['name']])) as $k=>$v): /*if ($v['Field'] == 'id') continue;*/ ?>
					<div id="<?=$v['Field']?>">
						<input type="checkbox" name="param[<?=$row["Tables_in_{$conf['db']['name']}"]?>][<?=$v['Field']?>]"<?=($param[$row["Tables_in_{$conf['db']['name']}"]][$v['Field']] == 'on' ? ' checked' : '')?>><?=$v['Field']?> (<?=$v['Type']?>)
					</div>
				<? endforeach; ?>
			</div>
		</td>
		<? if (++$tr %4 == 0): ?></tr><? endif; ?>
	<? endforeach; ?>
	<tr><td colspan='4' align='right'><input type='submit' name='update_param' value='Сохранить'></td></tr>
	</table></form>

<? return;}else{
	if(($arg['modpath'] == "admin") && ($arg['fn'] == "search") && array_key_exists('search_block_num', $_GET) && ($_GET['search_block_num'] == $arg['blocknum'])){
//		$search = fk("search", $w = array("uid"=>$conf['user']['uid']), $w += array("name"=>$_GET['search']), $w);
	}else{
//		$search = rb("search", "uid", $conf['user']['uid']);
	}
} ?>

<h2>АдминПоиск</h2>
<form action='/<?=$arg['modpath']?>:<?=$arg['fn']?>' method='get'>
	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
		<tr>
		<td align='center'>
			<input type='hidden' name='search_block_num' value='<?=$arg['blocknum']?>'>
			<input type='text' name='search' style="width:100%" value='<?//=$search['name']?>' title="Поиск" placeholder="Поиск по админке">
			<input type='submit' value='Искать'>
		</td>
		</tr>
	</table>
</form>
