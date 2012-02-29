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
 * Класс, представляющий всякое множество, основанное на таблице базы данных
 *
 * Предоставляет механизмы передвижения по элементам коллекции;
 * возвращает элемент коллекции по идентификатору;
 * добавление нового элемента в коллекцию.
 *
 * Обладает механизмом валидации при создании нового элемента.
 * Теоретически, можно попытаться скормить ему всякую фигню - плохо не будет.
 *
 * Реализует интерфейсы:
 * Iterator для перебора элементов коллекции
 * Countable для подсчета количества элементов коллекции
 */
abstract class aCollection extends aObject implements Iterator, Countable {
  /**
   * Фильтрация id IN () вложеным запросом
   */
  const IN_AS_SQL = 'SQL';
  /**
   * Фильтрация id IN () перечислением
   */
  const IN_AS_LIST = 'LIST';

  /**
   * Среднее арифметическое
   * @var real
   */
  const STAT_AVG = 'AVG';
  /**
   * Максимальное значение
   * @var mixed
   */
  const STAT_MAX = 'MAX';
  /**
   * Минимальное значение
   * @var mixed
   */
  const STAT_MIN = 'MIN';
  /**
   * Сумма значений
   * @var mixed
   */
  const STAT_SUM = 'SUM';
  /**
   * Количество значений
   * @var mixed
   */
  const STAT_COUNT = 'COUNT';
  /**
   * Идентификаторы объектов, входящих в коллекцию
   * @var array
   */
  protected $keys = null;
  /**
   * По умолчанию представляет все возможные элементы коллекции
   * 0 конструирует пустую коллекцию
   *
   * @param $param string SQL условие WHERE
   */
  public function __construct($param = false)
  {

    parent::__construct();

    if (is_string($param)) {
      /*
      if ($param && $param == (string)$param) {
        $this->SQL()->where($param);
      }
      */
      throw new ConstructorException("Устаревший конструктор {$this->name()}({$param})");
    } else if ($param === false) {

    } else if ($param ===0 ) {
    	$this->SQL()->where("{$this->Table()->PK()->__toString()}=0");
    } else if (is_array($param)) {
    	$this->SQL()->where($this->SQL()->array2SQL($param));
    } else {
      throw new ConstructorException("Неправильный конструктор {$this->name()}({$param})");
    }

    // Сортировка по умолчанию
    if ($this instanceof iTree) {
      if ($this->Tree()->OrderColumn()) {
        $order = $this->Tree()->OrderColumn()->name();
      	$this->SQL()->order($order);
      }
    }

    if ($this instanceof iReconstructor) $this->__reconstructor();

  }
  /**
   * Строковое значение объекта в виде ClassName(SQL)
   * @return string
   */
  public function __toString()
  {
    return "{$this->name()}({$this->SQL()->SELECT()})";
  }
  /**
   * Возвращает имя парного класса типа aItem
   * @return string
   */
  abstract public function iItem();
  /**
   * Возвращает имя таблицы, на которой основывается объект
   * @return string
   */
  public function iTable()
  {
    $item = $this->iItem();
    $item = new $item();
    return $item->iTable();
  }
  /**
   * Массив идентификаторов элементов коллекции
   *
   * @param string $glue склеить в строку
   * @return mixed
   */
  final public function keys($glue = null)
  {
    $this->load();
    return isset($glue) ? implode($glue, $this->keys) : $this->keys;
  }
  /**
   * Возвращает идентификаторы коллекции для последующего использования в SQL запросе
   *
   * Если идентификаторы уже получены, то вернет их перечисление,
   * а если нет, то SQL запрос для их получения
   * @param integer $method принудительно IN_AS_SQL || IN_AS_LIST. По умолчанию - автоматически
   * @return string
   */
  final public function in($method = false)
  {
    if ($method == self::IN_AS_LIST || ($this->keys && $this->Storage()->loaded && !$method)) {
      return $this->keys(',');
    }
    if ($method == self::IN_AS_SQL || (!$this->Storage()->loaded && !$method)) {
      return $this->SQL()->SELECT($this->Table()->PK()->__toString());
    }
  }


