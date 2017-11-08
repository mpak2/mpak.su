<style>
	.table-raw:nth-child(2) .table-cell {border:1px solid #808080; border-left: 1px;}
	.table-cell {display:table-cell;width:200px;padding:3px;border:1px solid #808080; border-top:1px; border-left:1px;vertical-align: middle;}
	.table-cell:nth-child(2) {width:350px;}
</style>
	<? if(!$THEMES_INDEX = rb("themes-index")): mpre("Список хостов не найден"); ?>
	<? elseif(!$admin_gethostbyname = explode(',', get($conf, 'settings', 'admin_gethostbyname') ?: $_SERVER['SERVER_ADDR'])): mpre("Текущий адрес сервера") ?>
	<? elseif(!$_THEMES_INDEX = array_map(function($themes_index) use($admin_gethostbyname){// mpre($themes_index);
				if(!$themes_index['_idn_to_ascii'] = idn_to_ascii($themes_index["name"])){ mpre("Ошибка конвертации русскоязычных имен");
				}elseif(!$themes_index['_gethostbyname'] = gethostbyname($themes_index['_idn_to_ascii'])){ mpre("Получение ip адреса хоста");
				}elseif(!is_numeric($key = array_search($themes_index['_gethostbyname'], $admin_gethostbyname))){// mpre("ip адреса нет среди перечисленных");
					return $themes_index + ['_color'=>'red'];
				}elseif(!$key){// mpre("ip адрес указан первым");
					return $themes_index + ['_color'=>'green'];
				}else{// mpre("ip адрес указан не первым");
					return $themes_index + ['_color'=>'blue'];
				}
			return $themes_index;
		}, $THEMES_INDEX)): mpre("Ошибка расчета цвета хоста") ?>
	<? else:// mpre($admin_gethostbyname, $_THEMES_INDEX) ?>
		<span style="float:right;">
			<a href="/settings:admin/r:mp_settings?&where[name]=admin_gethostbyname">Адрес на сервере (через запятую)</a>
		</span>
		<h1>Домены по ip</h1>
		<div class="table">
			<? foreach($_THEMES_INDEX as $_themes_index): ?>
				<div>
					<span style="width:20%;"><?=$_themes_index["name"]?></span>
					<span><p style="color:<?=$_themes_index['_color']?>;"><?=$_themes_index['_gethostbyname']?></p></span>
				</div>
			<? endforeach; ?>
		</div>
	<? endif; ?>
	
