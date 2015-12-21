<? # Заголовка блока
################################# php код #################################

if(($blocks = rb("{$conf['db']['prefix']}blocks", "id", $arg['blocknum'])) && $_GET['conf']){
	if(array_key_exists("null", $_GET)){
		if($blocks = fk("{$conf['db']['prefix']}blocks", array("id"=>$blocks['id']), null, array("param"=>json_encode($_POST)))){
			exit(json_encode($blocks));
		}else{ mpre("Ошибка сохранения информации в блоке"); }
	}elseif($param = json_decode($blocks['param'], true)){ ?>
		<form action="/blocks:admin/conf:<?=$blocks['id']?>/null" method="post">
			<script src="/include/jquery/jquery.iframe-post-form.js"></script>
			<script>
				(function($, script){
					$(script).parent().one("init", function(e){
						setTimeout(function(){
							$(e.delegateTarget).iframePostForm({
								complete:function(data){
									try{
										if(json = JSON.parse(data)){
											console.log("json:", json);
											document.location.reload(true);
										}
									}catch(e){
										if(isNaN(data)){ alert(data) }else{
											console.log("date:", data)
										}
									}
								}
							});
						}, 100)
					}).trigger("init")
				})(jQuery, document.scripts[document.scripts.length-1])
			</script>
			<p>
				<select name="<?=$arg['fn']?>_id">
					<option></option>
					<? foreach(rb("{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}") as $$arg['fn']): ?>
						<option value="<?=${$arg['fn']}['id']?>" <?=($param["{$arg['fn']}_id"] ? "selected" : "")?>><?=${$arg['fn']}['name']?></option>
					<? endforeach; ?>
				</select>
			</p>
			<p><button type="submit">Сохранить</button></p>
		</form>
	<? }
}elseif(($param = json_decode($blocks['param'], true)) && array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	exit(mpre($_POST));
}

################################# верстка ################################# ?>
<div class="block_<?=$arg['blocknum']?>">
	<?=$arg['modname']?>_<?=$arg['fn']?>
	<? mpre($param) ?>
</div>
