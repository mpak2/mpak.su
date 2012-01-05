<?php

require_once "mysql_query_holder.php";

function prepare_query($template){
  $args = is_array($template) ? $template : func_get_args();
  $template = array_shift($args);
  $_args = array();
  for($i = 1; $i < count($args); $i++){
    $arg = func_get_arg($i);
    array_push($_args, mysql_real_escape_string($arg) );
  }
  return vsprintf($template, $_args);
}

function prepare_insert($info_hash){
  $fields = array();
  $values = array();
  foreach($info_hash as $k => $v){
    $fields[]= sprintf("`%s`", $k);
    $values[]= sprintf("'%s'", mysql_real_escape_string($v) );
  }
  return array($fields, $values);
}

function prepare_update($info_hash){
  $pairs = array();
  foreach($info_hash as $k => $v){
    $pairs[]= sprintf("`%s` = '%s'", $k, mysql_real_escape_string($v) );
  }
  return $pairs;
}

class mysql_storage{
  protected $db_conn_obj;

  function __construct($host, $login, $password, $db = FALSE){
    list($this->host, $this->login, $this->password, $this->db) = array($host, $login, $password, $db);
    $this->db_conn_obj = $GLOBALS['conf']['db']['conn'];
    $this->debug = 0;
  }

function escape($v){
	return mysql_real_escape_string($v);
}

  function connect(){
      mysql_query("SET NAMES 'cp1251';");
  }

  function insert($table, $params){
    list($fields, $values) = prepare_insert( $params );
    $query = sprintf("INSERT INTO `%s` (%s) VALUES(%s)", $table, join(",", $fields), join(",", $values));
    return $this->query($query);
  }

  function update($table, $condition, $params){
    if(is_array($condition)){
      $pairs = prepare_update($condition);
      $where = join(' AND ', $pairs);
    }else{
      $where = $condition;
    }
    $pairs = prepare_update( $params );
    $query = sprintf("UPDATE `%s` SET %s WHERE %s", $table, join(",", $pairs), $where);
    return $this->query($query);
  }

  function delete($table, $condition){
    if(is_array($condition)){
      $pairs = prepare_update($condition);
      $where = join(' AND ', $pairs);
    }else{
      $args = func_get_args();
      $table = array_shift($args);
      $where = prepare_query($args);
    }
    $query = sprintf("DELETE FROM `%s` WHERE %s", $table, $where);
    return $this->query($query);
  }

  function replace($table, $params){
    list($fields, $values) = prepare_insert( $params );
    $query = sprintf("REPLACE INTO `%s` (%s) VALUES(%s)", $table, join(",", $fields), join(",", $values));
    return $this->query($query);
  }

  function select($table, $fields_list = 0, $conditions = 0, $limit = 0, $offset = 0, $order = 0){
    if($fields_list){
      $ff = array();
      foreach($fields_list as $field){
        $ff[] = "`" . mysql_real_escape_string($fields) . "`";
      }
      $fields_list = join(',', $ff);
    }else{
      $fields_list = '*';
    }
    $query = sprintf("SELECT %s FROM `%s`", $fields_list, mysql_real_escape_string($table));
    if($conditions){
      $pairs = array();
      foreach($conditions as $k => $v){
        if(is_array($v)){
        }else{
#          $v = $conditions[$k];
        }
        $pairs[] = sprintf("`%s` = '%s'", mysql_real_escape_string($k), mysql_real_escape_string($v));
      }
      if(count($pairs)) $query .= " WHERE " . join(' AND ', $pairs);
    }
    if($order){
      $query .= " ORDER BY $order";
    }
    if($limit){
      $query .= " LIMIT $offset, $limit";
    }
    return $this->query($query);
  }

  function query($query){
    if($this->debug){
      logging_ts( "$query\n" );
      if($this->debug > 1){
        foreach(debug_backtrace() as $Line){
          logging( serialize($Line) . "\n" );
        }
      }
      logging( "\n" );
    }
    $this->ping();
    return new mysql_query_holder($query, $this->db_conn_obj);
  }

  function ping(){
    if(mysql_ping($this->db_conn_obj) === FALSE){
    }
  }

  function get_query_value($query){
    $res = $this->query( $query );
    $row = $res->get_row();
    return $row[0];
  }

}
