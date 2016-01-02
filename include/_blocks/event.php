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
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

//$link = mpqn(mpqw("SELECT *, p.name, s.name AS sity, 'link' AS event FROM {$conf['db']['prefix']}ymaps_point AS p LEFT JOIN {$conf['db']['prefix']}ymaps_sity AS s ON p.sity_id=s.id ORDER BY p.id DESC LIMIT 1"), 'time');
$user = mpqn(mpqw("SELECT *, 'user' AS event FROM {$conf['db']['prefix']}users ORDER BY id DESC LIMIT 1"), 'reg_time');
$foto = mpqn(mpqw("SELECT i.*, u.name, 'foto' AS event FROM {$conf['db']['prefix']}foto_img AS i LEFT JOIN {$conf['db']['prefix']}users AS u ON i.uid=u.id ORDER BY i.id DESC LIMIT 1"), 'time');
$firms = mpqn(mpqw("SELECT *, 'firms' AS event FROM {$conf['db']['prefix']}firms_index ORDER BY id DESC LIMIT 1"), 'time');
$news = mpqn(mpqw("SELECT p.*, c.name, 'news' AS event FROM {$conf['db']['prefix']}news_post AS p LEFT JOIN {$conf['db']['prefix']}news_kat AS c ON c.id=p.kid ORDER BY id DESC LIMIT 1"), 'time');
$desc = mpqn(mpqw("SELECT d.*, o.name AS oname, u.name AS uname, 'desc' AS event FROM {$conf['db']['prefix']}services_desc AS d LEFT JOIN {$conf['db']['prefix']}services_obj AS o ON o.id=d.obj_id LEFT JOIN {$conf['db']['prefix']}users AS u ON d.uid=u.id ORDER BY id DESC LIMIT 1"), 'time');

$event = $user+$foto+$firms+$news+$desc;
krsort($event);

?>

<!-- [settings:foto_lightbox] -->
<div>
	<? foreach($event as $k=>$v): ?>
		<div style="clear:both;">
			<span style="font-weight:bold;"><?=date('Y.m.d H:i:s', $k)?></span>
			<? if($v['event'] == 'user'): ?>
				<span>Регистрация пользователя </span>
				<span><a href="/users/<?=$v['id']?>"><?=$v['name']?></a></span>
			<? elseif($v['event'] == 'link'): ?>
				<span>Реклама на странице <a href="/ymaps/sity_id:<?=$v['sity_id']?>"><?=$v['sity']?></a></span>
				<div style="font-style:italic;"><?=$v['name']?></div>
			<? elseif($v['event'] == 'foto'): ?>
				<div style="float:right;" id="gallery">
					<a href="/foto:img/<?=$v['id']?>/w:600/h:500/null/img.jpg">
						<img src="/foto:img/<?=$v['id']?>/w:80/h:80/null/img.jpg">
					</a>
				</div>
				<span>Обновление фотоархива </span>
				<span>добавил: <a href="/users/<?=$v['uid']?>"><?=$v['name']?></a></span>
				<span>Все <a href="/foto/<?=$v['uid']?>">фото пользователя <?=$uid[ $v['uid'] ]['name']?></a></span>
				<span>весь <a href="/foto">Фотоархив</a></span>
			<? elseif($v['event'] == 'firms'): ?>
				<span>Новая фирма в каталоге</span>
				<span><?=$v['name']?></span>
				<span>Интернациональное название: <?=$v['intname']?></span>
				<span><a href="/firms">Перейти в каталог</a></span>
			<? elseif($v['event'] == 'news'): ?>
				<span>Новостная лента</span>
				<div><?=$v['tema']?></div>
				<span>Новости категории: <a href="/news/kid:<?=$v['kid']?>"><?=$v['name']?></a></span>
				<span><a href="/news">Все новости</a></span>
			<? elseif($v['event'] == 'desc'): ?>
				<span>Новый товар в магазине</span>
				<span><a href="/am/<?=$v['id']?>"><?=$v['name']?></a></span>
				<span>Категория <a href="/am/oid:<?=$v['obj_id']?>"><?=$v['oname']?></a></span>
				<span>Все товары пользователя <a href="/am/uid:<?=$v['uid']?>"><?=$v['uname']?></a></span>
			<? else: ?>
				<? mpre($v); ?>
			<? endif; ?>
		</div>
	<? endforeach; ?>
</div>
