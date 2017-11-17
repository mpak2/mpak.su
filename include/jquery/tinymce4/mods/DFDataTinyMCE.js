//Я заколебался писасть длынную форму "console.log"
//поэтому решил укоротить )))
var log = console.log;

//возвращает dataTransfer
function getDtopDataTransfer(event){
	if(event.dataTransfer == undefined){
		if(event.originalEvent && event.originalEvent.dataTransfer){
			return event.originalEvent.dataTransfer;
		}else{
			return false
		}
	}else{
		return event.dataTransfer
	}
}
//обработчик режима переноса файлов
//isOnlyFile - предотвращает срабатывание на перетаскиваниии текста и других элементов страницы
//поумолчанию пеагирует только на файлы
$.fn.drop = function(settings,isOnlyFile=true){
	function is_file(event){
		var isf = false;
		var DT = getDtopDataTransfer(event);			
		if(DT !== false)
			isf = fi(get(DT,['types','indexOf']) ? DT.types.indexOf('Files') != -1 : dt.types.contains('Files'));
		return isf;
	}
	function Allow(event){
		if(fi(isOnlyFile))
			return is_file(event);
		return true;
	}
	window._drophide_65312354321 = false;
	$(this).on('dragover',function(event) {
		window._drophide_65312354321 = false;
		if(fi(settings.onFocus) && Allow(event)){
				settings.onFocus(event);				
			return false;
		}
	}).on('dragleave',function(event) {
		window._drophide_65312354321 = true;			
		if(fi(settings.onOut) && Allow(event)){			
			setTimeout(function(){
				if (window._drophide_65312354321){
					settings.onOut(event);
				}
			},100);
		}
		return false;
	}).on('drop',function(event) {
		if(Allow(event)){
			event.preventDefault();
			if(fi(settings.onOut))
				settings.onOut(event);
			if(fi(settings.onDrop))
				settings.onDrop.call(event.target,event);
			return false;
		}
	});		
}

function count(o){
	var c=0;
	for (var k in o) 
		if(o.hasOwnProperty(k))
		   ++c;
	return c;
}

//ели есть метод(ы)
function ifIssetMetods(obj){
	for(var m in obj)
		if(typeof obj[m] == "function")
			return true;
	return false;
}


//Данная фукнкця преобразовывает любые данные в  boolean
//Точно также как это было бы в php
function fi(dataFI){
	var Fi;
	switch(typeof(dataFI)) {
		  case 'number':
			Fi =  (dataFI==0 || isNaN(dataFI)) ? false : true;
			break
		  case 'string':
			Fi =  (dataFI==='' || dataFI==='0') ? false : true;
			break
		  case 'object':
			if((ifIssetMetods(dataFI))){	
				//это объект с методами
				Fi =  true;
			}else{					
				try{
					Fi = fi(parseInt(dataFI['length'])) ? true : fi(count(dataFI));
				}catch(e){
					Fi =  fi(count(dataFI));
				}
			}
			break
		  default:
			Fi = !!dataFI;
			break
		}
	return Fi;
}

function get(Var,path,Else=false,ElseIsFunction=false){
	if(fi(ElseIsFunction)){
		var ElseF = Else;
		Else = function(){return ElseF;};
	}
	if(fi(Var) && is_array(path)){			
		var result = Var;
		for(detI in path){
			if(result[path[detI]]!=undefined){
				result = result[path[detI]];
			}else{
				return Else;
			}
		}
		return result;
	}else{
		return Else;
	}
}
//analog php
function is_array(Var){
	if(Var.constructor === Array){
		return true;
	}else if(Var.constructor === Object){
		return count(array_keys(Var))===count(Var);
	}else{
		return false;
	}
}

