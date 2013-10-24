<?

// router.php
/*if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
	return false;
}else*/

if(strpos($_SERVER["REQUEST_URI"], "/img/") === 0){
	header("Content-Type: image/". array_pop(explode(".", $_SERVER["REQUEST_URI"])));
	readfile($_SERVER["SCRIPT_FILENAME"]);
}elseif(strpos($_SERVER["REQUEST_URI"], "/include/") === 0){
	$ext = array("js"=>"text/javascript");
	$e = array_pop(explode(".", $_SERVER["REQUEST_URI"]));
	header("Content-Type: ". ($ext[$e] ?: "text/{$e}"));
	readfile($_SERVER["SCRIPT_FILENAME"]);
}else{ 
	include "index.php";
}