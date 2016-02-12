
		<!-- Yandex.Metrika counter -->
			<? foreach(rb("{$conf['db']['prefix']}themes_yandex_metrika_index", "index_id", "id", $conf['user']['sess']['themes_index']['id']) as $themes_yandex_metrika_index): ?> 
				<? if($themes_yandex_metrika = rb("{$conf['db']['prefix']}themes_yandex_metrika", "id", $themes_yandex_metrika_index['yandex_metrika_id'])): ?>
					<script sync type="text/javascript">
						/*<![CDATA[*/
						(function (d, w, c) {
							(w[c] = w[c] || []).push(function() {
								try {
										w.yaCounter<?=$themes_yandex_metrika['id']?> = new Ya.Metrika({id:<?=$themes_yandex_metrika['id']?>,
												webvisor:true,
												clickmap:true,
												trackLinks:true,
												accurateTrackBounce:true});
								} catch(e) { }
							});
						
							var n = d.getElementsByTagName("script")[0],
								s = d.createElement("script"),
								f = function () { n.parentNode.insertBefore(s, n); };
							s.type = "text/javascript";
							s.async = true;
							s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
						
							if (w.opera == "[object Opera]") {
								d.addEventListener("DOMContentLoaded", f, false);
							} else { f(); }
						})(document, window, "yandex_metrika_callbacks");
						/*]]>*/
					</script>
				<? endif; ?> 
			<? endforeach; ?> 
		<!-- /Yandex.Metrika counter -->
