<div id="menu_list">
	<? foreach($menu as $k=>$v): ?>
        <a href="<?=$v['link']?>"><?=$v['name']?></a>
        <img src="/themes/null/images/splitter.gif" class="splitter" alt="" />
	<? endforeach; ?>
</div>
