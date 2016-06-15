<? if($tpl['index'] = rb("index")): ?>
	<? if($tpl['index_theme'] = ql("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index_theme ORDER BY id")): ?>
		<? foreach($tpl['index'] as $index): ?>
			<? if($index['theme'] == ""): ?>
				<? /*if($index_theme = get($tpl['index_theme'], $nn = crc32($index['id'])%(count($tpl['index_theme'])+1))): ?>
					<? if($index["index_theme_id"] == $index_theme['id']): ?>
						<? // mpre("Шаблон уже установлен", $index_theme); ?>
					<? elseif($index = fk("themes-index", array("id"=>$index['id']), null, array("index_theme_id"=>$index_theme['id']))): ?>
						<? mpre($index_theme) ?>
					<? endif; ?>
				<? endif;*/ ?>
				<? foreach($tpl['index_theme'] as $index_theme): ?>
					<? if(4 != $index_theme['id']): ?>
						<? if($index['id']%$index['prime']%$index_theme['id'] == 0): ?>
							<? ($theme = $index_theme) ?>
						<? endif; ?>
					<? endif; ?>
				<? endforeach; ?>

				<? if($index['index_theme_id'] != $theme['id']): ?>
					<? mpre($index = fk("index", array("id"=>$index['id']), null, array('index_theme_id'=>$theme['id']))) ?>
				<? endif; ?>
			<? endif; ?>
		<? endforeach; ?>
	<? endif; ?>
<? endif; ?>