  /**
   * Проверяет данные коллекции на соответствие правилам
   *
   * @uses load()
   * @return boolean
   */
  final protected function validate()
  {
    $this->load();
    $valid = true;

    if (!$this->keys) { // Если нет записей - объект не валидный
      $valid = false;
    }

    $this->Storage()->valid = $valid;
    return $valid;
  }
  /**
   * Количество элементов коллекции испрользуя запрос COUNT; не путать с length()
   * @return integer
   */
  public final function count($force = false)
  {
    $count = ($this instanceof iCacheable) ? $this->Cache()->count : null;

    if ($force || is_null($count)) {
      $count = (integer)Database::fetch(Database::query($this->SQL()->COUNT(), $force ? false : true), 0);
      if ($this instanceof iCacheable) $this->Cache()->count = $count;
    }

    return (integer)$count;
  }
  /**
   * Количество элементов коллекции испрользуя количество рядов в результате запроса; не путать с length()
   * @return integer
   */
  public final function count2($force = false)
  {
    $count = ($this instanceof iCacheable) ? $this->Cache()->count2 : null;

    if ($force || is_null($count)) {
      $count = (integer)Database::rows(Database::query($this->SQL()->SELECT(), $force ? false : true), 0);
      if ($this instanceof iCacheable) $this->Cache()->count2 = $count;
    }

    return (integer)$count;
  }
  /**
   * Количество загруженных элементов коллекции; не путать с count()
   * @return integer
   */
  public final function length()
  {
    $this->load();
    return count($this->keys);
  }
  /**
   * Проверяет, содержит ли коллекция какие либо записи
   * @param $force
   * @return boolean
   */
  public final function isEmpty($force = false)
  {
    return (boolean)!$this->count($force);
  }

  /**
   * Устанавливает указатель на первый элемент коллекции
   *
   */
  final public function rewind()
  {
    $this->load();
    if (is_array($this->keys)) reset($this->keys);
  }

  /**
   * Устанавливает указатель на последний элемент коллекции
   *
   */
  final public function fastforward()
  {
    $this->load();
    if (is_array($this->keys)) end($this->keys);
  }

  /**
   * Возвращает ключ текущего элемента коллекции
   *
   * @return string
   */
  final public function key()
  {
    $this->load();
    return key($this->keys);
  }

  /**
   * Устанавливает указатель на следующий элемент коллекции
   *
   * @return array
   */
  final public function next()
  {
    $this->load();
    return next($this->keys);
  }

  /**
   * Проверяет текущий элемент коллекции
   *
   * @return boolean
   */
  final public function valid()
  {
    $this->load();
    return current($this->keys);
  }

  /**
   * Возвращает текущий элемент коллекции
   *
   * @uses Item()
   * @return aItem
   */
  public function current()
  {
    $this->load();
    $itemClass = $this->iItem();
    $id = current($this->keys);
    if ($id) return new $itemClass($id);
    else return null;
  }

  /**
   * Первый элемент коллекции
   *
   * @return aItem
   */
  final public function first()
  {
    $this->rewind();
    return $this->current();
  }

