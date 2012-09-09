jQuery.fn.klesh = function(action, callbackFnk, select){

	function array_search(name, search){
		ret = false;
		$.each(search, function(key, val){
			if(val.name == name) ret = key;
		}); return ret;
	}

	if(select){
		html = $(this).html();
		val = array_search(html, select);
		$(this).attr("old", html).attr("val", val);
	}

	var f = function(){};
	jQuery(this).bind("click", f = function(){
		$(this).unbind("click", f);
		if(select){
			text = $(this).attr("old");
			input = $("<select>").attr("old", text);//.css({"position":"absolute", "z-index":10});

			$.each(select, function(key, val){
				$("<option value='"+val.id+"' "+(text == val.name ? "selected" : "")+">"+val.name+"</option>").appendTo(input);
			});
			$(this)
//				.html($("<input type='button'>").addClass("fnok").val("ok").css("float", "right").css("width", "50px"))
				.html($("<div>")/*.css("margin-right", "60px")*/.html(input));
			$(this).find("select").focus();
		}else{
			text = $(this).html()
			input = $("<input type='text' onkeydown='if((event||window.event).keyCode == 13) $(this).change();'>").css("width", "80%").attr("old", text).val(text);
			$(this)
//				.html($("<input type='button'>").addClass("fnok").val("ok").css("float", "right").css("width", "50px"))
				.html($("<div>")/*.css("margin-right", "60px")*/.html(input));
			$(this).find("input").select();
		}
		$(klesh = this).find("input[type='text'],select").change(ch = function(){
			val = $(this).val();// alert(val);
			old = $(this).attr("old");
			$(klesh).attr("val", val);

			var attr = {old:old};
			for(var i=0;i<(attrs = $(this).parent().parent()[0].attributes).length;i++) {
				attr[attrs[i].nodeName] = attrs[i].nodeValue;
			}
			if(val != old){
				$.post(action, attr, function(data){
					if(isNaN(data)/* && (typeof callbackFnk != 'function')*/){
						alert(data);
					}
				});
			};
			$(this).parent().parent().attr("old", select ? select[val].name : val).html(select ? select[val].name : val).bind("click", f);
			if(typeof callbackFnk == "function"){
				callbackFnk.call(klesh);
			}
		}).bind("blur", function(){
			$(this).change();
		});
	}).css("padding-left", "15px")
	.css("background-repeat", "no-repeat")
	.css("background-position", "0 7px")
	.css("background-image", "url(/img/edit.png)")
	.css("min-height", "25px")
	.css("min-width", "25px")
	.css("line-height", "25px")
	.css("overflow", "hidden");
	return this;
};