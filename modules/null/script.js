$("script").slice(-1).parent().on("ajax", function(e, param){
	var href = "/<?=$arg['modname']?>:ajax/class:"+param.class;
	$.each(param.get, function(key, val){
		href += "/"+ (key == "id" ? "" : key+ ":")+ val;
	});
	$.post(href, param.post, function(data){
		if(typeof(param.complete) == "function"){
			param.complete.call(this, data);
		}
	}, "json").fail(function(error) {
		console.log("error:", error);
	});
}).on("json", function(e, json){
	$.each(json, function(url, post){
		$.ajax({url:url, post:post, dataType:"json", async:false,
			success:function(data){
				$(e.delegateTarget).data("json", data);
			}
		}).done(function(data){
			$(e.delegateTarget).data("json", data);
		}).fail(function(data){
			alert(data.responseText);
		});
	});
}).on("tpl", "*", function(e, t){
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
}).on("events", function(e, log){
	$.each($._data(this, "events"), function(name, event){
		if(typeof(log) == "undefined") console.log("events:", name);
		$.each(event, function(k, ev){
			if(typeof(log) == "undefined") console.log("\t", ev);
			$(e.delegateTarget).on(ev.type, ev.selector, function(){
				console.log("trigger(\""+ ev.type + "\", \""+ ev.selector+ "\")", ev);
			});
		});
	});
}).each(function(){
	$(this).trigger("events");
	$(main = this).find("form.imgs").on("click", "[imgs_id] .del", function(e){
		var img_id = $(this).parents("[imgs_id]").attr("imgs_id");
		var ajax = $(main).trigger("ajax", {"/<?=$arg['modname']?>:ajax/class:imgs":{id:-img_id}}).data("ajax");
		if(ajax <= 0){
			$(this).parents("[imgs_id]").remove();
		}
	}).on("tpl", function(e, index){
		var tpl = $(this).find(".template").html();
		$.each(index, function(key, val){
			tpl = tpl.split("${"+key+"}").join(val);
		}); $(this).data("tpl", tpl);
	}).on("add", function(e, index){
		var tpl = $(this).trigger("tpl", index).data("tpl");
		$(this).find(".list").append(tpl);
	}).each(function(){
		return $(this).iframePostForm({
			complete:function(data){
				if(isNaN(data)){ alert(data) }else{
					$(this).trigger("add", {id:data});
				}
			}
		});
	}).each(function(){
		$.each(<?=json_encode(rb($tpl['imgs'], "uid", "index_id", "id", $conf['user']['uid'], 0))?>, $.proxy(function(key, imgs){
			$(this).trigger("add", imgs);
		}, this))
	});
});