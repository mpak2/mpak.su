<? if(!is_array($pages_index = rb('pages-index', 'uid', 'id', $conf['user']['uid'], get($_GET, 'id')))): mpre("Ошибка выборки статьи") ?>
<? elseif($_POST && (!$pages_index = call_user_func(function($pages_index) use($conf){// mpre("Загрузка статьи");
		if($conf['user']['uid'] <= 0){ mpre("Сохранение статей разрешеной только зарегистрированным пользователям");
		}elseif(!$name = trim($_POST['name'])){ mpre("Укажите заголовок статьи");
		}elseif(!$text = trim($_POST['text'])){ mpre("Укажите текст статьи");
		}elseif(!$pages_index = fk("pages-index", null, ['name'=>$name, 'text'=>$text])){ mpre("ОШИБКА сохранения статьи");
		}elseif(($IMG = get($_FILES, 'img')) && (!$PAGES_INDEX_IMGS = call_user_func(function($IMG) use($conf, $pages_index){// mpre($IMG);
				foreach($IMG['error'] as $key=>$error){
					if(!$name = $IMG['name'][$key]){ exit(!mpre("Имя файла не установлено"));
					}elseif($error = $IMG['error'][$key]){ mpre("Код ошибки `{$error}` для файла `{$name}` поле `{$param_name}`");
					}elseif(!$pages_index_imgs = fid("pages-index_imgs", "img", null, $key)){ mpre("Ошибка установки изображения");
					}elseif(!$pages_index_imgs = fk("pages-index_imgs", ['id'=>$pages_index_imgs['id']], null, ['index_id'=>$pages_index['id']])){ mpre("ОШИБКА обновления изображения ссылкой на статью");
					}else{// mpre($fid);
						$PAGES_INDEX_IMGS[$pages_index_imgs['id']] = $pages_index_imgs;
					}
				} return $PAGES_INDEX_IMGS;
			}, $IMG))){ mpre("ОШИБКА загрузки изображений", $_FILES);
		}else{// mpre($pages_index);
			header("Location: /pages/{$pages_index['id']}");
			return $pages_index;
		}
	}, $pages_index))): mpre("Ошибка сохранения статьи"); ?>
<? else: // pre($pages_index ? "Адрес: <a href=''>{$pages_index['name']}</a>" : "Новая статья") ?>
	<form enctype="multipart/form-data" method="post">
		<script sync>
			(function($, script){
				$(script).parent().on("click", "input[type=button]", function(e){
					var img = $(e.delegateTarget).find(".imgs");
					var p = $(img).find("p:first").clone();
					console.log("p:", p, 'img:', img);
					$(p).prependTo(img);
				})
			})(jQuery, document.currentScript)
		</script>
		<p><input type="file" multiple accept="image/*,image/jpeg,image/png" name="img[]" value="<?=get($pages_index, 'img')?>"></p>
		<p><input type="text" required placeholder="Заголовок статьи" name="name" value="<?=get($pages_index, 'name')?>" style="width:100%;"></p>
		<p><input type="text" required placeholder="Ссылка на видео c youtube.com" name="youtube" value="<?=get($pages_index, 'youtube')?>" style="width:100%;"></p>
		<? if($conf['user']['gid']['1']): ?>
			<p><?=mpwysiwyg("text", get($pages_index, 'text'))?></p>
		<? else: ?>
			<p><textarea required type="text" placeholder="Текст статьи" name="text" value="<?=get($pages_index, 'text')?>" style="min-height:250px;width:100%;"></textarea></p>
		<? endif; ?>
		<p><button type="submit">Добавить</button></p>
	</form>
<? endif; ?>
