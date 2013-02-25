<!-- [settings:foto_lightbox] -->
<style>
	.fm_name {
		width:20%;
		float:left;
		text-align:right;
		padding-right:5px;
	}
	.fm_data {
		width:70%;
	}
</style>
<div>
		<form method="post" enctype='multipart/form-data'>
			<div>
				<div class="fm_name">Фото:</div><input class="fm_data" type="file" name="img">
			</div>
			<div>
				<div class="fm_name">Производитель:</div>
				<select class="fm_data" name="pid">
					<? foreach($conf['tpl']['producer'] as $k=>$v): ?>
						<option value="<?=$k?>"<?=($_POST['pid'] == $k ? ' selected' : '')?>><?=$v?></option>
					<? endforeach ?>
				</select>
			</div>
			<div>
				<div class="fm_name">Каталог:</div>
				<select class="fm_data" name="oid">
					<? foreach($conf['tpl']['obj'] as $k=>$v): ?>
						<option value="<?=$k?>"<?=($_POST['oid'] == $k ? ' selected' : '')?>><?=$v?></option>
					<? endforeach ?>
				</select>
			</div>
			<div>
				<div class="fm_name">Имя:</div><input class="fm_data" type="text" name="name">
			</div>
			<div>
				<div class="fm_name">Цена:</div><input class="fm_data" type="text" name="price">
			</div>
			<div>
				<div class="fm_name">Количество на складе:</div><input class="fm_data" type="text" name="itogo">
			</div>
			<div>
				<div class="fm_name">Описание: </div><textarea class="fm_data" name="description"></textarea>
			</div>
			<div style="padding-left:20%"><input type="submit"></div>
		</form>
		<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div id="gallery" style="text-align:center; float:left; margin:3px 10px; width:130px;">
					<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:1/w:600/h:500/null/img.jpg">
						<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:1/w:120/h:100/null/img.jpg">
					</a>
				</div>
				<div style="margin:10px;">
					<div style="float:right;"><a onclick="javascript: if (confirm('Вы уверенны?')){return obj.href;}else{return false;}" href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/del:<?=$v['id']?>">Удалить</a></div>
					<div><?=$conf['tpl']['obj'][$v['oid']]?></div>
					<div><?=$conf['tpl']['producer'][$v['pid']]?></div>
					<div style="font-weight:bold;"><?=$v['name']?></div>
					<div><?=$v['description']?></div>
				</div>
			</div>
		<? endforeach; ?>
</div>
<? mpager($conf['tpl']['cnt']); ?>
