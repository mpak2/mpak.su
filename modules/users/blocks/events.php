<? die; # Заголовка блока
################################# php код #################################

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	$tpl['event_logs'] = qn($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_event_logs WHERE id>". (int)$_POST['event_logs_id']. " ORDER BY id DESC LIMIT 5");
	exit(json_encode($tpl['event_logs']));
};

$tpl['event_logs'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_event_logs ORDER BY id DESC LIMIT 5");

################################# верстка ################################# ?>
<div class="block_<?=$arg['blocknum']?>">
	<script>
		(function($, script){
			var event_logs_id = <?=(int)$event_logs['id']?>;
			$(script).parent().on("tpl", "*", function(e, t){
				var index = $(this).data(t);
				if(typeof(index) != "undefined"){
					$.each(index, function(k, v){
						$(e.currentTarget).find("[data-"+t+"-"+k+"]").each(function(n, el){
							if($(el).is("select")){
								alert("tpl:select");
							}else if($(el).is("input[type=checkbox]")){
								$(el).prop("checked", v);
							}else{
								$(el).text(v);
							}
						});
					});
				}
			}).on("events", function(e){
				$.post("/blocks/<?=$arg['blocknum']?>/null", {event_logs_id:event_logs_id}, function(event_logs){
					$.each(event_logs, function(n, logs){
						var ul = $(e.delegateTarget).find("ul");
						$(ul).find(">li:hidden").clone().data("event_logs", logs).show().prependTo(ul).trigger("tpl", "event_logs");
						if(event_logs_id < logs.id){
							event_logs_id = logs.id;
						} $(ul).find(">li:visible:gt(5)").remove();
//						console.log("slice:", $(ul).find(">li").slice(5));
					});// console.log("event_logs:", event_logs);
				}, "json").fail(function(error) {
					alert(error.responseText);
				});
			}).each(function(n, main){
				setInterval(function(){
					$(main).trigger("events");
				}, 3000);
				setTimeout(function(){
					$(main).trigger("events");
				}, 50);
			});
		})(jQuery, document.scripts[document.scripts.length-1])
	</script>
	<div class="log">
		<ul style="padding-left:15px;">
			<li data-event_logs style="display:none;">
				<span style="float:right;" data-event_logs-id>0</span>
				<span data-event_logs-description>description</span>
			</li>
		</ul>
	</div>
</div>