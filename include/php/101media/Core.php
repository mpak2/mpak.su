<?php
/**
 * Модель "база данных -> объекты" (Object-relational mapping)
 * @package ORM
 * @subpackage Core
 * @version 2.1.0
 * @copyright 2009, 101 Media
 * @author Cellard
 */

/**
 * Ошибка хранилища данных
 */
class DepotException extends ORM_Exception {
  protected $domain = "Ошибка хранилища данных";
}

/**
 * Хранилище объектов
 *
 */
class Depot {
	private static $d = array();
	private $name;
	private $collection = false;
	private $item = false;
	private $id;
	/**
	 *
	 * @param $Object aObject
	 * @return unknown_type
	 */
  public function __construct($Object)
  {
  	if ($Object instanceof aCollection || $Object instanceof aItem) {
  	  $this->name = $Object->name();
      $this->id   = $this->id($Object);
      if ($Object instanceof aItem) $this->collection = $Object->iCollection();
      if ($Object instanceof aCollection) $this->item = $Object->iItem();

      if ($this->id) {
    		if (!isset(self::$d[$this->name])) self::$d[$this->name] = array();
    		if (!isset(self::$d[$this->name][$this->id])) self::$d[$this->name][$this->id] = array();
      }
  	} else {
  		throw new DepotException("Depot доступен для использования объектам класса aCollection или aItem");
  	}
  }
  /**
   * Полностью очищает хранилище
   */
  public static function reset()
  {
    self::$d = array();
  }
  private function id($Object)
  {
    if ($Object instanceof aItem)       return $Object->key();
    if ($Object instanceof aCollection) return md5($Object->SQL()->SELECT());
  }
  public function __get($prop)
  {
  	if ($this->id && !isset(self::$d[$this->name][$this->id][$prop])) {
  		switch ($prop) {
  			case 'loaded':  self::$d[$this->name][$this->id][$prop] = false; break;
  			case 'valid':   self::$d[$this->name][$this->id][$prop] = false; break;
  			case 'row':     self::$d[$this->name][$this->id][$prop] = array(); break;
  			case 'wet':     self::$d[$this->name][$this->id][$prop] = false; break;
  			case 'rows':    self::$d[$this->name][$this->id][$prop] = array(); break;
  			case 'keys':    self::$d[$this->name][$this->id][$prop] = array(); break;
  		}
  	}

  	return @self::$d[$this->name][$this->id][$prop];
  }
  public function __set($prop, $value)
  {
    if ($this->id) self::$d[$this->name][$this->id][$prop] = $value;
  }
  /**
   * Очистить хранилище данного объекта
   */
  public function cleanup()
  {
    unset(self::$d[$this->name][$this->id]);
  }
  public function __unset($prop)
  {
    unset(self::$d[$this->name][$this->id][$prop]);
  }
  /**
   * Ищет среди всех Item данного типа по маске и возвращает id
   *
   * @param string $ClassName имя интересующего класса
   * @param array $mask маскирующий массив
   * @return integer
   */
  public static function search($ClassName, $mask)
  {

    $rows = self::$d;
    $rows = @$rows[$ClassName];

    if (!$rows) return null;

    $id = 0;

    $Item = Depot::Item($ClassName);

    foreach ($rows as $id=>$properties) {
      $row = @$properties['row'];
      if (!$row) continue;
      foreach ($mask as $key=>$val) {
        if (in_array($key, array_keys($row))) {
          $Column = $Item->Table()->Column($key);

          $rVal = $Column->castSet($row[$key]);
          if (is_array($val)) {
          	$found = false;
          	foreach ($val as $key=>$v) {
          		$mVal = $Column->castSet($v);
	          	if (is_null($rVal) && is_null($mVal)) {
	              /* совпадение значения */
	          		$found = true;
	            } elseif ($rVal == $mVal) {
	              /* совпадение значения */
	            	$found = true;
	            }
          	}
          	if (!$found) continue 2;
          } else {
	          $mVal = $Column->castSet($val);
	          if (is_null($rVal) && is_null($mVal)) {
	            /* совпадение значения */
	          } elseif ($rVal == $mVal) {
	            /* совпадение значения */
	          } else {
	            continue 2;
	          }
          }
        } else {
          continue 2;
        }
      }
      /* если мы здесь, значит маска сработала */
      return $id;
    }

    return null;
  }
  public static function dump()
  {
    return self::$d;
  }
  /**
   * Создает и возвращает объект типа aItem
   * @param $className
   * @param $id
   * @param $row
   * @return aItem
   */
  public static function Item($className, $id = 0, $row = array())
  {
    return new $className($id, $row);
  }
  /**
   * Создает и возвращает объект типа aCollection
   * @param $className
   * @param $param
   * @return aCollection
   */
  public static function Collection($className, $param = false)
  {
    return new $className($param);
  }
}

/**
 * Базовый класс для представления всякого объекта, основанного на базе данных
 *
 * Реализует базовые настройки, проверки и приведение типов данных в классе.
 *
 */
