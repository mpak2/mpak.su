<style>
	canvas{
		cursor: crosshair;
		border:#CCC solid 1px;
		float:left;
		margin-bottom:10px;
	}

	hr {
	margin: 20px 0 19px;
	border: 0;
	border-bottom: 1px solid #EEE;
	}


	#clr{
		margin:0 auto;
		float:left;
	}

	#clr div{
		cursor:pointer;
		cursor:hand;
		width:20px;
		height:20px;
	}

	#footer{
	border-top:1px #ccc solid;
	margin-top:20px;
	padding:10px;
	}

	#themain{
		border: 1px solid #ccc;
		/*
		-webkit-box-shadow: 2px 2px 2px #aaa;
		-moz-box-shadow: 2px 2px 2px #aaa;
		-webkit-border-radius: 10px;
		-moz-border-radius: 10px;
		*/
		padding: 20px;
	}

	#themain img{
		height: 80px;
		width: 100px;
		box-shadow: 0px 0.9px 2px #333333;
		margin-bottom: 14px;
		background: none;
		
	}

	#themain img:hover {
	box-shadow: 0px 1px 10px #333333;
	}


	#themain a.minimal:active {
		background: none repeat scroll 0 0 #D0D0D0;
		box-shadow: 0 0 1px 1px #E3E3E3 inset;
		color: #000000;
	}

	#themain a.minimal {
		background: none repeat scroll 0 0 #E3E3E3;
		border: 1px solid #BBBBBB;
		border-radius: 3px 3px 3px 3px;
		box-shadow: 0 0 1px 1px #F6F6F6 inset;
		color: #333333;
		font: bold 12px/1 "helvetica neue",helvetica,arial,sans-serif;
		padding: 8px 10px 9px;
		text-align: center;
		text-shadow: 0 1px 0 #FFFFFF;
		width: 150px;
		text-decoration:none;
	}


	#themain a.download:active {
		-moz-border-bottom-colors: none;
		-moz-border-image: none;
		-moz-border-left-colors: none;
		-moz-border-right-colors: none;
		-moz-border-top-colors: none;
		background: none repeat scroll 0 0 #3282D3;
		border-color: #154C8C #154C8C #0E408E;
		border-style: solid;
		border-width: 1px;
		box-shadow: 0 0 6px 3px #1657B5 inset, 0 1px 0 0 white;
		text-shadow: 0 -1px 1px #2361A4;
	}

	#themain a.download {
		cursor:pointer;
	}

	#themain a.download {
		background-color: #52A8E8;
		background-image: -moz-linear-gradient(center top , #52A8E8, #377AD0);
		border-color: #4081AF #2E69A3 #20559A;
		border-radius: 16px 16px 16px 16px;
		border-style: solid;
		border-width: 1px;
		box-shadow: 0 1px 0 0 #72B9EB inset, 0 1px 2px 0 #B3B3B3;
		color: #FFFFFF;
		font: 25px/1 "lucida grande",sans-serif;
		padding: 3px 5px;
		text-align: center;
		text-shadow: 0 -1px 1px #3275BC;
		width: 112px;
		display: block;
		margin: 10px auto;
		padding: 10px;
		width: 230px;
	}

	#btns{
		margin:20px auto;
	}
</style>

<script>
	var restorePoints = [];
	$(document).ready(function(){
		// This array will store the restoration points of the canvas
		var restorePoints = [];
		var a=false;var b,c="";var d=document.getElementById("can");
		var e=d.getContext("2d");


	/*	var image = new Image();
		image.src = "/themes/null/i/logo_last.png";
		$(image).load(function() {
			e.drawImage(image, 0, 0);
		});*/

		e.strokeStyle="red";
		e.lineWidth=5;
		e.lineCap="round";
		e.fillStyle="#fff";
		e.fillRect(0,0,d.width,d.height);
		$("#bsz").change(function(a){e.lineWidth=this.value});
		$("#bcl").change(function(a){e.strokeStyle=this.value});
		$("#can").mousedown(function(d){
			saveRestorePoint();a=true;e.save();b=d.pageX+<?=(int)$conf['settings']['convas_pageX']?>-this.offsetLeft;c=d.pageY+<?=(int)$conf['settings']['convas_pageY']?>-this.offsetTop}
		);


		$(document).mouseup(function(){a=false});
		$(document).click(function(){a=false});

		$("#can").mousemove(function(d){
			if(a==true){
				e.beginPath();
				e.moveTo(d.pageX+<?=(int)$conf['settings']['convas_pageX']?>-this.offsetLeft,d.pageY+<?=(int)$conf['settings']['convas_pageY']?>-this.offsetTop);
				e.lineTo(b,c);
				e.stroke();
				e.closePath();
				b=d.pageX+<?=(int)$conf['settings']['convas_pageX']?>-this.offsetLeft;
				c=d.pageY+<?=(int)$conf['settings']['convas_pageY']?>-this.offsetTop
			}
		});
				
		$("#clr > div[color]").click(function(){
			e.strokeStyle = $(this).css("background-color");
			color = $(this).attr("color");
			$("#clr > div[size] > div").each(function(key, val){
				if($(val).css("background-color") != "transparent"){
					$(val).css("background-color", color);
				}
			});
			$("#bcl").val(color);
		});
		$("#clr > div[size]").click(function(){
			color = $("#bcl").val();
			e.lineWidth = $(this).attr("size");
			$("#clr > div[size] > div").css("background-color", "");
			$(this).find(">div").css("background-color", color);
			$("#bsz").val(e.lineWidth);
		});
		$("#undo").click(function(){undoDrawOnCanvas();});

		$("#save").click(function(){
			index_id = $("#can").attr("index_id");
			$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", {index_id:index_id, base64:d.toDataURL()}, function(data){
				if(isNaN(data)){ alert(data) }else{
					$("#result").html("<img src="+d.toDataURL()+' />');
					$("#data").val(d.toDataURL());
					$("#can").attr("index_id", data);
				}
			});
		});
		$("#clear").click(function(){
			e.fillStyle="#fff";
			e.fillRect(0,0,d.width,d.height);
			e.strokeStyle="red";
			e.fillStyle="red"
		})
	});

	function saveRestorePoint() {
		var oCanvas = document.getElementById("can");
		var imgSrc = oCanvas.toDataURL("image/png");
		restorePoints.push(imgSrc);
	}

	function undoDrawOnCanvas() {
			if (restorePoints.length > 0) {
			var oImg = new Image();
			oImg.onload = function() {
				var canvasContext = document.getElementById("can").getContext("2d");		
				canvasContext.drawImage(oImg, 0, 0);
			}
			oImg.src = restorePoints.pop();
		}
	}
