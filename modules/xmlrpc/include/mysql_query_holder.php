<?php

class mysql_query_holder{
  public $res, $query, $db_conn_obj;  

  function __construct($query, &$db_conn_obj){
    list($this->query, $this->db_conn_obj) = array($query, &$db_conn_obj);
    if(($this->res = mysql_query($query, $db_conn_obj)) == FALSE){
      throw new Exception(mysql_error() . "\nQUERY: $query");    
    }
  }

  function get_all(){
    $rows = array();
    while($row = $this->get_row()){
      $rows[] = $row;
    }
    return $rows;
  }
  
  function get_all_as_hash(){
    $rows = array();
    while($row = $this->get_hash()){
      $rows[] = $row;
    }
    return $rows;
  }
  
  function get_all_as_map(){
    $rows = array();
    while($row = $this->get_row()){
      list($k, $v) = $row;
      $rows[$k] = $v;
    }
    return $rows;
  }
  
  function get_row(){
    return mysql_fetch_row($this->res);
  }

  function get_value(){
    if(($row = $this->get_row()) === FALSE){
      return FALSE;
    }
    list($v) = $row;
    return $v;
  }
  
  function get_hash($type = MYSQL_ASSOC){
    return mysql_fetch_array($this->res, $type);
  }

  function get_fields(){
    return mysql_fetch_assoc($this->res);
  }

  function fields(){
    $fields = array();
    for($i = 0; $i < mysql_num_fields($this->res); $i++){
      $fields[] = mysql_field_name($this->res, $i);
    }
    return $fields;
  }

  function get_row_obj(){
    return mysql_fetch_object($this->res);
  }
  
  function get_insert_id(){
    return mysql_insert_id($this->db_conn_obj);
  }

  function __destruct(){
    if(gettype($this->res) == "resource"){
      if(!mysql_free_result($this->res)){
        throw new Exception(mysql_error());
      }
      $this->res = FALSE;
    }
  }
  
  function processed(){
    return mysql_affected_rows($this->db_conn_obj);
  }

  function count(){
    return mysql_num_rows($this->res);
  }
}