<? # Заголовка блока
################################# php код #################################

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	include "/srv/www/vhosts/mpak.cms/include/parse/simple_html_dom.php";
	$html = new simple_html_dom();
	
	mpsettings("{$arg['modpath']}_clone_href", $_POST['href']);
	mpsettings("{$arg['modpath']}_clone_xpath", $_POST['xpath']);

	function realurl($url, $link){
		if(substr($link, 0, 4) == 'http'){
			$fn = $link;
		}elseif(substr($link, 0, 2) == '//'){
			$fn = 'http:'. $link;
		}elseif(substr($link, 0, 1) == '/'){
			preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $matches);
			$fn = $matches[0]. $link;
		}else{
				$fn = dirname($url. 'url'). '/'. $link;
		}
		$fn. "\n";
		return $fn;
	} if(preg_match("/^((http:\/\/)?([^\/]+))(\/.*)/i", $_POST['href'], $matches)){
		$html->load(mpde($data = file_get_contents($_POST['href'])));
		$data = $html->find($_POST['xpath'], 0)->innertext;
		$h1 = $html->find("h1", 0)->innertext;
		$html->load($data);
		foreach($html->find("img") as $img){
			$src = $img->src;
			$dst = realurl($_POST['href'], $src);
			if($file_id = mphid("{$conf['db']['prefix']}files_files", "name", 0, $dst)){
				mpqw("UPDATE {$conf['db']['prefix']}files_files SET activ=1 WHERE id=". (int)$file_id);
				$img->src = "/files/". (int)$file_id. "/null/". basename($src);
			}
		}
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET time=". (int)time(). ", uid=". (int)$conf['user']['uid']. ", name=\"". mpquot($h1). "\", text=\"". mpquot($html->innertext). "\"");
		$page_index_id = mysql_insert_id();
		if($conf['modules']['seo']){
			$redirect_id = mpfdk("{$conf['db']['prefix']}seo_redirect", $w = array("from"=>$matches[4]), $w += array("to"=>"/pages/". $page_index_id, "description"=>$_POST['href']), $w);
		} exit((string)$page_index_id);
	}else{
		exit("Адрес удаленного ресурса введен не верно");
	} exit(mpre($_POST));
};

//$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}");

################################# верстка ################################# ?>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("form.clone").iframePostForm({
			complete:function(data){
				var href = $(this).find("[name=href]").val();
				if(isNaN(data)){ alert(data) }else{
					if(confirm("Перейти на страницу?")){
						document.location.href = "/pages/"+data;
					}
				}
			}
		})
	})
</script>
<form class="clone" action="/blocks/<?=$arg['blocknum']?>/null" method="post">
	<input type="text" name="href" placeholder="Адрес страницы" value="<?=$conf['settings']["{$arg['modpath']}_clone_href"]?>">
	<input type="text" name="xpath" placeholder=".class" value="<?=$conf['settings']["{$arg['modpath']}_clone_xpath"]?>">
	<input type="submit" value="Клонировать">
</form>