</script>

<link href="css/style.css" type="text/css" rel="stylesheet" />

<div id="themain" style="overflow:hidden;">
	<canvas height="400" width="485" id="can" index_id="0"></canvas>
	<div id="clr">
		<div style="background-color:black;" color="#00000"></div>
		<div style="background-color:red;" color="#ff0000"></div>
		<div style="background-color:green;" color="#00ff00"></div>
		<div style="background-color:orange;" color=""></div>
		<div style="background-color:#FFFF00;" color="#FFFF00"></div>
		<div style="background-color:#F43059;" color="#F43059"></div>
		<div style="background-color:#ffffff;" color="#ffffff"></div>
		<div style="background-color:#9ecc3b;" color="#9ecc3b"></div>
		<div style="background-color:#fbd;" color="#fbd"></div>
		<div style="background-color:#fff460;" color="#fff460"></div>
		<div style="background-color:#F43059;" color="#F43059"></div>
		<div style="background-color:#82B82C;" color="#82B82C"></div>
		<div style="background-color:#0099FF;" color="#0099FF"></div>
		<div style="background-color:#ff00ff;" color="#ff00ff"></div>
		<div style="background-color:#8000FF;" color="#8000FF"></div>
		<div style="background-color:#FF8000;" color="#FF8000"></div>
		<div style="background-color:#99FE00;" color="#99FE00"></div>
		<div style="" size="#1200FF"></div>
		<div style="" size="3"><div style="border:1px solid #ddd; margin:3px auto; width:6px; height:6px; border-radius:3px;"></div></div>
		<div style="" size="5"><div style="border:1px solid #ddd; background-color:red; margin:5px auto; width:10px; height:10px; border-radius:5px;"></div></div>
	</div><br><br>
	<br style="clear:both;">
	<div style="float:right;">
		<span id="result"><br></span> 
	</div>
	<span class="size">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
	</span>
	Размер кисти: <input type="range" id="bsz" value="5" max="50" min="0"><br><br>
	Цвет кисти: <input type="color" id="bcl" value="#FF00FF" placeholder="#FF00FF"><br>
	<div id="btns">
		<a class="minimal" href="javascript:" id="undo">Назад</a>
		<a class="minimal" href="javascript:" id="save">Сохранить</a>
		<a class="minimal" href="javascript:" id="clear">Очистить</a>
	</div><br>
	<form class="file" action="/<?=$arg['modname']?>:<?=$arg['fn']?>/null" method="post" enctype="multipart/form-data">
		<script src="/include/jquery/jquery.iframe-post-form.js"></script>
		<script>
			$(function(){
				$("#themain form.file").iframePostForm({
					complete:function(data){
						if(isNaN(data)){ alert(data) }else{
							var d=document.getElementById("can");
							var e=d.getContext("2d");

							var image = new Image();
							image.src = "/convas:img/"+data+"/tn:index_img/fn:img/w:500/h:400/null/img.jpg";
							$(image).load(function() {
								e.drawImage(image, (d.width-image.width)/2, (d.height-image.height)/2);
							});

							$("#themain form.file input[type='file']").val("");
						}
					}
				});
			});
		</script>
		<input type="hidden" name="index_id" value="0">
		<input type="file" name="img">
		<input type="submit" value="Загрузить фото">
	</form>
	<br style="clear:both;">
	<!-- [settings:foto_lightbox] -->
	<div id="gallery">
		<? foreach($tpl['index'] as $v): ?>
			<div style="width:110px; height:110px; float:left;">
				<a href="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:index/fn:img/w:600/h:500/null/img.jpg">
					<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:index/fn:img/w:100/h:100/null/img.jpg">
				</a>
			</div>
		<? endforeach; ?>
	</div>
</div>