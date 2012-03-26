    <div class="FooterMenu">
      <ul>
		<? foreach($menu[0] as $v): ?>
			<li><a href="<?=$v['link']?>"><?=$v['name']?></a></li>
		<? endforeach; ?>
      </ul>
    </div>
