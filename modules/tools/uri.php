<? die;

if($_POST['uri']){
	echo "<div style=text-align:center;>";
	echo $_POST['uri'];
	$get = mpgt($_POST['uri']);
	echo "<br />"; print_r($get);
	echo "<br />". $sm = "array://".serialize($get);
	echo "</div>";
}

?>