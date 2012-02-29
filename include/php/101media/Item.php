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
 * Класс, представляющий объект, основанный на записи из таблицы базы данных
 *
 * Предоставляет магические методы __get и __set с защитой от изменения
 * идентификатора и приведением типов данных (см. _GetInvoke и _SetInvoke),
 * метод удаления самого себя из базы данных;
 * класс минимизирует обращения к базе данных:
 * так, запись измененного состояния производится при разрушении объекта.
 *
 * Реализует интерфейсы:
 * Iterator для перебора полей объекта
 * ArrayAccess для доступа к полям объекта как к полям массива
 * Countable для подсчета количества полей объекта
 */
abstract class aItem extends aObject implements ArrayAccess {

  /**
   * Уникальный идентификатор объекта
   *
   * @var integer
   */
  protected $_id = 0;

  /**
   * Объект определяется идентификатором или самим собой.
   * Можно передать массив с данными объекта.
   *
   * @param integer $id или объект такого же типа
   * @param array $row
   */
  public function __construct($id = 0, array $row = array()) {
    parent::__construct();

    if ($id instanceof $this) {
      /* Создание объекта из самого себя - type hinting */
      $this->SQL = $id->SQL();
      $id = $id->key();
    } else {
      if (is_array($id)) {
        $mask = $id;
        $id = Depot::search($this->name(), $mask);
        if (!$id) {
          $Collection = $this->iCollection();
          $Collection = new $Collection($mask);
          $Item = $Collection->first();
          if ($Item) $id = $Item->key();
        }
      }
      $id = (integer) $id;
      $this->SQL()->where("{$this->Table()->PK()->__toString()}={$id}");
    }

    $this->_id = $id;

    if ($id && is_array($row) && !$this->Storage()->loaded) {
      if (count($row)) {
        $row[$this->Table()->PK()->name()] = $id;
        //$row = $this->purify($row);
        $this->Storage()->row = $row;
        $this->Storage()->wet = $this->md5($this->purify($row));
        $this->Storage()->loaded = true;
        $this->validate();
      }
    }

    if ($this instanceof iReconstructor)
      $this->__reconstructor();
  }
  /**
   * Сравнивает два объекта
   * @param aItem $Object
   * @return boolean
   */
  public function is($Object) {
    if (is_object($Object)) if ($this instanceof $Object) if ($this->key() == $Object->key()) return true;
    return false;
  }
  /**
   * Проверяет, возможно ли совершение действия над Item, без нарушения целостности базы данных
   * @param string $action - возможные действия (delete)
   * @return boolean
   */
  public function can($action) {
    switch ($action) {
      case 'delete':
        foreach ($this->Table()->constaraints() as $foreignTab) {
          if ($foreignTab['delete'] == 'RESTRICT') {
            $sql = "SELECT * FROM {$foreignTab['foreign_table']} WHERE {$foreignTab['foreign_column']} = {$this->key()}";
            if (Database::rows(Database::query($sql)) != 0)
              return false;
          }
        }
        return true;
        break;
      default:
        return true;
        break;
    }
  }

  /**
   * Строковое значение объекта в виде ClassName(key)
   * @return string
   */
  public function __toString() {
    return "{$this->name()}({$this->key()})";
  }

  /**
   * Очистить массив от чужих данных
   * @param $row
   * @return array
   */
  protected function purify($row) {
    $data = array();
    if ($row) {
      foreach ($row as $key => $val) {
        try {
          $Column = $this->Table->Column($key);
          $data[$Column->__toString()] = $val;
        } catch (Exception $e) {

        }
      }
    }
    return $data;
  }

  /**
   * Хеш сумма массива
   * @param $row array
   * @return string
   */
  protected function md5($row) {
    /*
     * Вместо serialize используется print_r, потому что при сериализвации возможны приколы с типами данных
     */
    return md5(print_r($row, true));
  }

  /**
   * Возвращает имя парного класса типа aCollection
   * @return string
   */
  abstract public function iCollection();

  /**
   * Удаляет ссылки на внешние объекты
   */
  public function __destruct() {
    try {
      $this->save();
      if ($this->changed()) {
        echo "<h2>При разрушении объекта {$this->name()}({$this->key()}) было обнаружено, что он не был сохранен</h2>
        <p>Потрудитесь найти место в коде, где вы забыли вызвать метод <em>{$this->name()}::save()</em> и исправьте свою ошибку</p>";
        echo "<pre>Backtrace:\n\n" . print_r(debug_backtrace(false), true) . "</pre>";
        die();
      }
    } catch (Exception $e) {
      //echo "<pre>" . print_r($e, true) . "</pre>";
      die();
    }
  }

  /**
   * Приведение объекта к коллекции
   * @return aCollection
   */
  public function toCollection() {
    $collectionClass = $this->iCollection();
    $collection = new $collectionClass("{$this->Table()->PK()->__toString()}={$this->key()}");
    return $collection;
  }

