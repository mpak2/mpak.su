<h1>Копирование общих метатегов на другие ЖК</h1>
<? if(!$seo_data = rb('seo-data', 'themes-index', '[,NULL]')): mpre("Ошибка выборки дефолтных значений") ?>
<? elseif(!$SEO_DATA_VALUES = rb('seo-data_values', 'data_id', 'id', $seo_data['id'])): mpre("Ошибка выборки скрытых значений") ?>
<? elseif(!$SEO_DATA_TAG = rb('seo-data_tag', 'id', 'id', rb($SEO_DATA_VALUES, 'data_tag_id'))): mpre("Ошибка выборки списка тегов") ?>
<? elseif($themes_index = rb('themes-index', 'id', get($_GET, 'themes-index'))): mpre("Формирование микроразметки для <a href='//{$themes_index['name']}'>{$themes_index['name']}</a>") ?>
	<? if(!$data = fk('seo-data', $w = ['themes-index'=>$themes_index['id']], $w += ['name'=>$themes_index['name'], 'hide'=>0])): mpre("Ошибка добавления даты") ?>
	<? elseif(!$hh_blocks = rb('hh-blocks', 'id', $themes_index['hh_blocks'])): mpre("Ошибка выборки ЖК сайта") ?>
	<? elseif(!$hh_builders = rb('hh-builders', 'id', $hh_blocks['builders_id'])): mpre("Ошибка выборки застройщика сайта") ?>
	<? elseif(!$hh_regions = rb('hh-regions', 'id', $hh_blocks['regions_id'])): mpre("Ошибка определения района ЖК") ?>
	<? elseif(!$zam = ['hh-blocks'=>$hh_blocks, 'hh-builders'=>$hh_builders, 'hh-regions'=>$hh_regions, 'themes-index'=>$themes_index]): mpre("Ошибка формирования массива замены") ?>
	<? elseif(!$INFO = array_map(function($key, $zam){
			if(!$name = (get(['hh-blocks'=>'Жилой комплекс', 'hh-builders'=>'Информация о застройщике', 'hh-regions'=>'Район жилого комплекса', 'themes-index'=>'Свойства сайта'], $key) ?: $key)){ mpre("Ошибка поиска описания таблицы");
			}else{ mpre($name, mpzam([$key=>$zam]));
			}
		}, array_keys($zam), $zam)): mpre("Ошибка формирования информационного массива") ?>
	<?// elseif(!mpre($ZAM)): mpre("Ошибка вывода массива замены") ?>
	<? elseif(!$ZAM = mpzam($zam)): mpre("Ошибка формирования массива замены") ?>
	<? elseif(!$_SEO_DATA_VALUES = array_map(function($seo_data_values) use($themes_index, $data, $SEO_DATA_TAG, $ZAM){
			if(!$seo_data_tag = rb($SEO_DATA_TAG, 'id', $seo_data_values['data_tag_id'])){ mpre("Ошибка выборки тега значения");
			}elseif(!is_string($name = strtr($seo_data_values['name'], $ZAM))){ mpre("Ошибка замены тегов в значении");
			}elseif(!$data_values = fk('seo-data_values', $w = ['data_id'=>$data['id'], 'data_values_id'=>$seo_data_values['id']], $w += ['hide'=>1, 'data_href_id'=>$seo_data_values['data_href_id'], 'data_tag_id'=>$seo_data_values['data_tag_id'], 'name'=>$name], $w)){ mpre("Ошибка добавления нового значения");
			}else{  mpre("{$seo_data_values['id']}. {$seo_data_tag['name']}", $seo_data_values['name'], $name);
			}
		}, $SEO_DATA_VALUES)): mpre("Ошибка формирования тегох сайта") ?>
	<? else: mpre("Теги успешно сформированы") ?>
	<? endif; ?>
<?// elseif(!$SEO_DATA_VALUES = rb('seo-data_values', 'hide', 'id', 1)): mpre("Ошибка выборки скрытых значений") ?>
<?// elseif(!$SEO_DATA_TAG = rb('seo-data_tag', 'id', 'id', rb($SEO_DATA_VALUES, 'data_tag_id'))): mpre("Ошибка выборки списка тегов") ?>
<? elseif(!is_array($SEO_DATA_HREF = rb('seo-data_href', 'id', 'id', rb($SEO_DATA_VALUES, 'data_href_id')))): mpre("Ошибка выборки всех ссылок значений") ?>
<? elseif(!$SEO_DATA = rb('seo-data', 'id', 'id', rb($SEO_DATA_VALUES, 'data_id'))): mpre("Ошибка выборки даты") ?>

