<?

// router.php
/*if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
	return false;
}else*/

$stderr = function($error){
	$stderr = fopen('php://stderr', 'w');
	fwrite($stderr, $error. "\n\n");
	fclose($stderr);
};

if(strpos($_SERVER["REQUEST_URI"], "/img/") === 0){
	$stderr("img {$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}\n[{$_SERVER['HTTP_USER_AGENT']}]");
	header("Content-Type: image/". array_pop(explode(".", $_SERVER["REQUEST_URI"])));
	readfile($_SERVER["SCRIPT_FILENAME"]);
}elseif(strpos($_SERVER["REQUEST_URI"], "/include/") === 0){
	$ext = array("js"=>"text/javascript");
	$e = array_pop(explode(".", $_SERVER["REQUEST_URI"]));
	header("Content-Type: ". ($ext[$e] ?: "text/{$e}"));
	readfile($_SERVER["SCRIPT_FILENAME"]);
}else{ 
	$stderr("index {$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}\n[{$_SERVER['HTTP_USER_AGENT']}]");
	include "index.php";
}