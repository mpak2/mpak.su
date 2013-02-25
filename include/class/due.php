<?

class due{
	static $e = "";
	static $mpre = array(); # Текущее состояние связей
	var $conf = array(); # Ссылка на глобальную переменную
	var $arg = array(); # Состояние переменных модуля или блока переданные при создании обьекта
	static $due = array(); # Список сконвертированных связей начиная с нуля
	static $get = array(); # Список всех возвращенных связей таблицы от нуля
	
	static function tie($e, $f = "id", $t = "id", $w = array()){
		if(gettype($e) == "string"){
			self::$e = $e = qn($sql = "SELECT * FROM ". ($tb = self::$conf['db']['prefix']. self::$arg['modpath']. "_". $e). mpwr($tb, $w));
		}else{
			self::$e = $e;
		}// mpre(func_get_args());
		$get = $due = array();
		if($e) foreach($e as $k=>$v){
			if(($w == null) || ((gettype($w) == "array") && array_intersect_key($v, $w) == $w) || (is_numeric($w) && $v['id'] == $w)){
				$get[ $v[ $f ] ][ $v["id"] ] = $v[ $t ];
			}
		} self::$get[] = $get;
		if($count = count(self::$due)){
			foreach(self::$due[ $count - 1 ] as $k=>$list){
				foreach($list as $n=>$v){
					if($dt = (array)$due[ $k ] + (array)$get[ $v ]){
						$due[ $k ] = $dt;
					}
				}
			} self::$due[] = self::$mpre = $due;
		}else{
			self::$due[] = self::$mpre = $get;
		} return self;
	}
	static function mpre(){
//		mpre(array_pop($tmp = self::$due));
//		return $this;
	}
}

/*class due{
	var $mpre = array(); # Текущее состояние связей
	var $conf = array(); # Ссылка на глобальную переменную
	var $arg = array(); # Состояние переменных модуля или блока переданные при создании обьекта
	var $due = array(); # Список сконвертированных связей начиная с нуля
	var $get = array(); # Список всех возвращенных связей таблицы от нуля

	function tie($e, $f = "id", $t = "id", $w = array()){
		if(gettype($e) == "string"){
			$this->e = $e = qn($sql = "SELECT * FROM ". ($tb = "{$this->conf['db']['prefix']}{$this->arg['modpath']}_{$e}"). mpwr($tb, $w));
		}else{
			$this->e = $e;
		}// mpre(func_get_args());
		$get = $due = array();
		if($e) foreach($e as $k=>$v){
			if(($w == null) || ((gettype($w) == "array") && array_intersect_key($v, $w) == $w) || (is_numeric($w) && $v['id'] == $w)){
				$get[ $v[ $f ] ][ $v["id"] ] = $v[ $t ];
			}
		} $this->get[] = $get;
		if($count = count($this->due)){
			foreach($this->due[ $count - 1 ] as $k=>$list){
				foreach($list as $n=>$v){
					if($dt = (array)$due[ $k ] + (array)$get[ $v ]){
						$due[ $k ] = $dt;
					}
				}
			} $this->due[] = $this->mpre = $due;
		}else{
			$this->due[] = $this->mpre = $get;
		} return $this;
	}
	function each($from, $to = null){
		foreach($this->e as $v){
			$from($v);
			if($to){
				
			}
		} return $this;
	}
	function due($c = null, $a = null){
		global $conf, $arg;
		if(empty($this->conf)) $this->conf = $c ?: $conf;
		if(empty($this->arg)) $this->arg = $a ?: $arg;
		$this->due = $this->get = $this->mpre = $this->shift = array();
		return $this;
	}
	function mpre(){
		mpre(array_pop($tmp = $this->due));
		return $this;
	}
}*/

/*class due{
	var $e = array(); # Последний массив данных таблицы
	var $conf = array(); # Ссылка на глобальную переменную
	var $arg = array(); # Состояние переменных модуля или блока переданные при создании обьекта
	var $due = array(); # Список сконвертированных связей начиная с нуля
	var $get = array(); # Список всех возвращенных связей таблицы от нуля
	var $mpre = array(); # Текущее состояние связей
	var $shift = array();

	function due($c = null, $a = null){
		global $conf, $arg;
		if(empty($this->conf)) $this->conf = $c ?: $conf;
		if(empty($this->arg)) $this->arg = $a ?: $arg;
		$this->due = $this->get = $this->mpre = $this->shift = array();
	}
	function tie($e = null, $f = "id", $t = "id", $w = null){
		if(gettype($e) == "string"){
			$this->e[] = $e = qn($sql = "SELECT * FROM ". ($tb = "{$this->conf['db']['prefix']}{$this->arg['modpath']}_{$e}"). mpwr($tb, $w));
		}else{
			$this->e[] = $e;
		}
		if(empty($e)){
			$this->due = $this->get = array();
			return $this;
		}
		$get = $due = array();
		if($e) foreach($e as $k=>$v){
			if(($w == null) || ((gettype($w) == "array") && array_intersect_key($v, $w) == $w) || (is_numeric($w) && $v['id'] == $w)){
				$get[ $v[ $f ] ][ $v["id"] ] = $v[ $t ];
			}
		} $this->get[] = $get;
		if($count = count($this->due)){
			foreach($this->due[ $count - 1 ] as $k=>$list){
				foreach($list as $n=>$v){
					if($dt = (array)$due[ $k ] + (array)$get[ $v ]){
						$due[ $k ] = $dt;
					}
				}
			} $this->due[] = $this->mpre = $due;
		}else{
			$this->due[] = $this->mpre = $get;
		} return $this;
	}
	function each($from = null, $to = null){
		$due = array_pop($tmp = $this->due);// mpre($this->due);
		$e = array_pop($tpl = $this->e); mpre($e);
		return($this);
	}
	function mpre($num = null){
		if($num === null){
			mpre(array_pop($tmp = $this->due));
		}else if($num < 0){
			mpre($this->due);
		}else{
			foreach(func_get_args() as $num){
				mpre($this->due[ $num ]);
			}
		} return $this;
	}
	function get($num = null){
		if($num === null){
			mpre(array_pop($tmp = $this->get));
		}else if($num < 0){
			mpre($this->get);
		}else{
			foreach(func_get_args() as $num){
				mpre($this->get[ $num ]);
			}
		} return $this;
	}
	function res($num = null){
		if($num === null){
			return array_pop($tmp = $this->due);
		}else if($num < 0){
			return $this->due;
		}else{
			foreach(func_get_args() as $num){
				return $this->due[ $num ];
			}
		} return $this;
	}
	function e(){
		mpre($this->e);
	}
	function shift($e){
		if($mpre = $this->mpre){
			if($keys = array_shift($mpre)){
				foreach($keys as $k){
					$result[ $k ] = $e[ $k ];
				}
			} return $result;
		}else{ return array(); }
	}
}*/