<?// elseif(!$SEO_DATA_VALUES = rb('seo-data_values', 'data_id', 'data_href_id', 'id', "[". get($seo_data, 'id'). ",0,NULL]", "[". get($seo_data_href, 'id'). ",0,NULL]")):// mpre("Ошибка выборки данных микроразметки") ?>
<?// elseif(mpre($SEO_DATA_VALUES)): ?>
<?// elseif(!$SEO_DATA_TAG = rb('seo-data_tag', 'id', 'id', rb($SEO_DATA_VALUES, 'data_tag_id'))): mpre("Ошибка выборки тегов значений") ?>
<? elseif(!$_SEO_DATA_TAG = array_filter(array_map(function($seo_data_tag){ return ($seo_data_tag['data_tag_id'] ? $seo_data_tag : null); }, $SEO_DATA_TAG))):// mpre("Ошибка получения вложенных элементов") ?>
<? elseif(!$json = call_user_func(function($SEO_DATA_VALUES) use($SEO_DATA_TAG, $_SEO_DATA_TAG){ # Массив ключей и значений
		if(!$SEO_DATA_TAG_[$n = 'Корневые'] = array_filter(array_map(function($seo_data_tag){
				return ($seo_data_tag['data_tag_id'] ? null : $seo_data_tag);
			}, $SEO_DATA_TAG))){ mpre("Ошибка формирования списка `{$n}`");
		}elseif(!$tags = array_column($SEO_DATA_TAG_['Корневые'], 'alias', 'id')){ mpre("Ошибка формирования списка тегов");
		}elseif(!$values = array_replace($tags, array_intersect_key(array_column($SEO_DATA_VALUES, 'name', 'data_tag_id'), $tags))){ mpre("Ошибка формирования списка значений");
		}elseif(!$rep = call_user_func(function($SEO_DATA_TAG) use($SEO_DATA_VALUES){
				if(!$_SEO_DATA_TAG = rb($SEO_DATA_TAG, 'data_tag_id', 'id')){
				}elseif(!$_SEO_DATA_TAG = array_map(function($_DATA_TAG) use($SEO_DATA_VALUES){
						if(!$tags = array_column($_DATA_TAG, 'alias', 'id')){ mpre("Ошибка формирования списка тегов");
						}elseif(!is_array($default = array_filter(array_column($_DATA_TAG, 'value', 'id')))){ mpre("Ошибка выборки значений по умолчанию");
						}elseif(!$values = array_replace($tags, array_column($SEO_DATA_VALUES, 'name', 'data_tag_id'))){ mpre("Ошибка формирования списка значений");
						}elseif(!$value = array_replace($default, array_filter($values))){ mpre("Ошибка убирания лишних элементов");
						}elseif(!$rep = array_combine(array_intersect_key($tags, $value), array_intersect_key($value, $tags))){ mpre("Ошибка формирования json массива");
						}else{ return $rep; }
					}, $_SEO_DATA_TAG)){ mpre("Ошибка установки значения в тегах");
				}else{ return $_SEO_DATA_TAG; }
			}, $_SEO_DATA_TAG)){ mpre("Ошибка формирования многоуровневой замены");
		}elseif(!$values = array_replace($values, $rep)){ mpre("Ошибка установки значений корневых элементов");
		}elseif(!$json = array_combine($tags, array_intersect_key($values, $tags))){ mpre("Ошибка формирования json массива");
		}else{// mpre($rep, $json);
			return $json;
		}
	}, $SEO_DATA_VALUES)): mpre("Ошибка формирования структуры тегов") ?>
<? elseif(!$THEMES_INDEX = rb('themes-index')): mpre("Ошибка выборки хостов") ?>
<? else:// mpre($json) ?>
	<div class="table">
		<div>
			<span><? mpre($json) ?></span>
			<span>
				<ul>
					<? foreach($THEMES_INDEX as $themes_index): ?>
						<? if(!is_array($seo_data = rb('seo-data', 'themes-index', $themes_index['id']))): mpre("Ошбка выбоки хоста") ?>
						<? elseif(!is_array($_SEO_DATA_VALUES = ($seo_data ? rb('seo-data_values', 'data_id', 'id', get($seo_data, 'id')) : []))): mpre("Ошибка выборки свойств хоста") ?>
						<? elseif(!is_string($cnt = ((string)count($_SEO_DATA_VALUES) ?: ""))): mpre("Ошибка расчета строки счетчика") ?>
						<? else: ?>
							<li>
								<a target="blank" href="/seo:admin_data_replace/themes-index:<?=$themes_index['id']?>"><?=$themes_index['name']?></a> <?=$cnt?>
							</li>
						<? endif; ?>
					<? endforeach; ?>
				</ul>
			</span>
		<div>
	</div>
<? endif; ?>