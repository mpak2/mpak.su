<?

class posts_mysql_storage{

  function __construct(){
    $this->posts = "{$GLOBALS['conf']['db']['prefix']}pages_index";
    $this->storage = new mysql_storage();
  }

  function new_post($data){
    $qr = $this->storage->insert($this->posts, $data);
    return $qr->get_insert_id();
  }

  function get_post($post_id){
    $qr = $this->storage->select($this->posts, 0, array('id'=>$post_id), 1, 0);
    $row = $qr->get_hash();
    return $row;
  }

  function update_post($post_id, $data){
    $this->storage->update($this->posts, array('id' => $post_id), $data);
  }

  function del_post($post_id){
    $this->storage->delete($this->posts, array('id' => $post_id));
  }

}
