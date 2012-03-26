<? die;

// $index = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));
// //mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET w=0, ot=0, d=\"\"");
// 
// foreach($index as $k=>$v){
// 	mpre($v['name']);
// 	preg_match("/([0-9]+)\/([0-9]+)(R[0-9]+)/", $v['name'], $reg);
// 	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET w=". (int)$reg[1]. ", ot=". (int)$reg[2]. ", d=\"". mpquot($reg[3]). "\" WHERE id=". (int)$v['id']);
// }

?>