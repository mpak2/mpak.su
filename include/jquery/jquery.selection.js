/*****************************************
 * email:skoder@ya.ru 
 * author:Leroy
 * ICQ:370533832 
 * website:http://xdan.ru
******************************************/
/*
	Используется функция $.selection(window)
	она вощзвращает объект с двумя методами get и set
	exampe: $.selection().get().html - верент выделенный html
	exampe: $.selection().get().text - верент выделенный text
	exampe: $.selection().set('Привет'); - заменит выделенный текст на Привет
	exampe: $.selection().set(text,function(text,info,replaceFunc){
		replaceFunc('Привет') // заменяем выделенную часть на Привет
		return false; // тогда по умолчанию set не зменяет text 
	}); - заменит выделенный текст на Привет
	exampe: $.selection().set(text,function(text,info,replaceFunc){
		replaceFunc(info.html.replace('Hello','Привет')) // заменяем в выделенной части Hello на Привет
		return false; // тогда по умолчанию set не зменяет text 
	}); - заменит выделенный текст на Привет
	
*/
(function($,window,document){
	$.selection = function (w){
		var wind = w || window;
		var selectionInfo = false;
		var get = function (){
			selectionInfo = getSelectionFragment();
			return selectionInfo;
		};
		var set = function (text,callback){
			this.get();
			if( (callback && $.isFunction(callback))||( $.isFunction(text) && (callback=text) ))
				text = callback(text,selectionInfo,replaceSelection);
			text!==false && replaceSelection(text);
		};
		var replaceSelection = function (text){
			if(!selectionInfo.ie){
				selectionInfo.rang.deleteContents();
				var documentFragment = toDOM(text);
				selectionInfo.rang.collapse(false);
				selectionInfo.rang.insertNode(documentFragment);
			}else{
				selectionInfo.selectedText.pasteHTML(text);
			}
		}
		var getSelectionFragment = function(){
			var ie = false;
			if ( wind.getSelection ) { 
				var selectedText = wind.getSelection(); 
			} else if ( wind.document.getSelection ) { 
				var selectedText = wind.document.getSelection(); 
			} else if ( wind.document.selection ) { 
				ie = true;
				var selectedText = wind.document.selection.createRange(); 
			} 
			if(!ie){
				var rang = selectedText.getRangeAt(0);
				var theParent = rang.cloneContents(); 
				return {'ie':false,'text':selectedText,'html':toHTML(theParent),'rang':rang};
			}else{
				return {'ie':true,'text':selectedText.text,'html':selectedText.htmlText,'selectedText':selectedText};
			}
		};
		var toHTML = function (docFragment){ 
			var d = wind.document.createElement('div'); 
			d.appendChild(docFragment);
			return d.innerHTML; 
		};
		var toDOM = function(HTMLstring){ 
			var d = wind.document.createElement('div'); 
			d.innerHTML = HTMLstring; 
			var docFrag = wind.document.createDocumentFragment();  // тут тоже важный момент, я с этим пол дня провозился
			while (d.firstChild) { 
				docFrag.appendChild(d.firstChild) ;
			}; 
			return docFrag; 
		} ;
		return {'get':get,'set':set}
	}
})(jQuery,window,document)