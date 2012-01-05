<div class="CurService" style="vertical-align:top;display: table;">
<!-- [settings:foto_lightbox] -->
<? if($_GET['id']): # Если выбираем конктерный обьект ?>
<div style="float:right;"><img src="/<?=$arg['modpath']?>:img/tn:desc/<?=$conf['tpl']['obj']['id']?>/w:100/h:100/null/img.jpg"></div>
<h2><?=$conf['tpl']['obj']['name']?></h1>
	<? if($arg['access'] >= 4): ?>
		<a href=/?m[<?=$arg['modpath']?>]=admin&where[id]=<?=$conf['tpl']['obj']['id']?>><img src="/img/edit.png"></a>
	<? endif; ?>
	<div><a name="up" class="up"></a>
	<? foreach($conf['tpl']['desc'] as $k=>$v): # Меню подробных описаний ?>
		<a href=#<?=$v['id']?>><?=$v['name']?></a>&nbsp;|
	<? endforeach; ?>
	</div><div style="clear:both"></div>

	<div style="overflow:hidden;">
	<? foreach($conf['tpl']['desc'] as $k=>$v): # Тексты подробных описаний ?>
		<a name="<?=$v['id']?>"></a><h3><?=$v['name']?></h3>
		<p><?=$v['text']?></p>
		<a href="#up" class="lnk">вверх</a>
	<? endforeach; ?>
	</div>

	<? foreach($conf['tpl']['img'] as $k=>$v): # Список фото ?>
		<div id="gallery" style="float:left; margin:10px;">
			<a href="/<?=$arg['modpath']?>:img/tn:img/<?=$v['id']?>/w:600/h:500/null/img.jpg">
				<img src="/<?=$arg['modpath']?>:img/tn:img/<?=$v['id']?>/w:100/h:100/c:1/null/img.jpg">
			</a>
		</div>
	<? endforeach; ?>

<? else: # Если обьект не выбран выводим все обьекты ?>

<!-- [settings:services_title] -->

<?	$file = <<<EOF
<div style="clear:both;"><a href='/{$arg['modpath']}/{id}'>
	<img style="float:left; margin:5px 25px 0 0;" src=/{$arg['modpath']}:img/{id}/tn:obj/w:60/h:60/c:1/null/img.jpg class="mn_img" />
	<h3 style="padding:10px 50px;">{name}</h3>
	<p style="text-align:justify;">{description}</p>
</a></div>
EOF;

	$folder = <<<EOF
{tmp:prefix}
<li style="clear:both;">
	<a href='/{$arg['modpath']}/oid:{id}' onClick="javascript: obj=document.getElementById('dv_{id}'); if(obj.style.display=='block'){obj.style.display='none'}else{obj.style.display='block'}; return false;">
		<img style="float:left; margin:5px;" src="/{$arg['modpath']}:img/tn:obj/{id}/w:60/h:60/c:1/null/img.jpg" class="mn_img" />
		<h3 style="padding:10px 50px;">{name}</h3>
		<p style="text-align:justify;">{description}</p>
	</a>
	<span id='dv_{id}' style='display:{отображение};'>
		<ul style="padding-left:73px;">{folder}</ul>
	</span>
</li>
EOF;

	$shablon = array(
        	'id'=>'id',
        	'pid'=>'pid',
        	'поля'=>array('*'=>'0'),
        	'line'=>array(),
        	'file'=>$file,
        	'folder'=>$folder,

	        'prefix'=>array(
			'+'=>'&nbsp;&nbsp;&nbsp;', # Вертикальная полоса
			'-'=>'&nbsp;&nbsp;&nbsp;', # Пробел
        	),
        	'отображение'=>array('*'=>'none')+spisok("SELECT CONCAT('0', id), 'block' FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE id=".(int)$_GET['id']),
	);
$count = spisok("SELECT o1.id, COUNT(o2.id) FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o1, {$conf['db']['prefix']}{$arg['modpath']}_obj AS o2 WHERE o1.id=o2.pid GROUP BY o1.id");
foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj ORDER BY sort")) as $k=>$v){
	if($count[$v['id']]){
		$v['id'] = "0{$v['id']}";
	}
	$v['pid'] = "0".$v['pid'];
	$data[$v['id']] = $v;
} ?>
<h2><?=$data[$_GET['oid']]['name']?></h1>
<div><?=mptree($data, empty($_GET['oid']) ? '00' : $_GET['oid'], $shablon)?></div>

<!-- <h2>Услуги</h1>
<ul class="nl ServicesList">
<? foreach($conf['tpl']['obj'] as $k=>$v): ?>
	<li>
		<a href=/<?=$arg['modpath']?>/<?=$v['id']?>><img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:60/h:55/c:1/null/img.jpg" class="mn_img"  /></a>
		<a href=/<?=$arg['modpath']?>/<?=$v['id']?>><h3><?=$v['name']?></h3></a>
		<p><?=$v['description']?></p>
	</li>
<? endforeach; ?>
</ul> -->

<? endif; ?>
</div>