abstract class aObject {
  /**
   * Внешние объекты
   * @var array
   */
  protected $external = array();
  /**
   * Запрос
   * @var SQLExpression
   */
  protected $SQL;
  /**
   * Таблица объекта
   * @var DatabaseTable
   */
  protected $Table;
  /**
   * Подключает объект базы данных
   */
  public function __construct()
  {
    $iTable = $this->iTable();
    if (is_array($iTable)) {
      $this->Table = new DatabaseTable($iTable[0], $iTable[1]);
    } else {
      $this->Table = new DatabaseTable($iTable);
    }
    $this->SQL = new SQLExpression($this->Table);
  }
  /**
   * Клонирование вложенных объектов
   */
  public function __clone()
  {
    $this->SQL = clone $this->SQL;
  }
  /**
   * Таблица объекта
   * @return DatabaseTable
   */
  final public function Table()
  {
    return $this->Table;
  }
  /**
   * Синхронное хранилище данных
   * @return Depot
   */
  final public function Storage()
  {
    return new Depot($this);
  }
  /**
   * Объект запроса к базе данных
   * @return SQLExpression
   */
  final public function SQL($debug = false)
  {
  	$SQL = $this->SQL->__instance($this);
    return $SQL;
  }
  /**
   * Должен возвращать имя таблицы, на которой основывается объект (а если массив, то второй элемент это префикс полей)
   * @return string || array
   */
  abstract public function iTable();
  /**
   * Вынуждает объект повторно загрузить данные из базы
   */
  public final function reload()
  {
    $this->Storage()->loaded = false;
  }
  /**
   * Загружен ли объект
   * @return boolean
   */
  public final function loaded()
  {
    return $this->Storage()->loaded;
  }
  /**
   * Подключает к данному объекту 1-ко-многим другой объект через внешний ключ
   * Приводит к перезагрузке объекта
   *
   * @param $Object aObject
   * @param $fk string имя внешнего ключа
   */
  final public function append($Object, $FK)
  {
    if ($Object instanceof aItem || $Object instanceof aCollection) {
    	try {
        $Column = $this->Table()->Column($FK);
    	} catch (DatabaseException $e) {
    		$Column = $Object->Table()->Column($FK);
    	}
      $this->external[$Column->__toString()] = $Object;
      $this->SQL()->append($Object->Table(), $Column);
    } else {
      throw new ConstructorException('Для указания внешней таблицы в методе append() надо использовать объект типа aItem или aCollection');
    }
    $this->reload();
  }
  /**
   * Проверяет класс на предмет реализации интерфейса и в случае несоответствия выбрасывает исключение
   * @throws ConstructorException
   * @param string $interface
   * @return void
   */
  final protected function isImplements($interface)
  {
    if (!$this instanceof $interface) throw new ConstructorException("Класс {$this->name()} не реализует интерфейс {$interface}");
  }
  /**
   * Проверяет массив $row на соответствие правилам
   *
   * @param array $row
   * @return boolean
   */
  protected function validate($row)
  {

    if (!$this->Storage()->loaded) {
      throw new ObjectValidationException($this->name(), EXCEPTION_OBJECT_NOT_LOADED);
    }

    $valid = true;

    $keys = array_keys($row);

    foreach ($this->Table()->columns() as $Column) {

      // Если имени поля нет среди ключей $row - объект не валидный
      if (!in_array($Column->__toString(), $keys)) {
        $valid = false;
        throw new ObjectValidationException($Column->__toString(), EXCEPTION_WASTE_FIELD);
      }

      // Если значение обязательного поля пусто и не имеет значения по умолчанию - объект не валидный
      if ($Column->required() && is_null($row[$Column->__toString()]) && is_null($Column->defaultValue())) {
      	$valid = false;
        throw new ObjectValidationException($Column->__toString(), EXCEPTION_MISSED_MANDATORY_FIELD);
      }

    }

    $this->Storage()->valid = $valid;

    return $valid;
  }
  /**
   * Выбрасывает пользовательское исключение с произвольным текстом
   * @param $text
   * @return void
   */
  public final function throwException($text)
  {
    throw new CustomException($text);
  }
  /**
   * Имя класса текущего объекта
   *
   * @return string
   */
  final public function name()
  {
    return get_class($this);
  }
  /**
   * Фиксирует в реестре присодененные к данному внешние объекты
   * @param array $row массив с данными
   * @return void
   */
  final protected function registerExternal($row)
  {
    /* Засовываем в реестр объекты, которые были append() */
    foreach ($this->external as $FK=>$Object) {
    	try {
        $FK = $this->Table()->Column($FK)->name();
    	} catch (DatabaseException $e) {
    		$FK = $Object->Table()->Column($FK)->name();
    	}
      if ($row[$FK]) {
        if ($Object instanceof aCollection) $itemClassName = $Object->iItem();
        if ($Object instanceof aItem) $itemClassName = $Object->name();
        new $itemClassName($row[$FK], $row);
      }
    }
  }
}



