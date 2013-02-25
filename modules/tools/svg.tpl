<style>
	div.svg > svg {border:1px solid gray;}
</style>
<div style="width:100%; height:300px; padding:5px;" class="svg"></div>
<script>
	$(function(){
		$("textarea.svg").keypress(function(){
			setTimeout(function(){
				svg = $("textarea.svg").delay(100).val();
				$("div.svg").html(svg);
			}, 100);
		}).keypress();
	});
</script>
<textarea class="svg" style="width:100%; height:280px;">
<svg width="100%" height="8cm" viewBox="0 0 1200 600" xmlns="http://www.w3.org/2000/svg">
	<path d="M200,300 Q400,50 600,300 T1000,300" fill="none" stroke="red" stroke-width="5"  />
	<g fill="black" >
		<circle cx="200" cy="300" r="10"/>
		<circle cx="600" cy="300" r="10"/>
		<circle cx="1000" cy="300" r="10"/>
	</g>
	<g fill="#888888" >
		<circle cx="400" cy="50" r="10"/>
		<circle cx="800" cy="550" r="10"/>
	</g>
	<path d="M200,300 L400,50 L800,550 L1000,300" fill="none" stroke="#888888" stroke-width="2" />
</svg>
</textarea>