//analog php
function in_array(needle, haystack, strict) {
	var found = false, key, strict = !!strict;
	for (key in haystack) {
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
			found = true;
			break;
		}
	}
	return found;
}
//analog php
function array_values( input ) {
	var tmp_arr = [], cnt = 0;
	for ( key in input ){
		tmp_arr[cnt] = input[key];
		cnt++;
	}
	return tmp_arr;
}
//analog php
//соедиение массива в строку 
function implode( glue, pieces ) {	// Join array elements with a string
	pieces = array_values(pieces);
	return ( ( pieces instanceof Array ) ? pieces.join ( glue ) : pieces );
}
function SendFileDF(opt={}){
	var attr = {
		url:'/data:admin_ajax/null',
		files:false,
		data:{},
		dataType:'html',
		success:function(){},
		progress:true,
		progress_obj:false
	};
	for(var k in opt){
		if(attr[k]!==undefined)
			attr[k] = opt[k];
	}
	
	var formData = new FormData();	
	for(var key in attr.data){
		var type = typeof(attr.data[key]);
		if(type==='object' || type==='array'){
			formData.append(key, JSON.stringify(attr.data[key])); 
		}else{
			formData.append(key, attr.data[key]); 
		}
	}
	if(attr.files){
		if(!attr.files.length){
			attr.files = $(attr.files);
		}
		for(var f=0; f<attr.files.length; f++){
			var file = attr.files.get(f).files;
			var name = $(attr.files.get(f)).attr('name');
			if(!name)
				name = f+'[]';
			if(file[0]!==undefined){				
				for(var ff=0; ff<file.length; ff++){
					formData.append(name, file[ff]);
				}
			}else{
				formData.append(name, file);
			}
		}
	}
	$.ajax({
		url: attr.url,
		type: 'post',
		contentType: false,
		processData: false,
		data: formData,
		dataType: attr.dataType,
		xhr: function(){
			var xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest				
			xhr.upload.addEventListener('progress', function(evt){ // добавляем обработчик события progress (onprogress)
				if (evt.lengthComputable) { // если известно количество байт
					var percentComplete = Math.ceil(evt.loaded / evt.total * 10) * 10;
					if(attr.progress && attr.progress_obj)
						attr.progress_obj.html(" "+percentComplete+"%");						
				}
			}, false);
			return xhr;
		},
		success: function(data){
			attr.success(data);
		}
	}).done(function(){
		if(attr.progress && attr.progress_obj)
			$(attr.progress_obj).html(" ");
	}).fail(function(){		
		if(attr.progress && attr.progress_obj)
			$(attr.progress_obj).html(" ");
		alert("Ошибка загрузки файлов!");
	});
}