  /**
   * Существует ли поле объекта
   * @return boolean
   */
  public function offsetExists($offset) {
    try {
      $Column = $this->Table->Column($offset);
      $this->load();
      $row = $this->Storage()->row;
      return isset($row[$Column->__toString()]);
    } catch (DatabaseException $e) {
      return false;
    }
  }

  /**
   * Возвращает значение поля объекта
   * @return mixed
   */
  public function offsetGet($offset) {
    try {

      // Ищем поле в основной таблице
      $Column = $this->Table->Column($offset);
      if ($Column->PK())
        return $this->key();
    } catch (DatabaseException $e) {
      // В основной таблице этого поля нет
      $Column = $this->SQL()->Tables()->Column($offset);
    }

    // Если объект кривой - плохо
    if (!$this->load())
      return null;

    $row = $this->Storage()->row;

    return $Column->castGet($row[$Column->__toString()]);
  }

  /**
   * Новое значение поля объекта (нельзя изменить значение индексного поля)
   * Возвращает FALSE в случае ошибки
   * @return mixed
   */
  public function offsetSet($offset, $value) {
    try {
      // Ищем поле в основной таблице
      $Column = $this->Table()->Column($offset);

      if ($Column->PK())
        throw new PropertySetException("Попытка изменить первичный индекс");
      if ($Column->required() && (!isset($value) || $value === ''))
        throw new PropertySetException($offset, EXCEPTION_MISSED_MANDATORY_FIELD);

      // TODO реализовать бы readonly поля
    } catch (DatabaseException $e) {
      // Если поле принадлежит внешней таблице - его менять нельзя
      return false;
    }

    $this->load();

    $row = $this->Storage()->row;
    $row[$Column->__toString()] = $Column->castSet($value);
    $this->Storage()->row = $row;

    return $row[$Column->__toString()];
  }

  /**
   * Устанавливает значение поля объекта в NULL
   */
  public function offsetUnset($offset) {
    $this[$offset] = null;
  }

  /**
   * Возвращает значение поля объекта
   * @return mixed
   */
  public function __get($column) {
    return $this[$column];
  }

  /**
   * Новое значение поля объекта (нельзя изменить значение индексного поля)
   * Возвращает FALSE в случае ошибки
   * @return mixed
   */
  public function __set($column, $value) {
    return $this[$column] = $value;
  }

  /**
   * Устанавливает значение поля объекта в NULL
   */
  public function __unset($column) {
    $this[$column] = null;
  }

  /**
   * Существует ли поле объекта
   * @return boolean
   */
  public function __isset($column) {
    return isset($this[$column]);
  }

  /**
   * Сохраняет данные объекта в базу
   *
   * Возвращает TRUE или FALSE в зависимости от успехов выполнения.
   *
   * @uses validate()
   * @uses changed()
   * @param boolean $force принудительное сохранение неизмененного объекта
   * @return boolean
   */
  public function save($force = false) {
    if ($this->changed() || $force) {

      if ($this->_id <= 0)
        return false; // Объект был создан только для скелета

        if ($this instanceof iTouching)
        $this[$this->iTouch()] = time();

      $row = $this->Storage()->row;

      $save = array();
      if ($row) {
        foreach ($row as $key => $val) {
          try {
            $Column = $this->Table->Column($key);
            if (!$Column->PK()) $save[$Column->__toString()] = $Column->castSet($val);
          } catch (Exception $e) {

          }
        }
      }

      $this->validate();

      Database::query($this->SQL()->UPDATE($save));

      $this->Storage()->wet = $this->md5($this->purify($this->Storage()->row));

      if ($this->key() && $this instanceof iCacheable) $this->Cache()->drop();
      if ($this->key() && $this instanceof iSearchable) $this->searchIndex();

      return true;
    }
    return false;
  }

  /**
   * Подгружает данные в объект из базы
   *
   * Данные будут подгружены, только если они еще не были подгружены.
   * При поднятом флаге произойдет принудительная подгрузка данных, в этом случае возможна потеря несохраненных данных.
   * При подгрузке данных вызывает метод validate(),
   * который выставляет флаг valid.
   * Возвращается значение флага valid.
   *
   * @throws DatabaseException
   * @see $valid
   * @uses validate()
   * @uses loadQuery()
   * @param boolean $force подгрузить данные в любом случае
   * @return boolean
   */
  public function load($force = false) {
    // Если данные не загружены или требуется перезагрузка

    if (!$this->Storage()->loaded || $force) {
      if ($this->_id <= 0)
        return false; // Объект был создан только для скелета

      $row = ($this instanceof iCacheable) ? $this->Cache()->row : null;

      if ($force || is_null($row)) {
        $row = Database::fetch(Database::query($this->SQL()->SELECT(), !$force));
        if ($this instanceof iCacheable) $this->Cache()->row = $row;
      }
      $this->Storage()->loaded = true;

      // После того, как данные прочитаны - копируем их в _row
      $this->Storage()->row = $row;
      $this->Storage()->wet = $this->md5($this->purify($row));
      $this->validate();

      $this->registerExternal($row);
    }
    return $this->Storage()->valid;
  }
  /**
   * Выгружает объект из памяти
   */
  public function unload()
  {
    $this->Storage()->cleanup();
  }
  /**
   * Проверяет данные объекта на соответствие правилам
   *
   * Правильная ли структура
   *
   * @return boolean
   */
  final protected function validate() {
    $valid = true;

    $row = $this->purify($this->Storage()->row);

    if ($row) {
      if (count($row) == 0) { // Если массива нет - объект не валидный
        throw new ObjectValidationException("Попытка создания объекта {$this->name()} без данных");
        $valid = false;
      } else { // Сходится ли структура массива
        $valid = parent::validate($row);
      }
      if ($this->_id < 0) { // Если нет идентификатора - объект не валидный
        throw new ObjectValidationException("Попытка создания объекта {$this->name()} с отрицательным первичным индексом");
        $valid = false;
      }
    } else {
      throw new ObjectValidationException("Попытка создания объекта {$this->name()} без данных");
      $valid = false;
    }

    $this->Storage()->valid = $valid;
    return $valid;
  }

