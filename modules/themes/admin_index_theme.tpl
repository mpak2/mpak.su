<h1>Шаблоны для сайтов</h1>
<? if(!$INDEX = rb("index")): mpre("Хосты не найдены") ?>
<? elseif(!$_INDEX = rb($INDEX, "theme", "id")): mpre("Ошибка распределения сайтов по темам") ?>
<? else: ?>
	<? foreach($_INDEX as $theme=>$_INDEX_): ?>
		<div>
			<h2><?=$theme?></h2>
			<ul>
				<? foreach($_INDEX_ as $index): ?>
					<li style="display:inline-block; margin-right:20px;">
						<a href="//<?=$index['name']?>"><?=$index['name']?></a>
					</li>
				<? endforeach; ?>
			</ul>
		</div>
	<? endforeach; ?>
<? endif; ?>
