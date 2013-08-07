<? die; # Точки

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$guest = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE name=\"". mpquot($conf['settings']['default_usr']). "\""), 0);
$conf['tpl']['placemark'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_placemark WHERE uid=". (int)$arg['uid']));

$type_id= spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_type");
$status = array('Включен', 'Выключен');

?>
<script type="text/javascript" src="/include/jquery/jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		<? foreach($conf['tpl']['placemark'] as $k=>$v): ?>
			$('.jedit[pid=<?=$v['id']?>]').not('#status').not('#type_id').editable(
				"/<?=$arg['modpath']?><?=($arg['fn'] ? ':'. $arg['fn'] : '')?>/<?=$v['id']?>/null",
				{
					submitdata : {pid:"<?=$v['id']?>"},
					width : '90%', //height: 'none', style : 'display: inline', submit : 'Сохранить',
					indicator : "<img src='data:image/jpeg;base64,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'>"
				}
			);
			$('.jedit#type_id[pid=<?=$v['id']?>]').editable(
				"/<?=$arg['modpath']?><?=($arg['fn'] ? ':'. $arg['fn'] : '')?>/null",
				{
					submitdata : {pid:"<?=$v['id']?>"},
					data: '<?=json_encode($type_id)?>',
					type: 'select',
					submit: 'ОК',
					width : '90%', //height: 'none', style : 'display: inline', submit : 'Сохранить',
					indicator : "<img src='data:image/jpeg;base64,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'>"
				}
			);
			$('.jedit#status[pid=<?=$v['id']?>]').editable(
				"/<?=$arg['modpath']?><?=($arg['fn'] ? ':'. $arg['fn'] : '')?>/null",
				{
					submitdata : {pid:"<?=$v['id']?>"},
					data: '<?=json_encode($status)?>',
					type: 'select',
					submit: 'ОК',
					width : '90%', //height: 'none', style : 'display: inline', submit : 'Сохранить',
					indicator : "<img src='data:image/jpeg;base64,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'>"
				}
			);
		<? endforeach; ?>
	});
</script>
<style>
		.jedit, .jarea{
			width:120px;
			height:18px;
			padding:0 0 10px 20px;
			background-image: url(data:image/gif;base64,R0lGODlhEAAQANUjAACd/zuW26XX9MonAAA3nRxvztb6/3W87gyZ+So1Qv/LUv82AF06AHa87pNfAACb///ss2ZlzAuZ+QA0oZeX////+f/65//zzdH6/zGU4qXX9R1vzszM/9L4/x1v1//rpBxv0f/M/5MAAP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAACMALAAAAAAQABAAAAZrwJFwSBwNjsXkaMNZhAbK4UYQaS6gyoKgc4hQrlmB4RCQeEVJraFRfgAmaTEbAXjH1wE6gJAEafB6fEUeGhhkgUkOCoYZEnWCRQ4VChkPbpBEkhYXCpdRDpsQHwpwUQkJEBAMDFFCp6utQ0EAOw==);
			background-repeat: no-repeat;
			margin-left:10px;
		}
	.jtitle {
		font-weight: bold;
		font-style:italic;
	}
</style>
<div style="background:#eee;">
	<div style="border-top:1px solid #ccc;">
	<? foreach($conf['tpl']['placemark'] as $k=>$v): ?>
		<div style="border:1px solid #ccc; border-top:0px solid #ccc; padding:5px; background:white;">
			<? if(($v['uid'] == $conf['user']['uid']) || ($v['uid'] == -$conf['user']['sess']['id'])): ?>
				<div style="float:right; clear:both; position:relative; margin-right:5px;"><a href="/<?=$arg['modpath']?>/drive:<?=$v['id']?>">Двигать</a></div>
			<? endif; ?>
			<table width="100%" border="0">
				<tr>
					<td class="jtitle" style="width:70px;">Статус: </td>
					<td pid="<?=$v['id']?>" id="status" class="jedit" style="color:green;"><?=$status[$v['status']]?></td>
				</tr>
				<tr>
					<td class="jtitle" style="width:70px;">Тип: </td>
					<td pid="<?=$v['id']?>" id="type_id" class="jedit" style="color:green;"><?=$type_id[$v['type_id']]?></td>
				</tr>
				<tr>
					<td class="jtitle" style="width:70px;">Телефон: </td>
					<td pid="<?=$v['id']?>" id="name" class="jedit" style="color:green;"><?=$v['name']?></td>
				</tr>
				<tr>
					<td class="jtitle">Цена: </td>
					<td pid="<?=$v['id']?>" id="price" class="jedit"><?=$v['price']?></td>
				</tr>
				<tr>
					<td class="jtitle">Описание: </td>
					<td pid="<?=$v['id']?>" id="description" class="jedit"><?=$v['description']?></td>
				</tr>
			</table>
		</div>
	<? endforeach; ?>
	</div>
</div>