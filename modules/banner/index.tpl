<? if($_GET['id']): ?>
	<script type="text/javascript" src="/include/jquery/jquery.jeditable.js"></script>
	<? if($arg['access'] > 1): ?>
		<script type="text/javascript">
			$(document).ready(function() {  
				$('.jedit').editable(
					"/<?=$arg['modpath']?><?=($arg['fn'] ? ':'. $arg['fn'] : '')?>/<?=$_GET['id']?>/null",
					{
						width : '80%', //height: 'none', style : 'display: inline', submit : 'Сохранить',
						indicator : "<img src='data:image/jpeg;base64,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'>"
					}
				);
				<? foreach($conf['tpl'] as $k=>$v): if(substr($k, -3) != '_id') continue;?>
				$('#<?=$k?>').editable(
					"/<?=$arg['modpath']?><?=($arg['fn'] ? ":{$arg['fn']}" : '')?>/<?=$_GET['id']?>/null",
					{
						data : '<?=json_encode($v)?>',
						style : 'display: inline',
						type : 'select',
						submit : 'OK',
						indicator : "<img src='data:image/jpeg;base64,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'>"
					}
				);
				<? endforeach; ?>
			});
		</script>
		<style>
			.jedit, .jarea{
#				border:1px solid red;
				width:100%;
				height:18px;
#				float:left;
				padding:0 0 0 20px;
				background-image: url(data:image/gif;base64,R0lGODlhEAAQANUjAACd/zuW26XX9MonAAA3nRxvztb6/3W87gyZ+So1Qv/LUv82AF06AHa87pNfAACb///ss2ZlzAuZ+QA0oZeX////+f/65//zzdH6/zGU4qXX9R1vzszM/9L4/x1v1//rpBxv0f/M/5MAAP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAACMALAAAAAAQABAAAAZrwJFwSBwNjsXkaMNZhAbK4UYQaS6gyoKgc4hQrlmB4RCQeEVJraFRfgAmaTEbAXjH1wE6gJAEafB6fEUeGhhkgUkOCoYZEnWCRQ4VChkPbpBEkhYXCpdRDpsQHwpwUQkJEBAMDFFCp6utQ0EAOw==);
				background-repeat: no-repeat;
				margin:5px 10px;
			}
		</style>
	<? endif; ?>
	<h1 title="<?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}"] ?: $fn)?>: <?=$conf['tpl'][$arg['fn']]['name']?>">
		<?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}"] ?: $fn)?>: <?=$conf['tpl'][$arg['fn']]['name']?>
	</h1>
	<div style="overflow:hidden;">
		<? if($conf['tpl'][$arg['fn']]['img']): ?>
			<div style="float:right;">
				<a href="/<?=$arg['modpath']?>:img/<?=$conf['tpl'][$arg['fn']]['id']?>/tn:<?=$arg['fn']?>/w:600/h:400/null/img.jpg">
					<img src="/<?=$arg['modpath']?>:img/<?=$conf['tpl'][$arg['fn']]['id']?>/tn:<?=$arg['fn']?>/w:120/h:100/null/img.jpg">
				</a>
			</div>
		<? endif; ?>
		<ul style="width:100%;">
		<? foreach(array_diff_key($conf['tpl'][$arg['fn']], array('id'=>'', 'uid'=>'', 'img'=>'', 'disabled'=>'')) as $k=>$v): ?>
			<li>
				<div style="font-weight:bold;" title="<?=($fn = "{$arg['modpath']}_{$arg['fn']}_{$k}")?>">
					<?=($conf['settings'][$fn] ?: $fn)?> :
				</div> <div id="<?=$k?>" class="<?=($conf['tpl'][$k] ? 'jarea jarea_'. $k : 'jedit')?>"><?=($conf['tpl'][$k] ? $conf['tpl'][$k][$v] : $v)?></div>
			</li>
		<? endforeach; ?>
		</ul>
		<? foreach((array)$conf['tpl']['fk'] as $n=>$m): ?>
			<h2 style="clear:both;"><?=($conf['settings'][$fn = "{$arg['modpath']}_$n"] ?: $fn)?></h2>
			<ul>
				<? foreach($m as $k=>$v): ?>
				<li>
					<div><a href="/<?=$arg['modpath']?>:<?=$n?>/<?=$v['id']?>"><?=$v['name']?></a></div>
					<div style="margin-left:10px; font-style:italic;"><?=$v['description']?></div>
				</li>
				<? endforeach; ?>
			</ul>
		<? endforeach; ?>
	</div>
<? else: ?>
	<h1 style="margin:10px;" title="<?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}"] ?: $fn)?>">
		<?=($conf['settings'][$fn = "{$arg['modpath']}_{$arg['fn']}"] ?: $fn)?>
	</h1>
	<ul style="width:100%;">
		<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
			<li style="width:90%; clear:both;">
				<div style="float:right; margin-left:5px;">
					<a href="/<?=$arg['modpath']?><?=$f != 'index' ? ":{$arg['fn']}" : ''?>/<?=$v['id']?>">Подробно</a>
				</div>
				<div style="float:right; margin-left:5px;">
					<? if($conf['user']['id'] == $v['uid']): ?>
						<a onclick="javascript: if (confirm('Вы уверенны?')){return obj.href;}else{return false;}" href="/<?=$arg['modpath']?>/<?=$v['id']?>/del"> Удалить</a>
					<? endif; ?>
				</div>
				<div><?=$v['name']?></div>
			</li>
		<? endforeach; ?>
	</ul>
	<div style="margin:10px;"><?=$conf['tpl']['mpager']?></div>
<? endif; ?>