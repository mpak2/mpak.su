<script type="text/javascript" src="/include/jquery/tinymce4/init.js"></script>
<div>
	<textarea  name="<?=$conf['settings']['tinymce4_name']?>" rows="15" cols="80" style="width: 100%" class="tinymce">
		<?
			$tinymce_entity_fix = function($s){
			  $entity_from=array('&amp;',     '&lt;',     '&gt;',     '&nbsp;',     '&quot;');
			  $entity_to=  array('&amp;amp;', '&amp;lt;', '&amp;gt;', '&amp;nbsp;', '&amp;quot;');
			  $s=str_replace($entity_from, $entity_to, $s);
			  return $s;
			};
			echo $tinymce_entity_fix($conf['settings']['tinymce4_text']);
		?>
	</textarea>
</div>
