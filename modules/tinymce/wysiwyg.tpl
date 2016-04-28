<? if(!array_key_exists('tpl', $conf) || !array_key_exists('elm', $conf['tpl'])): ?>
	<script type="text/javascript" src="//www.google.com/jsapi"></script>
	<script type="text/javascript"> google.load("jquery", "1"); </script>
	<script type="text/javascript" src="/include/jquery/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript">
		$().ready(function() {
			$('textarea.tinymce').tinymce({
				script_url : '/include/jquery/tiny_mce/tiny_mce.js',
				mode : "exact",
				extended_valid_elements : 'noindex,div[*],p[*],script[type|src],iframe[src|style|width|height|scrolling|marginwidth|marginheight|frameborder]',
//				extended_valid_elements : "iframe[name|src|framespacing|border|frameborder|scrolling|title|height|width]"

				language : "ru",
				convert_urls : false,
				elements : "elm1,elm2",
//				extended_valid_elements : "noindex",

				theme : "advanced", // General options
				plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,gallery",

				theme_advanced_buttons1 : "gallery,save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,

				// Example content CSS (should be your site CSS)
//				content_css : "css/content.css",

				// Drop lists for link/image/media/template dialogs
				template_external_list_url : "lists/template_list.js",
				external_link_list_url : "lists/link_list.js",
				external_image_list_url : "lists/image_list.js",
				media_external_list_url : "lists/media_list.js",

				// Replace values for the template plugin
				template_replace_values : {
					username : "Some User",
					staffid : "991234"
				}
			});
		});
	</script>
<? endif; ?>
<div>
	<textarea id="elm<?=($conf['tpl']['elm'] = (get($conf, 'tpl', 'elm') ? ++$conf['tpl']['elm'] : 0))?>" name="<?=$conf['settings']['tinymce_name']?>" rows="15" cols="80" style="width: 100%" class="tinymce">
		<?=$conf['settings']['tinymce_text']?>
	</textarea>
</div>
