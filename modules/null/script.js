$(".index").on("ajax", function(e, ajax){
	$.each(ajax, function(url, post){
		$.ajax({url:url, data:post, dataType:"text", async:false,
			success:function(data){
//				$(e.delegateTarget).data("ajax", data);
			}
		}).done(function(data){
			if(isNaN(data)){
				$(e.delegateTarget).data("ajax", 0);
				alert(data);
			}else{
				$(e.delegateTarget).data("ajax", data);
			}
		}).fail(function(data){
			alert(data.responseText);
		});
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
}).on("tpl", function(e, index){
	var tpl = $(e.delegateTarget).find(">script[type='text/template']").html();
	$.each(index, function(key, val){ // console.log("key:", "${"+key+"}", "val:", val);
		tpl = tpl.split("${"+key+"}").join(val);
	}); $(e.delegateTarget).data("tpl", tpl);
}).each(function(){
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