  /**
   * Последний элемент коллекции
   *
   * @return aItem
   */
  final public function last()
  {
    $this->fastforward();
    return $this->current();
  }
  /**
   * Приводит коллекцию к aItem если в коллекции один единственный элемент
   * @return aItem
   */
  final public function toItem()
  {
    switch ($this->length()) {
      case 0: throw new CastException("В коллекции нет элементов");
      case 1: return $this->first();
      default: throw new CastException("В коллекции более одного элемента");
    }
  }
  /**
   * Возвращает коллекцию в виде массива объектов aItem
   * @return array
   */
  final public function toArray()
  {
    $items = array();
    foreach ($this as $Item) {
      $items[] = $Item;
    }
    return $items;
  }
  /**
   * Объеденяет строковые значения элементов коллекции в одну строку
   * @param $glue символ слияния
   * @param $reverse обратный порядок
   * @return string
   */
  public function toString($glue = '', $reverse = false)
  {
    $lines = array();
    foreach ($this as $item) {
      $lines[] = $item->__toString();
    }
    if ($reverse) $lines = array_reverse($lines);
    return implode($glue, $lines);
  }
  /**
   * Возвращает склееные в строку значения поля
   * @param $glue string склейка, по умолчанию пусто
   * @param $column string название поля
   * @return string
   */
  final public function implode($glue, $column = false)
  {
    if (!$column) {
      $column = $glue;
      $glue = '';
    }
    return implode($glue, $this->get($column));
  }
  /**
   * Возвращает объект коллекции по его алиасу
   * Для интерфейса iAliased
   * @param $column string поле таблицы
   * @param $string string значение алиаса
   * @return aItem
   */
  protected final function iAliased($column, $string)
  {
    $this->isImplements('iAliased');

    if (is_string($column)) {
      $column = $this->Table()->Column($column);
    }

    $string = Database::escape($string);

    $clone = clone $this;

    $clone->SQL()->where("{$column->name()}='{$string}'");

    return $clone->first();
  }
  /**
   * Статистические действия над полями
   * @param $option см. aCollection::STAT_*
   * @param $column имя поля
   * @param $group имя поля группировки
   * @return mixed
   */
  public function statistics($option, $column, $group = '')
  {

    // TODO Сделать поддержку iCacheable

    $column = $this->Table()->Column($column)->name();

    switch ($option) {
      case self::STAT_SUM:
      case self::STAT_MIN:
      case self::STAT_MAX:
      case self::STAT_AVG:
      case self::STAT_COUNT:
        if ($option == self::STAT_SUM) $select = "SUM({$column})";
        if ($option == self::STAT_MIN) $select = "MIN({$column})";
        if ($option == self::STAT_MAX) $select = "MAX({$column})";
        if ($option == self::STAT_AVG) $select = "AVG({$column})";
        if ($option == self::STAT_COUNT) $select = "COUNT({$column})";

        $select = "{$select} stat";

        if ($group) {

          $SQL = clone $this->SQL;

        	$group = $this->Table()->Column($group)->name();
          $select = "{$select}, {$group}";
          $SQL->group($group);

          $res = Database::query($SQL->SELECT($select), true);
          $arr = array();
          while ($row = Database::row($res)) {
            $arr[$row[$group]] = $row['stat'];
          }
          return $arr;
        } else {
        	return Database::fetch(Database::query($this->SQL()->SELECT($select), true), 0);
        }
        break;
    }
  }
  /**
   * Множество значений поля элементов коллекции
   *
   * @param string $column
   * @param boolean $distinct
   * @return array
   */
  public function get($column, $distinct = false)
  {
    // Считаем это чтением свойств элементов коллекции
    $values = array();
    $column = $this->Table->Column($column)->name();
    foreach ($this->rows() as $row) {
      $values[] = $row[$column];
    }
    if ($distinct) {
      $values = array_unique($values);
    }
    return $values;
  }

  /**
   * Сохраняет свойства элементов коллекции
   *
   * @param string $column
   * @param mixed $value
   */
  public function set($column, $value)
  {
    foreach ($this as $item) {
      $item[$column] = $value;
    }
  }
  /**
   * Подгружает данные в объект из базы
   *
   * Данные будут подгружены, только если они еще не были подгружены.
   * При поднятом флаге произойдет принудительная подгрузка данных.
   * При подгрузке данных вызывает метод validate(),
   * который выставляет флаг valid.
   * Возвращается значение флага valid.
   *
   * @uses validate()
   * @param boolean $force подгрузить данные в любом случае
   * @return boolean
   */
  public function load($force = false)
  {
    if (!$this->Storage()->loaded || $force) {

    	$rows = ($this instanceof iCacheable) ? $this->Cache()->rows : null;

    	if ($force || is_null($rows)) {
        $res = Database::query($this->SQL()->SELECT(), true);
        $rows = Database::fetchall($res, $this->Table->PK()->__toString());
        if ($this instanceof iCacheable) $this->Cache()->rows = $rows;
      }

      if (!$rows) $rows = array();

      $itemClass = $this->iItem();

      foreach ($rows as $id=>$row) {
        $item = new $itemClass($id, $row);
        $this->registerExternal($row);
      }

      $this->keys = array_keys($rows);
      $this->Storage()->keys = $this->keys;
      $this->Storage()->loaded = true;
      $this->validate();

    } else {
      if (is_null($this->keys)) $this->keys = $this->Storage()->keys;
    }

    return $this->Storage()->valid;
  }
  /**
   * Делает UPDATE запрос
   *
   * @param $row array
   * @return boolean
   */
  public function update($row)
  {
    $sql = $this->SQL()->UPDATE($this->__setInvoke_r($row));

    if (!$sql) throw new SaveException("SQL запрос на обновление коллекции не был сформирован");

    if ($this instanceof iCacheable) $this->Cache()->drop();

    return (boolean)Database::query($sql);
  }

