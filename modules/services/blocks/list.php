<? die; # УслугиСписок

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

	$file = <<<EOF
<li class="nl">
	<a href='/{$arg['modpath']}/{id}/mn:{pid}'>
		{tmp:prefix}{line}{name}
	</a>
</li>
EOF;

	$folder = <<<EOF
{tmp:prefix}
<li class="tit1">
	<a href='/{$arg['modpath']}/oid:{id}/mn:{id}' onClick="javascript: obj=document.getElementById('div_{id}'); if(obj.style.display=='block'){obj.style.display='none'}else{obj.style.display='block'}; return false;">
		{line} {name}
	</a>
</li>
	<span id='div_{id}' style='display:{отображение};'>
		<ul style="padding-left:10px;">{folder}</ul>
	</span>
EOF;

	$shablon = array(
        	'id'=>'id',
        	'pid'=>'pid',
        	'поля'=>array('*'=>'0'),
        	'line'=>array(
//                		'++'=>'<img src=/img/tree_plus.png border=0>', # Закрытая не последняя директория
//                        '+-'=>'<img src=/img/tree_pplus.png border=0>', # Закрытая последняя директория
//                        '-+'=>'[3]', # Открытая не последняя директория
//                        '--'=>'[4]', # Открытая последняя директория
//                        '+'=>'<img src=/img/tree_split.png border=0>', # Не последний файл
//                        '-'=>'<img src=/img/tree_psplit.png border=0>', # Последний файл
//                        'null'=>'[7]' # Верктикальная линия
        	),
        	'file'=>$file,
        	'folder'=>$folder,

	        'prefix'=>array(
			'+'=>'', # Вертикальная полоса
			'-'=>'', # Пробел
        	),
        	'отображение'=>array('*'=>'none')+spisok("SELECT CONCAT('0', id), 'block' FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE id=".(int)$_GET['mn'].((int)$_GET['mn'] ?  " OR pid=".(int)$_GET['mn'] : '')),
	);
$count = spisok("SELECT o1.id, COUNT(o2.id) FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o1, {$conf['db']['prefix']}{$arg['modpath']}_obj AS o2 WHERE o1.id=o2.pid GROUP BY o1.id");
foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj")) as $k=>$v){
	if($count[$v['id']]){
		$v['id'] = "0{$v['id']}";
	}else{
		$v['id'] = $v['id'];
	}
	$v['pid'] = "0".$v['pid'];
	$data[] = $v;
}
echo "<ul class=\"nl\">".mptree($data, '00', $shablon)."</ul>";

?>