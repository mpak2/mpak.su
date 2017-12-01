$(function(){
	if(window.yepnop==undefined){window.yepnope=function(e,t,n){function r(){}function o(e){return Object(e)===e}function i(e){return"string"==typeof e}function a(){return"yn_"+y++}function c(){m&&m.parentNode||(m=t.getElementsByTagName("script")[0])}function u(e){return!e||"loaded"==e||"complete"==e||"uninitialized"==e}function l(t,n){n.call(e)}function s(e,n){var s,f,d;i(e)?s=e:o(e)&&(s=e._url||e.src,f=e.attrs,d=e.timeout),n=n||r,f=f||{};var y,v,w=t.createElement("script");d=d||p.errorTimeout,w.src=s,g&&(w.event="onclick",w.id=w.htmlFor=f.id||a());for(v in f)w.setAttribute(v,f[v]);w.onreadystatechange=w.onload=function(){if(!y&&u(w.readyState)){if(y=1,g)try{w.onclick()}catch(e){}l(s,n)}w.onload=w.onreadystatechange=w.onerror=null},w.onerror=function(){y=1,n(new Error("Script Error: "+s))},h(function(){y||(y=1,n(new Error("Timeout: "+s)),w.parentNode.removeChild(w))},d),c(),m.parentNode.insertBefore(w,m)}function f(n,a){var u,l,s={};o(n)?(u=n._url||n.href,s=n.attrs||{}):i(n)&&(u=n);var f=t.createElement("link");a=a||r,f.href=u,f.rel="stylesheet",f.media="only x",f.type="text/css",h(function(){f.media=s.media||"all"});for(l in s)f.setAttribute(l,s[l]);c(),m.parentNode.appendChild(f),h(function(){a.call(e)})}function d(e){var t=e.split("?")[0];return t.substr(t.lastIndexOf(".")+1)}function p(e,t,n){var r;o(e)&&(e=(r=e).src||r.href),e=p.urlFormatter(e,t),r?r._url=e:r={_url:e};var i=d(e);if("js"===i)s(r,n);else{if("css"!==i)throw new Error("Unable to determine filetype.");f(r,n)}}var m,h=e.setTimeout,y=0,v={}.toString,g=!(!t.attachEvent||e.opera&&"[object Opera]"==v.call(e.opera));return p.errorTimeout=1e4,p.injectJs=s,p.injectCss=f,p.urlFormatter=function(e,t){var n=e,r=[],o=[];for(var i in t)t.hasOwnProperty(i)&&(t[i]?r.push(encodeURIComponent(i)):o.push(encodeURIComponent(i)));return(r.length||o.length)&&(n+="?"),r.length&&(n+="yep="+r.join(",")),o.length&&(n+=(r.length?"&":"")+"nope="+o.join(",")),n},p}(window,document);}
	
	yepnope.injectJs('/include/jquery/tinymce4//tinymce.min.js',function(){
		yepnope.injectJs('/include/jquery/tinymce4/mods/html-formatting.min.js',function(){			
			yepnope.injectJs('/include/jquery/tinymce4/mods/DFDataTinyMCE.js',function(){
			
				var DFData_TinyMCE = DFDataTinyMCE();

				tinymce.PluginManager.add('browse_source', function( editor, url ) {
					var run = function(){
						DFData_TinyMCE.open({
							FileType: 'image',
							Multi: true,
							Сallback: editor,
						});
					}
					editor.addButton( 'browse_source', {
						tooltip: 'Менеджер ресурсов',
						icon: 'browse',
						onclick: run
					});
					
					editor.addMenuItem('browse_source', {
						context: 'insert',
						text: 'Менеджер ресурсов',
						icon: 'browse',
						onclick: run
					});
				});
				
				tinymce.PluginManager.add('typograf', function (editor, url) {
					'use strict';
					var scriptLoader = new tinymce.dom.ScriptLoader(),
						tp,
						typo = function () {
							if (tp) {
								editor.setContent(tp.execute(editor.getContent()));
								editor.undoManager.add();
							}
						}
					scriptLoader.add(url + '/dist/typograf.min.js');
					scriptLoader.loadQueue(function () {
						tp = new Typograf({
							locale: ['ru', 'en-US'],
							lang: 'ru',
							mode: 'name'
						});
					});
					editor.addButton('typograf', {
						tooltip: 'Типографика',
						icon: 'blockquote',
						onclick: typo
					});
					editor.addMenuItem('typograf', {
						context: 'format',
						text: 'Типографика',
						icon: 'blockquote',
						onclick: typo
					});
				});
				
				tinymce.init({
					selector: 'textarea.tinymce',
					height: 500,
					theme: 'modern',//advanced
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools  contextmenu colorpicker textpattern paste browse_source typograf code',
					toolbar1: 'browse_source | formatselect | bold italic  | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | link image | typograf  removeformat | code ',
					image_advtab: false,
					language:"ru",
					convert_urls : false,
					//images_upload_base_path: '/some/basepath',
					automatic_uploads: false,
					images_upload_url: 'postAcceptor.php',
					file_browser_callback_types: 'file image media',
					//file_browser_callback: function(field_name, url, type, win) {win.document.getElementById(field_name).value = 'my browser value';},
					file_picker_callback: function(callback, value, meta) {
						DFData_TinyMCE.open({
							FileType: meta.filetype,
							Multi: false,
							Сallback: callback,
						});
						return;
					},
					images_upload_handler: function (blobInfo, success, failure) {
						alert('images_upload_handler');return;
						var xhr, formData;
						xhr = new XMLHttpRequest();
						xhr.withCredentials = false;
						xhr.open('POST', 'postAcceptor.php');
						xhr.onload = function() {
						  var json;
						  if (xhr.status != 200) {
							failure('HTTP Error: ' + xhr.status);
							return;
						  }
						  json = JSON.parse(xhr.responseText);
						  if (!json || typeof json.location != 'string') {
							failure('Invalid JSON: ' + xhr.responseText);
							return;
						  }
						  success(json.location);
						};
						formData = new FormData();
						formData.append('file', blobInfo.blob(), blobInfo.filename());
						xhr.send(formData);
					},
					templates: [
						{ title: 'Test template 1', content: 'Test 1' },
						{ title: 'Test template 2', content: 'Test 2' }
					],
					content_css: [],
					paste_postprocess: function (plugin, args) {
						/*
							https://habrahabr.ru/post/266337/
							html-formatting.min.js
							Мы запрещаем вставку изображений с внешних источников, к тому же у всех разрешенных элементов удаляем какие бы то ни было классы. Кроме того, так как у нас на странице всегда присутствует заголовок первого уровня (название статьи), то при копировании таких заголовков преобразовываем их в заголовки второго уровня. Так же, согласно нашим внутренним правилам, мы удаляем все дочерние элементы из заголовков, а переносы (<br>) заменяем на пробелы. Из стилей мы сохраняем все стили для встраиваемых элементов (embed, iframe), text-align для параграфов и таблиц, а так же vertical-align только для таблиц. Ну и напоследок, к ссылкам на внешние ресурсы добавляется атрибут target="_blank".
							Независимо от правил конфигурации у всех элементов будут всегда удаляться идентификаторы, при их наличии, а в тексте будут заменяться неразрывные пробелы на обычные.			 
						*/
						var headerRule = {
							'br': {
								process: function (node) {
									var parent = node.parentNode,
									space = document.createTextNode(' ');
									parent.replaceChild(space, node);
								}
							}
						},
						valid_elements = {
							'h1': {convert_to: 'h2', valid_styles: '', valid_classes: '', no_empty: true, valid_elements: headerRule},
							'h2,h3,h4': {valid_styles: '',valid_classes: '',no_empty: true,valid_elements: headerRule},
							'p': {valid_styles: 'text-align', valid_classes: '', no_empty: true},
							a: {valid_styles: '',valid_classes: '',no_empty: true,
								process: function (node) {
									var host = 'http://' + window.location.host + '/';
									if (node.href.indexOf(host) !== 0)
										node.target = '_blank';
								}
							},
							'br': {valid_styles: '', valid_classes: ''},
							'blockquote,b,strong,i,em,s,strike,sub,sup,kbd,ul,ol,li,dl,dt,dd,time,address,thead,tbody,tfoot': {valid_styles: '', valid_classes: '', no_empty: true},
							'table,tr,th,td': {valid_styles: 'text-align,vertical-align',valid_classes: '',no_empty: true},
							'embed,iframe': { valid_classes: ''}
						}
						htmlFormatting(args.node, valid_elements); 
					}
				});
	
			});
		});
	});
});