function DFDataTinyMCE(){
	var DFData = false,
		Params = {
			CheckArray: new Array(0),
			FileType: 'image',
			Multi: true,
			DelModal:false,
			ModalBlock:false,
			Сallback: function(){console.log("Функция не назначена!")},
			Dialog: false
		}
	;
	
	var print = function(data,is_cat,is_remove){
		var list = DFData.find(".items .items-list");		
		var menu = DFData.find(".items .items-menu");
		if(is_cat==undefined) 
			var is_cat = false;
		if(is_remove==undefined) 
			var is_remove = true;
		if(is_remove){
			Params.CheckArray = new Array(0);
			list.find(".item").remove();
			if(is_cat)
				menu.find(".item").remove();
		}
		if(!is_remove){
			list.find(".item").each(function(i,item){
				var id = $(item).attr('item_id').trim();
				if(!in_array(id,data.data_id))
					$(item).remove();
			});
		}
		for(var i=0;i<data.data.length;i++){
			if(!list.find('.item[item_id="'+get(data,['data',i,'id'],'')+'"]').length){
				list.append('<a href="'+get(data,['data',i,'url'],'')+'" style="background-image:url('+get(data,['data',i,'bg'],'')+');" item_id="'+get(data,['data',i,'id'],'')+'" title="'+get(data,['data',i,'name'],'')+'" target="blank" class="item">\
								<div class="remove"></div>\
								<div class="footer">\
									<input type="checkbox" class="check" tabindex="-1"/>\
									<span class="name">'+get(data,['data',i,'name'],'')+'</span>\
								</div>\
							</a>\
				');
			}
		}
		if(is_cat){
			for(var i=0;i<data.cats.length;i++){
				if(!menu.find('.item[value="'+get(data,['cats',i,'value'],'')+'"]').length){
					menu.append('<div class="item '+(get(data,['cats',i,'active'])?" active":"")+'" value="'+get(data,['cats',i,'value'],'')+'" title="'+get(data,['cats',i,'name'],'').replace(/<\/?[^>]>/ig,'')+'" item_id="'+get(data,['cats',i,'id'],'')+'" tabindex="0">'+get(data,['cats',i,'name'],'')+'</div>');
				}
			}
		}
	}
	var getdata = function(is_cat,is_remove){
		if(is_cat==undefined) 
			var is_cat = false;
		if(is_remove==undefined) 
			var is_remove = true;
		
		if(!is_cat)
			var value = DFData.find('.items-menu .item.active').attr('value').split(':');

		$.ajax({
			url:'/data:admin_ajax/null',
			method:'post',
			dataType:'json',
			data:{
				action:'get',
				FileType:Params.FileType,
				is_cat: is_cat ? 1:0,
				cat: is_cat ? false : get(value,[0],''),
				cat_id_item:is_cat ? false : get(value,[1],''),
				
			},
			success:function(data){
				if(data.error){					
					alert(data.mess);
					console.log('Error: ',data.mess);
				}else{
					print(data,is_cat,is_remove);
				}
			}			
		}).error(function(data){
			if(data.responseText.trim()==''){
				alert("Ошибка запроса! Пустой ответ! Вероятно отсутствует файл data:admin_ajax.php");
			}
			if(data.responseText.match(/Модуль не найден/gi)){
				alert("Ошибка запроса! Причина: не устанвлен модуль Данные.");
			}else{
				alert("Ошибка запроса!");
			}	
			console.log('Error: ',data);
		});
	}
	var open = function(vars){
		if(vars.FileType && vars.FileType == 'media'){
			alert("Данная возможность не реализованна!");
			return false;
		}
		
		if(!DFData){
			start(vars);
			return;
		}
		for(k in vars){
			if(Params[k]!==undefined)
				Params[k] = vars[k];
		}
		DFData.find(".items-list").attr('filetype',Params.FileType);
		DFData.find(".mce-tab.mce-active").removeClass('mce-active');
		DFData.find('.mce-tab[value="'+Params.FileType+'"]').addClass('mce-active');
		
		DFData.attr('Multi',Params.Multi ? "true" : "false");
		Params.DelModal = false;	
		Params.Dialog = $('.mce-container[role="dialog"]');
		Params.ModalBlock = $("#mce-modal-block"); 		
		if(!Params.ModalBlock.length || Params.ModalBlock.length==0){
			Params.ModalBlock = $('<div id="mce-modal-block" class="mce-reset mce-fade mce-in" style="z-index: 999999999;"></div>');
			Params.DelModal = true;
			$("body").append(Params.ModalBlock);
		}
		DFData.show().css("z-index",parseInt(Params.ModalBlock.css("z-index").trim())+2);					
		Params.Dialog.hide();
		getdata(true,true);
	}
	var close = function(){					
		DFData.hide();//скрываем наш диалог					
		if(Params.Dialog && Params.Dialog.length)
			Params.Dialog.show();//отображаем диалоговый блок					
		if(Params.DelModal && Params.ModalBlock.length)
			Params.ModalBlock.remove();	//удаляем блок затемнения "Шторку"					
	}
	var ok_end = function(){
		if(Params.CheckArray.length && Params.CheckArray.length>0){
			switch(Params.FileType) {
				case 'image'://image
					if(Params.Multi){
						var data = '';
						Params.CheckArray.each(function(i,item){									
							data += '<img src="'+$(item).attr('href')+'" alt="'+$(item).find('.name').html()+'"/>';
						});								
						//Params.Сallback.insertContent(data);
						Params.Сallback.execCommand('mceInsertContent', false, data);
					}else{
						//callback('myimage.jpg', {alt: 'My alt text'});
						Params.Сallback(Params.CheckArray.attr('href'), {alt: Params.CheckArray.find('.name').html()});
					}
					break;
				case 'media':  //media
					if(Params.Multi){
						var data = '';
						Params.CheckArray.each(function(i,item){									
							data += '';
						});								
						//Params.Сallback.insertContent(data);
						Params.Сallback.execCommand('mceInsertContent', false, data);
					}else{
						//callback('movie.mp4', {source2: 'alt.ogg', poster: 'image.jpg'});
						Params.Сallback(Params.CheckArray.attr('href'));
					}
					break
				default://file							
					if(Params.Multi){
						var data = '';
						Params.CheckArray.each(function(i,item){									
							data += '<a href="'+$(item).attr('href')+'">'+$(item).find('.name').html()+'</a> ';									
						});				
						//Params.Сallback.insertContent(data);
						Params.Сallback.execCommand('mceInsertContent', false, data);
					}else{
						//callback('mypage.html', {text: 'My text'});
						Params.Сallback(Params.CheckArray.attr('href'), {text: Params.CheckArray.find('.name').html(),title: Params.CheckArray.find('.name').html()});
					}
					break;
			}
		}
		close();
	}
	var upload = function(files){		
		if(files==undefined) 
			var files = [];
		var value = DFData.find('.items-menu .item.active').attr('value').split(':');						
		SendFileDF({
			files:files,
			data:{
				action:'upload',
				FileType:Params.FileType,
				is_cat: false,
				cat: get(value,[0],''),
				cat_id_item: get(value,[1],''),
			},
			progress_obj:DFData.find('.loading'),
			success:function(data){
				getdata(false,false);
			}
		});
	}
	
	var start = function(vars){
		$.ajax({
			url:'/include/jquery/tinymce4/mods/DFDataTinyMCE.html',
			success:function(html){
				$('body').append(html);
				DFData = $('.DFData');
				var ListItems = DFData.find(".items .items-list");
				DFData.find(".mce-tab").click(function(){
					var tab = $(this);
					if(!tab.hasClass('mce-active')){
						tab.parent().find('.mce-active').removeClass('mce-active');			
						tab.addClass('mce-active');
						Params.FileType = tab.attr('value');
						DFData.find(".items-list").attr('filetype',Params.FileType);
						getdata(true,true);
					}
				});
				DFData.find(".items-menu").on('click','.item',function(){
					var tab = $(this);
					if(!tab.hasClass('active')){
						tab.parent().find('.active').removeClass('active');			
						tab.addClass('active');
						getdata(false,false);
					}
				});
				
				ListItems.on("click",'.item',function(event){
					if(Params.Multi){
						if($(this).hasClass('active'))
							$(this).removeClass('active').find('.check').prop("checked",false);
						else
							$(this).addClass('active').find('.check').prop("checked",true);
					}else{
						if(!$(this).hasClass('active')){
							$(this).closest('.items').find('.active').removeClass('active');
							$(this).addClass('active');
						}					
					}
					Params.CheckArray = ListItems.find('.active');
					event.preventDefault();
					return false;
				}).on("dblclick",'.item',function(){
					if(
						(Params.Multi && (!Params.CheckArray.length || Params.CheckArray.length==0))
							||						
						(!Params.Multi && (Params.CheckArray.length && Params.CheckArray.length==1))	
					){
						Params.CheckArray = $(this);
						ok_end();
						event.preventDefault();
						return false;
					}
				}).on("click",'.item .remove',function(event){
					var IDs = [];
					var NAMEs = [];
					
					if(Params.Multi){
						for(var i=0;i<Params.CheckArray.length;i++){
							var item = get(Params.CheckArray,[i]);
							if(item){
								var id = parseInt($(item).attr('item_id').trim());
								if(id>0 && !in_array(id,IDs)){
									IDs.push(id);
									NAMEs.push($(item).find('.name').html().trim());
									
								}
							}
						}
					}
					var id = parseInt($(this).parent().attr('item_id').trim());
					if(id>0 && !in_array(id,IDs)){
						IDs.push(id);
						NAMEs.push($(this).parent().find('.name').html().trim());						
					}
					
					
					if(count(IDs)>0){
						if (confirm("Вы действительно желаете удалить файлы: "+implode(', ', NAMEs)+"?")) {
							$.ajax({
								url:'/data:admin_ajax/null',
								method:'post',
								dataType:'json',
								data:{
									action:'del',
									FileType:Params.FileType,
									IDs:IDs,
								},
								success:function(data){
									if(data.error){					
										alert(data.mess);
										console.log('Error: ',data.mess);
									}else{
										getdata(false,false);
									}
								}			
							}).error(function(data){
								if(data.responseText.trim()==''){
									alert("Ошибка запроса! Пустой ответ! Вероятно отсутствует файл data:admin_ajax.php");
								}
								if(data.responseText.match(/Модуль не найден/gi)){
									alert("Ошибка запроса! Причина: не устанвлен модуль Данные.");
								}else{
									alert("Ошибка запроса!");
								}							
								console.log('Error: ',data);
							});
						} else {
							//alert("Вы нажали кнопку отмена")
						}
					}
					event.preventDefault();
					return false;
				});
				
				DFData.find(".mce-close i, .close").click(function(event){
					close();
					event.preventDefault();
					return false;
				});
				
				DFData.find(".ok").click(function(event){
					ok_end();
					event.preventDefault();
					return false;
				});
				
				DFData.find('.upload').click(function(event){
					DFData.find('input.file-btn.'+Params.FileType).click();
				});
				DFData.find('input.file-btn').change(function(event){
					upload(this);
					$(this).val('');
				});
				
				// Обрабатываем событие Drop
				$(window).drop({
					onFocus:function(event){
						//Включаем режим дропа
						DFData.find(".items").addClass('drop');
					},
					onOut:function(event){
						//Скрываем режим дропа						
						DFData.find(".items").removeClass('drop');
					},
					onDrop:function(event){
						if($(this).hasClass('mce-dropzone')){
							upload(getDtopDataTransfer(event));
						}else{
							//alert("Вы не попали в зону!\nПопробуйте снова!");
						}
					}
				});
				open(vars);					
			}
		});
	};
	
	return {open: open}
}