  /**
   * Проверяет, изменился ли объект
   *
   * @return boolean
   */
  final public function changed() {
    if (!$this->identificator())
      return false;
    if (!$this->Storage()->row)
      return false;
    $row = $this->purify($this->Storage()->row);
    if ($this->md5($row) == $this->Storage()->wet) {
      return false;
    } else {
      return true;
    }
  }

  /**
   * Удаляет текущий объект из базы данных
   *
   * @throws DatabaseException
   * @return boolean
   */
  public function delete() {

    if ($this->key() && $this instanceof iCacheable) $this->Cache()->drop();
    if ($this->key() && $this instanceof iFile) $this->File()->delete();
    if ($this->key() && $this instanceof iSearchable) $this->searchIndexDelete();

    Database::query($this->SQL()->DELETE("{$this->Table()->PK()->__toString()}={$this->key()}"));
    unset($this->Storage()->row);
    unset($this->Storage()->valid);
    $this->_id = 0;
    return true;
  }

  /**
   * Возвращает массив с данными объекта
   *
   * @param string $column только это поле
   * @return array
   */
  public function row($column = null) {
    $this->load();
    $row = $this->Storage()->row;
    foreach ($this->Table()->columns() as $Column) {
      $row[$Column->__toString()] = $this->key() ? $Column->castGet($row[$Column->__toString()]) : null;
    }

    if (!isset($column)) {
      if ($this->key()) {
        $row[$this->Table()->prefix().'toString'] = $this->__toString();
      }
      return $row;
    } else {
      try {
        $column = $this->Table()->Column($column)->name();
      } catch (DatabaseException $e) {}
      return $row[$column];
    }
  }

  /**
   * Возвращает массив с данными объекта без префиксов
   *
   * @param string $fx новый префикс
   * @return array
   */
  public function _row($fx = '') {
    $row = array();
    $fl = strlen($this->Table()->prefix());
    foreach ($this->row() as $key=>$val) {
      $row[$fx . substr($key, $fl)] = $val;
    }
    return $row;
  }

  /**
   * Возвращает массив с данными объекта для вывода в JSON
   * @return array
   */
  public function json() {
    $row = $this->row();
    foreach ($row as $key => $val) {
      if ($val instanceof Date) {
        $row[$key] = $val->UTC();
      }
    }
    return $row;
  }
  /**
   * Возвращает массив с данными объекта без префиксов для вывода в JSON
   * @return array
   */
  public function _json() {
    $row = $this->_row();
    foreach ($row as $key => $val) {
      if ($val instanceof Date) {
        $row[$key] = $val->UTC();
      }
    }
    return $row;
  }

  /**
   * Устанавливает массив с данными объекта
   *
   * @param array $row массив с новыми данными
   * @param boolean $save сохранить объект сразу
   */
  public function merge($row, $save = false) {
    if ($row) {
      if (is_array($row)) {
        $this->load();
        foreach ($row as $name => $value) {
          try {
            $Column = $this->Table()->Column($name);
            if (!$Column->PK()) $this[$Column->__toString()] = $value;
          } catch (DatabaseException $e) {
            /* Поля из другой таблицы просто пропустим */
            continue;
          }
        }
        if ($save)
          $this->save();
      }
      if ($this instanceof iCacheable)
        $this->Cache()->drop();
    }
  }

  /**
   * Уникальный идентификатор объекта
   *
   * @return integer
   */
  final public function key() {
    return $this->_id;
  }

  /**
   * Уникальный идентификатор объекта
   *
   * @return integer
   */
  final public function identificator() {
    return $this->_id;
  }

  /**
   * Проверяет, существует ли индекс в таблице
   *
   * @throws DatabaseException
   * @return boolean
   */
  final public function exists() {
    try {
      $this->load();
    } catch (Exception $e) {

    }
    return (boolean) $this->Storage()->row;
  }

}

