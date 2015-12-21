<?

error_reporting(0);
ini_set('display_errors', '0');
global $HTTP_RAW_POST_DATA;

header("Pragma: no-cache");
header("Cache-control: no-cache, must-revalidate");

require_once(mpopendir('modules/xmlrpc/include/class-IXR.php'));
require_once(mpopendir('modules/xmlrpc/include/mysql_storage.php'));
require_once(mpopendir('modules/xmlrpc/include/posts_mysql_storage.php'));

if (file_exists($file_name = mpopendir('modules/xmlrpc/post.xml'))){
	$HTTP_RAW_POST_DATA= file_get_contents($file_name);
}else{
	$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
}

if ( isset($HTTP_RAW_POST_DATA) )
	$HTTP_RAW_POST_DATA = trim($HTTP_RAW_POST_DATA);

function logIO($io,$msg) {
	global $xmlrpc_logging;
	if (file_exists($file_name = mpopendir("modules/xmlrpc/xmlrpc.log"))) {
		$fp = fopen($file_name,"a+b");
		$iot = ($io == "I") ? " Input: " : " Output: ";
		fwrite($fp, "\n\n".gmdate("Y-m-d H:i:s ").$iot.$msg);
		fclose($fp);
	}
	return true;
} logIO("I", $HTTP_RAW_POST_DATA);

class xmlrpc_server extends IXR_Server {

	function xmlrpc_server() {
		$methods = array(
			'metaWeblog.newPost' => 'this:mw_newPost',
			'metaWeblog.getPost' => 'this:mw_getPost',
			'metaWeblog.editPost' => 'this:mw_editPost',
			'blogger.deletePost' => 'this:blogger_deletePost',
		);
		$this->IXR_Server($methods);
	}

    function init_db(){
        if(isset($this->db)){
            return true;
        }
        try{
            $this->db = new posts_mysql_storage();
        }catch(Exception $e){
            $this->error = new IXR_Error(500, 'DB Error. ' . $e);
            return false;
        }
        return true;
    }

    function common(&$args){
        list($_id, $user_login, $user_pass) = $args;
		$user = mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}users WHERE name = '".mpquot($user_login)."'"), 0);
		$mod = mpql(mpqw("SELECT id FROM `{$GLOBALS['conf']['db']['prefix']}modules` WHERE `folder`='pages'"), 0);
		$uaid = mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}modules_uaccess WHERE `uid`={$user['id']} AND `mid`='{$mod['id']}'"), 0, 'access');
		$gaid = mpql(mpqw("SELECT MAX(`access`) as aid FROM {$GLOBALS['conf']['db']['prefix']}modules_gaccess WHERE gid IN (SELECT gid FROM `{$GLOBALS['conf']['db']['prefix']}users_mem` WHERE `uid`='{$user['id']}') AND `mid`='{$mod['id']}' ORDER BY access DESC"), 0, 'aid');
        if(!$user || $user['pass'] != mphash($user_login, $user_pass) || max($gaid, $uaid) < 4){
			$this->error = new IXR_Error(403, 'Bad login/pass combination.');
            return false;
        }
        if ( !$this->init_db() ) {
			return false;
        }
        return $user['id'];
    }

    function get_post_data(&$content_struct, $user_id){
		$catnames = $content_struct['categories'];
		if (is_array($catnames)) {
            $data['post_category'] = join('|', $catnames);
		}
		unset($content_struct['categories']);
        return $content_struct;
    }

    function mw_newPost($args) {
        //return '1'; #DEBUG post_id
        if(!($user_id = $this->common($args))){
            return $this->error;
        }
#		$user_login  = $args[1];
#		$user_pass   = $args[2];

		# Добавляем нужные поля
//		$content_values = $args[3];
		$content_values = array(
			'cat_id' => (int)$args[0], #Категория в которую попадают статьи
			'uid' => $user_id, # Идентификатор пользователя. Указывается в интерфейсе лекса.
			'name' => $args[3]['title'],
			'text' => $args[3]['description'],
			'time' => time(),
		);
//		$content_struct['title'] = iconv('UTF-8', 'CP1251', $content_struct['title']);
//		unset($content_struct['description']);
//		unset($content_struct['flNotOnHomePage']);
//print_r($content_values);
        $data = $this->get_post_data($content_values, $user_id);
        try{
            $post_id = $this->db->new_post($data);
            return strval($post_id);
        }catch(Exception $e){
            return new IXR_Error(500, $e);
        }
	}

	function mw_getPost($args) {
        if(!$this->common($args)){
            return $this->error;
        }
		$post_id     = (int) $args[0];
		$user_login  = $args[1];
		$user_pass   = $args[2];

        try{
            $post = $this->db->get_post($post_id);
            if(!$post){
    			return new IXR_Error(404, 'Sorry, no such post.');
            }
			$resp = array(
				'postid' => $post['id'],
				'description' => $post['text'],
				'name' => $post['title'],
				'link' => "http://{$_SERVER['HTTP_HOST']}/pages/{$post['id']}",
			);
            if(isset($post['post_category'])){
                $resp['categories'] = split('\|', $post['post_category']);
            }
			return $resp;
        }catch(Exception $e){
            return new IXR_Error(500, $e);
        }
	}

	function mw_editPost($args) {
        //return true; #DEBUG
        if(!($user_id = $this->common($args))){
            return $this->error;
        }
		$post_id     = (int) $args[0];
		$user_login  = $args[1];
		$user_pass   = $args[2];
//		$content_struct = $args[3];

		$content_values = array(
//			'kid' => (int)$args[0], #Категория в которую попадают статьи
			'uid' => $user_id, # Идентификатор пользователя. Указывается в интерфейсе лекса.
			'name' => $args[3]['title'],
			'text' => $args[3]['description'],
			'time' => time(),
		);

        try{
            $post = $this->db->get_post($post_id);
            if(!$post){
    			return new IXR_Error(404, 'Sorry, no such post.');
            }
            $data = $this->get_post_data($content_values);
            $this->db->update_post($post_id, $data);
            return true;
        }catch(Exception $e){
            return new IXR_Error(500, $e);
        }
	}

	function blogger_deletePost($args) {
        //return true; #DEBUG
        array_shift($args);
        if(!$this->common($args)){
            return $this->error;
        }
		$post_id     = (int) $args[0];
		$user_login  = $args[1];
		$user_pass   = $args[2];

        try{
            $post = $this->db->get_post($post_id);
            if(!$post){
    			return new IXR_Error(404, 'Sorry, no such post.');
            }
            $this->db->del_post($post_id);
            return true;
        }catch(Exception $e){
            return new IXR_Error(500, $e);
        }
	}

}

$xmlrpc_server = new xmlrpc_server($CONFIG);
