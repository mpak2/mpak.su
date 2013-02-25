<? die;

$conf['tpl']['vacancy'] = mpql(mpqw("SELECT id.*, u.name AS uname, u.mtel
	FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id
	LEFT JOIN {$conf['db']['prefix']}users AS u ON (id.uid=u.id)
	ORDER BY id.id DESC LIMIT 10"
));

$conf['tpl']['cnt'] = mpqn(mpqw("SELECT c.id, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON c.id=id.cat_id GROUP BY c.id"));

$conf['tpl']['cat'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"), 'cat_id', 'id');

$readtree = function ($id, $readtree, $tree = array()) use($arg) { global $conf;
	foreach((array)$conf['tpl']['cat'][ $id ] as $k=>$v){
		$tree[$v['id']] = $readtree($v['id'], $readtree);
		$tree[$v['id']][''] = $v;
		$conf['tpl']['cnt'][ $id ]['cnt'] += $conf['tpl']['cnt'][ $v['id'] ]['cnt'];
	}
	return $tree;
};

$conf['tpl']['tree'] = $readtree((int)$_GET['id'], $readtree);
$conf['tpl']['tree'][''] = array(
	"cat_id"=>"-1",
	"name"=>"Вакансии",
);

//mpre($conf['tpl']['cnt']);

?>