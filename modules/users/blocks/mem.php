<? die; # Нуль

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
} //$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

if(array_key_exists('check', $_POST) && array_key_exists('blocks', $_GET['m'])){
	if($_POST['check']){
		if(!mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_mem WHERE uid=". (int)$conf['user']['uid']. " AND grp_id=6"))){
			mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mem SET uid=". (int)$conf['user']['uid']. ", grp_id=6");
			echo "Контактная информация открыта";
		}
	}else{
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_mem WHERE uid=". (int)$conf['user']['uid']. " AND grp_id=6");
		echo "Контактная информация закрыта";
	} exit;
}

$check = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_mem WHERE uid=". (int)$conf['user']['uid']. " AND grp_id=6"));
$grp = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_grp LIMIT 10"));

?>
<script language="javascript">
	$(document).ready(function(){
		$(".vidimost").change(function(){
			if((id = $(this).attr('id')) == 'yes'){
				$(".vidimost#no").removeAttr("disabled").removeAttr("checked");
				$(".vidimost#yes").attr("disabled", "disabled").attr("checked", "checked");
			}else{
				$(".vidimost#yes").removeAttr("disabled").removeAttr("checked");
				$(".vidimost#no").attr("disabled", "disabled").attr("checked", "checked");
			}
			$.post('/blocks/<?=$arg['blocknum']?>/null', {check:(id == 'yes' ? 1 : 0)}, function(data){
//				alert(data);
			});
		});
	});
</script>
<table>
	<tr>
		<td>Видимость:</td>
		<td>
			<span style="width:200px; float:right;">
				<input class="vidimost" id="yes" type="checkbox"<?=($check ? " checked disabled" : '')?>> Включена
				<input class="vidimost" id="no" type="checkbox"<?=(!$check ? " checked disabled" : '')?>> Выключена
			</span>
		</td>
	</tr>
</table>
