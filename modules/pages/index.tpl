<? if(get($_GET, 'id')): ?>
	<? if($index = rb("index", "id", $_GET['id'])): ?>
		<?/* if($menu_index = rb($tpl['menu_index'], "href", array_flip(array($_SERVER['REQUEST_URI'])))): ?>
			<div class="navigation">
				<? $function = function($menu_index) use(&$function, $tpl, $arg){ ?>
					<? if($mi = $tpl['menu_index'][ $menu_index['index_id'] ]) $function($mi); ?>
					 &raquo; <a href="<?=$menu_index['href']?>"><?=$menu_index['name']?></a>
				<? }; $function($tpl['menu_index'][ $menu_index['index_id'] ]); ?>
			</div>
		<? endif;*/ ?>

		<? if($pages_cat = rb("cat", "id", $index['cat_id'])): ?>
			<div class="bradcrumbs">
				<? $f = function($pages_cat) use(&$f, $tpl, $arg){ ?>
					<? if($cat = rb("cat", "id", $pages_cat['cat_id'])): ?>
						<? $f($cat) ?>
					<? endif; ?>
					&raquo; <a href="/<?=$arg['modpath']?>:cat/<?=$pages_cat['id']?>"><?=$pages_cat['name']?></a>
				<? }; $f($pages_cat); ?>
			</div>
		<? endif; ?>
		
		<div><?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[id]=". (int)$index['id'])?></div>
		<h1><?=$index['name']?></h1>
		<? if(get($index, 'img')): ?>
			<img src="/pages:img/<?=$index['id']?>/tn:index/fn:img/w:300/h:300/null/img.png">
		<? endif; ?>
		<div><?=$index['text']?></div>
		<div style="margin-top:20px;"><?=get($conf, 'settings', 'comments')?></div>
	<? else: ?>
		<div style="margin-top:150px; text-align:center;">
			Данная страница не найдена на сайте.<br />Возможно она была удалена.
			<? if($arg['admin_access'] > 3): ?>
				<script>
					$(function(){
						$("a.add").on("click", function(){
							$.post("/<?=$arg['modpath']?>/<?=$_GET['id']?>/null", {id:<?=$_GET['id']?>}, function(data){
								if(isNaN(data)){ alert(data) }else{
									document.location.reload(true);
								}
							});
						});
					});
				</script>
				<a class="add" href="javascript:">Создать</a>
			<? endif; ?>
		</div>
	<? endif; ?>
<? else: ?>
	<ul>
		<? foreach(rb("index", 20) as $index): ?>
			<li>
				<a href="/<?=$arg['modpath']?>/<?=$index['id']?>"><?=$index['name']?></a>
				<p><?=$index['description']?></p>
			</li>
		<? endforeach; ?>
	</ul>
	<div><?=$tpl['pager']?></div>
<? endif; ?>