  /**
   * Делает REPLACE запрос
   *
   * @throws ObjectAddException
   * @param array $row
   * @return boolean
   */
  public function replace($row)
  {
    $sql = $this->SQL()->REPLACE($this->__setInvoke_r($row));

    if (!$sql) throw new ObjectAddException("SQL запрос на добавление данных не был сформирован");

    if ($this instanceof iCacheable) $this->Cache()->drop();

    return (boolean)Database::query($sql);
  }
  /**
   * Делает INSERT запрос. Создает из ассоциативного массива новый элемент коллекции и возвращает его
   *
   * @throws ObjectAddException
   * @param array $row
   * @param boolean $simulate симулировать добавление для валидации данных
   * @return aItem
   */
  public function add($row, $simulate = false)
  {

    $row = $this->__setInvoke_r($row);
    $sql = $this->SQL()->INSERT($row);

    if (!$sql) throw new ObjectAddException("SQL запрос на добавление данных не был сформирован");
    if ($simulate) return (boolean) $sql;
    $res = Database::query($sql);

    if ($this instanceof iCacheable) $this->Cache()->drop();

    $row = array_merge(array($this->Table()->PK()->name() => Database::insert_id()), $row);

    /* Приводим полученный от пользователя $row к виду, как он хранится в БД */
    foreach ($this->Table()->columns() as $Column) {
      if (isset($row[$Column->__toString()])) {
        $row[$Column->__toString()] = $Column->castSet($row[$Column->__toString()]);
      }
    }

    $itemClass = $this->iItem();
    $obj = new $itemClass($row[$this->Table()->PK()->name()]);
    if ($obj instanceof iNode) $obj->Node()->setBottom(); // В дереве надо учесть сортировку}
    if ($obj instanceof iSearchable) $obj->searchIndex();

    return $obj;
  }
  /**
   * Метод ищет запись с условием $get и, если не находит, то создает запись с данными $add
   * @param array $get
   * @param array $add
   * @return aItem
   */
  public function addIf($get, $add)
  {
    $className = $this->name();

    $Collection = new $className($get);

    if ($Item = $Collection->first()) {
      return $Item;
    } else {
      return $Collection->add(array_merge($get, $add));
    }

  }
  /**
   * Подготавливает массив к записи в базу данных
   * @param $row array
   * @return array
   */
  private function __setInvoke_r($row)
  {
    $values = array();

    foreach ($row as $key=>$val) {
      try {
        $Column = $this->Table()->Column($key);
        if ($Column->required() && is_null($Column->defaultValue())) {
          if (!isset($row[$key])) throw new ObjectAddException($Column->__toString(), EXCEPTION_MISSED_MANDATORY_FIELD);
          if ($row[$key] === '')   throw new ObjectAddException($Column->__toString(), EXCEPTION_MISSED_MANDATORY_FIELD);
        }
        $values[$Column->__toString()] = $Column->castSet($val);
      } catch (DatabaseException $e) {
        continue;
      }
    }

    return $values;
  }
  /**
   * Удаляет из базы все записи коллекции
   *
   * @throws DatabaseException
   * @return boolean
   */
  public function delete()
  {
    $this->load();

    if ($this instanceof iFiles) $this->Files()->delete();

    if ($this->length()) {
      Database::query($this->SQL()->DELETE("{$this->Table()->PK()->__toString()} IN ({$this->keys(', ')})"));
    }
    $this->keys = array();

    if ($this instanceof iCacheable) $this->Cache()->drop();

    return true;
  }
  /**
   * Полностью очищает таблицу
   *
   * @throws DatabaseException
   * @return boolean
   */
  final public function truncate()
  {
    Database::query($this->SQL()->TRUNCATE());
    $this->keys = array();

    if ($this instanceof iCacheable) $this->Cache()->drop();

    return true;
  }
  /**
   * Массив данных объекта
   *
   * @param string $column только это поле
   * @return array
   */
  public function rows($column = null)
  {
    $this->load();

    $ret = array();

    foreach ($this as $item) {
      $ret[$item->key()] = $item->row($column);
    }

    return $ret;
  }
  /**
   * Массив данных объекта без прификсов
   *
   * @param string $fx новый префикс
   * @return array
   */
  public function _rows($fx = '')
  {
    $this->load();

    $ret = array();

    foreach ($this as $item) {
      $ret[$item->key()] = $item->_row($fx);
    }

    return $ret;
  }
  /**
   * JSON данных объекта
   *
   * @return array
   */
  public function JSON()
  {
    $this->load();

    $ret = array();

    foreach ($this as $item) {
      $ret[] = $item->row();
    }

    return json_encode(array('name'=>$this->name(), 'rows'=>$ret));
  }
  /**
   * Добавляет элемент(ы) в коллекцию (не создает его в БД)
   *
   * @param $Object aObject
   */
  final public function concat($Object)
  {
    $this->load();
    $this->Storage()->loaded = true;
    if ($Object instanceof aCollection) {
      foreach ($Object as $o) {
        $this->concat($o);
      }
      return null;
    }
    $iItem = $this->iItem();
    if (! $Object instanceof $iItem) throw new ObjectAddException("Слияние объектов '{$Object->name()}' и '{$this->name()}' невозможно");
    $this->keys[] = $Object->key();
  }
  /**
   *
   * @param $Collection aCollection
   * @return unknown_type
   */
  final public function union($Collection)
  {
    // Проверим, совпадают ли структуры SQLExpression

    if (! $this instanceof $Collection) throw new ObjectAddException("Union объектов '{$Collection->name()}' и '{$this->name()}' невозможен");

    foreach ($this->SQL()->Tables()->tables() as $Table) {
      if (!$Collection->SQL()->Tables()->contains($Table)) {
        throw new ObjectAddException("Union объектов '{$Collection->name()}' и '{$this->name()}' невозможен. Отличаются структуры SQLExpression");
      }
    }
	  $this->SQL()->union($Collection->SQL());
  }
  /**
   * Проверяет, содержит ли коллекция объект
   *
   * @param aItem $Item
   * @return boolean
   */
  final public function contains($Item)
  {
    $itemClass = $this->iItem();
    if ($Item instanceof $itemClass) {
      return in_array($Item->key(), $this->keys());
    } else {
      return false;
    }
  }
  /**
   * Возвращает пересечение двух коллекций
   * @param $Collection aCollection
   * @return aCollection
   */
  final public function intersection($Collection)
  {
    if ($this instanceof $Collection) {
      $className = $this->name();
      $keys = array_intersect($this->keys(), $Collection->keys());
      if ($keys) {
        $keys = implode(", ", $keys);
        return new $className("{$this->Table()->PK()->__toString()} IN ({$keys})");
      } else {
        return null;
      }

    } else {
      throw new CastException("Нельзя получить пересечене коллекций разных типов: {$this->name()} и {$Collection->name()}");
    }
  }
  /**
   * Предварительно загружает связанную коллекцию, чтобы ее элементы не загружались по одиночке
   * @param $Collection aCollection
   * @param $fk имя внешнего ключа в этом объекте
   * @return aCollection
   */
  final public function precache($Collection, $fk)
  {
    if ($Collection instanceof aCollection) {
      $ids = $this->get($fk, true);
      if (!$ids) $ids[] = 0;
      $Collection->SQL()->in($Collection->Table()->PK(), $ids);
      $Collection->load();
      return $Collection;
    }
  }
  /**
   * Создает подколлекцию из данной, накладывая дополнительное ограничение
   * @param string $column название поля
   * @param string $value значение поля
   * @return aCollection
   */
  final public function partition($column, $value)
  {
    $this->load();

    $column = $this->Table()->Column($column)->name();

    $keys = array();
    foreach ($this->rows($column) as $key => $val) {
      if ((is_null($value) && is_null($val)) || ($val == $value)) {
        $keys[] = $key;
      }
    }

    // Поменям текущий объект, клонируем его и вернем обратно

    $_keys = $this->keys;
    $this->keys = $keys;

    // Клонируем объект
    $clone = clone $this;

    // Меняем его
    if (is_null($value)) {
      $clone->SQL()->null($column);
    } else {
      $clone->SQL()->equals($column, $value);
    }
    $clone->Storage()->loaded = true;

    // Восстанавливаем исходный
    $this->keys = $_keys;

    return $clone;
  }
}