<?

function array_map_recursive($func, $array) {
    foreach($array as $key => $val) {
        if ( is_array( $array[$key] ) ) {
            $array[$key] = array_map_recursive($func, $array[$key]);
        } else {
            $array[$key] = call_user_func( $func, $val );
        }
    }
    return $array;
}

function nesting2($text, $tags = array("\? if", "\? endif", "\? foreach", "\? endforeach", "html", "div", "span", "table", "ul", "li", "tr", "td", "form", "label", "button", "script", "noscript", "p", "a")){
	if(preg_match_all($str = "#<(\/?)(". implode("|", $tags). ")(.*?)>#si", $text, $match)){
		$nesting = $tags = array();// mpre($str, array_slice($match, 1));
		foreach($match[2] as $n=>$tag_name){
			$tn = last($nesting);
			if(($sl = get($match, 1, $n)) && ($tag_name == $tn)){
				$tn = array_pop($nesting);
			}elseif(($tn == "? if") && ($tag_name == "? endif")){
				$tn = array_pop($nesting);
			}elseif(($tn == "? foreach") && ($tag_name == "? endforeach")){
				$tn = array_pop($nesting);
			}else{
				$nesting[$n] = $tag_name; //array_push($nesting, $tag_name);
				$tags[$n] = "&lt;". ($sl ? "/" : ""). $match[2][$n]. $match[3][$n]. "&gt;"; //array_push($tags, "&lt;". $match[2][$n]. $match[3][$n]. "&gt;");
			}
		} return empty($nesting) ? false : array_intersect_key($tags, $nesting);
		// return empty($nesting) ? false : $nesting;
	}else{ return null; }
}
