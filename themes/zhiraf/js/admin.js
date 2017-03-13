$(function(){
	$("body").on("keypress", function(e){
		if((e.which == 17) && e.ctrlKey){
			document.location.href = "/";
		}else{ /*console.log("keypress", e);*/ }
	});
	$(document).on("mouseover",".minPreview",function(){
		if(window.bigPreview == undefined){
			window.bigPreview = $("<div class='bigPreview'></div>");
			$("body").append(window.bigPreview);
		}
		window.bigPreview.show();
		window.bigPreview.offset({top:$(this).offset().top, left:$(this).offset().left-120});
		window.bigPreview.css("background-image","url("+$(this).attr('src')+")");
	});
	$(document).on("mouseout",".minPreview",function(){
		window.bigPreview.hide();
	});
})