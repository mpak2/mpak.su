<?php

/**
 * Модель "база данных -> объекты" (Object-relational mapping)
 * @package ORM
 * @subpackage Database
 * @version 2.1.0
 * @copyright 2009, 101 Media
 * @author Cellard
 */

/**
 * Интерфейс базы данных
 * @author Cellard
 *
 */
interface iDatabase {
	/**
	 * Schema name
	 * @return string
	 */
	public function schema();
  /**
   * Establishes a connection to a server
   * @param $server
   * @param $database may be empty initially
   * @param $login
   * @param $password
   * @return resource
   */
  public function connect($server, $database, $login, $password = false);

  /**
   * Close connection
   * @return void
   */
  public function disconnect();

  /**
   * Send a MySQL query
   * @param $sql
   * @return resource
   */
  public function query($sql);

  /**
   * Get number of rows in result
   * @param $result
   * @return integer
   */
  public function rows($result);

  /**
   * Fetch a next result row as an associative array
   *
   * @param resource $result
   * @return array
   */
  public function row($result);

  /**
   * Returns the ID generated for an AUTO_INCREMENT column by the previous INSERT query
   *
   * @return integer
   */
  public function insert_id();

  /**
   * Escapes special characters in a string for use in a SQL statement
   * @param $string
   * @return string
   */
  public function escape($string);

  /**
   * Fetch a first row of result as an associative array
   *
   * If $column is defined the result will be the value of this column.
   *
   * @throws DatabaseException
   * @param resource $result
   * @param string $column
   * @return mixed
   */
  public function fetch($result, $column = false);

  /**
   * Fetch a result as an associative multi array
   *
   * If $key is defined the key of returned array is the value of column with such name.
   * Usefull to use with index column.
   *
   * @throws DatabaseException
   * @param resource $result
   * @param string $key
   * @return array
   */
  public function fetchall($result, $key = false);

  /**
   * Returns the text of the error message from previous operation
   * @return string
   */
  public function error();

  /**
   * Move internal result pointer to the very first row
   * @param resource $result
   * @return boolean
   */
  public function reset($result);

  /**
   * Should return SQL query to start transaction
   * return string
   */
  public function transactionStart();

  /**
   * Should return SQL query to commit changes made during transaction
   * return string
   */
  public function transactionCommit();

  /**
   * Should return SQL query to rollback changes made during transaction
   * return string
   */
  public function transactionRollback();
}

/**
 * Статический класс для доступа к базе данных SQL
 *
 * @package ORM
 * @version 1.1
 * @since 2009-01-16
 * @copyright 209, 101 Media
 * @author Cellard
 */
class Database {

  /**
   * Объект базы данных
   *
   * @var iDatabase
   */
  private static $Database;
  private static $queriesExecs = array();
  private static $queriesCache = array();
  private static $durations = array();
  /**
   * Кеш запросов
   *
   * @var array
   */
  private static $cache;
  /**
   * Можно ли кешировать запросы?
   * @var boolean
   */
  private static $cacheable = true;

  /**
   * Устанавливает соединение с сервером
   *
   * @throws DatabaseException
   * @param iDatabase $Decorator
   */
  public static function decorate($Decorator) {
    if ($Decorator instanceof iDatabase)
      self::$Database = $Decorator;
  }
  /**
   *
   * Имя базы данных
   */
  public static function schema()
  {
  	return self::$Database->schema();
  }
  /**
   * Turns on/off queries caching
   * @param $on
   * @return boolean
   */
  public static function cache($on) {
    self::$cacheable = $on;
  }

  public static function statistics($type = false) {
    switch ($type) {
      case "duration" : return round(array_sum(self::$durations), 2);
      case "calls" : return count(self::$queriesExecs);
      case "cached" : return count(self::$queriesCache);
      default:
        return array(
            "duration" => round(array_sum(self::$durations), 2),
            "calls" => count(self::$queriesExecs),
            "call log" => (self::$queriesExecs),
            "cached" => count(self::$queriesCache),
            "cache log" => (self::$queriesCache),
        );
    }
  }

  /**
   * Закрывает соединение
   * @return boolean
   */
  public static function disconnect() {
    return self::$Database->disconnect();
  }

  /**
   * Выполняет запрос
   *
   * @throws DatabaseException
   * @param string $sql
   * @param boolean $cache кешировать?
   * @return resource
   */
  public static function query($sql, $cache = false) {

    if ($cache && self::$cacheable) {
      $id = md5($sql);
      if (isset(self::$cache[$id])) {
        if (self::$cache[$id]) {
          if (!self::$Database->reset(self::$cache[$id])) {
            unset(self::$cache[$id]);
            return self::query($sql, false);
          }
          if (self::$cacheable)
            self::$queriesCache[] = $sql;
          return self::$cache[$id];
        }
      }
    }

    Timer::start('query');
    $s = microtime(true);
    $res = self::$Database->query($sql);
    $s = microtime(true) - $s;
    Timer::stop('query');

    self::$queriesExecs[] = array($sql => round($s, 4));
    self::$durations[] = $s;

    if ($cache && self::$cacheable) {
      self::$cache[$id] = $res;
    }

    return $res;
  }

  /**
   * Выполняет запрос
   *
   * @see query()
   * @throws DatabaseException
   * @param string $sql
   * @param boolean $cache кешировать?
   * @return resource
   */
  public static function q($sql, $cache = false) {
    return self::query($sql, $cache);
  }

  /**
   * Выпоняет запрос и возвращает количество миллисекунд затраченых на его выполнение
   *
   * @param $sql
   * @return double
   */
  public static function profile($sql) {
    $start = microtime(1);
    self::q($sql);
    return microtime(1) - $start;
  }

  /**
   * Возвращает количество записей в рекордсете
   *
   * @param resource $result
   * @return integer
   */
  public static function rows($result) {
    return self::$Database->rows($result);
  }

  /**
   * Возвращает рекордсет в виде ассоциативного массива
   *
   * Если установлена переменная $key, то значение поля с этим именем будет ключом массива. Применять только с первичным ключом
   *
   * @throws DatabaseException
   * @param resource $result
   * @param string $key
   * @return array
   */
  public static function fetchall($result, $key = false) {
    return self::$Database->fetchall($result, $key);
  }

  /**
   * Возвращает первую запись рекордсета в виде ассоциативного массива
   *
   * Если установлена переменная $column, то будет возвращено только это поле
   *
   * @throws DatabaseException
   * @param resource $result
   * @param string $column
   * @return mixed
   */
  public static function fetch($result, $column = false) {
    return self::$Database->fetch($result, $column);
  }

  /**
   * Возвращает следующую запись рекордсета в виде ассоциативного массива
   *
   * @param resource $result
   * @return array
   */
  public static function row($result) {
    return self::$Database->row($result);
  }

  /**
   * Возвращает значение поля с AUTO_INCREMENT сгенерированное последним INSERT запросом
   *
   * @param $sql сперва выполнить запрос
   * @return integer
   */
  public static function insert_id($sql = false) {
    if ($sql)
      self::query($sql);
    return self::$Database->insert_id();
  }

  /**
   * Возвращает значение поля с AUTO_INCREMENT сгенерированное последним INSERT запросом
   *
   * @see insert_id()
   * @param $sql сперва выполнить запрос
   * @return integer
   */
  public static function id($sql = false) {
    return self::insert_id($sql);
  }

  /**
   * Применяет к строке escape функцию в соответствии с правилами сервера
   * @param $string
   * @return string
   */
  public static function escape($string) {
    return self::$Database->escape($string);
  }

  /**
   * Начинает транзакцию
   * @return boolean
   */
  public static function transactionStart() {
    return self::$Database->transactionStart() ? self::query(self::$Database->transactionStart(), false) : false;
  }

  /**
   * Применяет изменения сделанные в течение транзакции
   * @return boolean
   */
  public static function transactionCommit() {
    return self::$Database->transactionCommit() ? self::query(self::$Database->transactionCommit(), false) : false;
  }

  /**
   * Отменяет изменения сделанные в течение транзакции
   * @return boolean
   */
  public static function transactionRollback() {
    return self::$Database->transactionRollback() ? self::query(self::$Database->transactionRollback(), false) : false;
  }

}

class Database_MySQL implements iDatabase {
	private $schema;
  private $connector;

  public function __construct($server, $database, $login, $password = false) {
  	$this->schema = $database;
    $this->connect($server, $database, $login, $password);
  }

  public function connect($server, $database, $login, $password = false) {
    if ($password) {
      $connector = mysql_connect($server, $login, $password);
    } else {
      $connector = mysql_connect($server, $login);
    }
    if (!$connector) {
      throw new DatabaseException("Could not connect: " . $this->error());
    } else {
      $this->connector = $connector;
      if ($database) {
        $this->query("USE `{$database}`");
        if (!mysql_selectdb($database)) {
          throw new DatabaseException("Can't use '{$database}': " . $this->error());
        }
      }
    }
    return $connector;
  }

  public function schema()
  {
  	return $this->schema;
  }

  public function __destruct() {
    fb(Database::statistics(), "Database", 'INFO');
    $this->disconnect();
  }

  public function disconnect() {
    return $this->connector ? @mysql_close($this->connector) : null;
  }

  public function error() {
    return @mysql_error($this->connector);
  }

  public function query($sql) {

    if (!$this->connector) throw new DatabaseException("MySQL connection was not established");
    $res = @mysql_query($sql, $this->connector);

    if ($res) {
      return $res;
    } else {
      switch (@mysql_errno($this->connector)) {
        case '1062':
          throw new DatabaseException("Значения уникальных полей совпадают! ({$sql})");
          break;
        default :
          throw new DatabaseException("Invalid query '{$sql}': " . $this->error());
      }
    }
  }

  public function rows($result) {
    return (integer) mysql_num_rows($result);
  }

  public function fetchall($result, $key = false) {
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
      if ($key) {
        if (isset($row[$key])) {
          $rows[$row[$key]] = $row;
        } else {
          throw new DatabaseException("There is no {$key} column in {$result}");
        }
      } else {
        $rows[] = $row;
      }
    }
    return $rows;
  }

  public function fetch($result, $column = false) {
    $row = mysql_fetch_assoc($result);
    if ($column !== false) {
      if (intval($column) == $column) {
        foreach ($row as $cell) {
          if ($column == 0)
            return $cell;
          $column--;
        }
      } else {
        if (isset($row[$column])) {
          return $row[$column];
        } else {
          throw new DatabaseException("There is no {$key} column in {$result}");
        }
      }
    } else {
      return $row;
    }
  }

  public function row($result) {
    return mysql_fetch_assoc($result);
  }

  public function reset($result) {
    return @mysql_data_seek($result, 0);
  }

  public function insert_id() {
    if (!$this->connector) throw new DatabaseException('MySQL connection lost');
    return mysql_insert_id($this->connector);
  }

  public function escape($string) {
    if (is_array($string)) throw new DatabaseException('mysql_real_escape_string() requires first parameter to be a string, array given');
    if (!$this->connector) throw new DatabaseException('MySQL connection lost');
    return @mysql_real_escape_string($string, $this->connector);
  }

  public function transactionStart() {
    return 'START TRANSACTION';
  }

  public function transactionCommit() {
    return 'COMMIT';
  }

  public function transactionRollback() {
    return 'ROLLBACK';
  }

}

/**
 * Набор таблиц базы данных
 * @author pm
 *
 */
class DatabaseTables {

  private $tables = array();

  /**
   * Добавляет таблицу к набору
   * @param DatabaseTable $Table
   * @param string $FK
   * @return void
   */
  public function concat($Table, $FK) {
    $this->tables[$FK] = $Table;
  }

  /**
   * Возвращает поле таблицы по имени
   * @param string $name
   * @return DatabaseTableColumn
   */
  public function Column($name) {
    foreach ($this->tables as $Table) {
      try {
        $tname = $Table->prefixize($name);
        foreach ($Table->columns() as $Column) {
          if ($Column->__toString() == $tname)
            return $Column;
        }
      } catch (DatabaseException $e) {
        /* Если поле не найдено в этой таблице, оно может оказаться в другой */
      }
    }

    $tables = array();
    foreach ($this->tables as $Table) {
      $tables[] = $Table->name();
    }
    $tables = implode(', ', $tables);
    throw new DatabaseException("Поиск несуществующего поля {$name} в {$tables}");
  }

  /**
   * Массив таблиц
   * @return DatabaseTable[$FK]
   */
  public function tables() {
    return $this->tables;
  }

  /**
   * Содержит ли набор эту таблицу
   * @param $Table DatabaseTable
   * @return boolean
   */
  public function contains($Table) {
    foreach ($this->tables as $_Table) {
      if ($Table->name() == $_Table->name())
        return true;
    }
    return false;
  }

}

/**
 * Описание таблицы
 * @author pm
 *
 */
class DatabaseTable implements iCacheable {

  private $name;
  private $prefix;
  private $columns = array();
  private $foriegntables = array();

  /**
   * (non-PHPdoc)
   * @see api/ORM/iCacheable#Cache()
   * @return CacheExtention
   */
  public function Cache() {
    return new CacheExtention($this, 0);
  }

  /**
   * Существует ли таблица
   * @param string $name
   * @return boolean
   */
  public static function exists($name)
  {
    $query = "SELECT * FROM information_schema.TABLES WHERE TABLE_NAME='{$name}' AND TABLE_SCHEMA='" . Database::schema() . "'";
    $result = Database::query($query);
    return (boolean) Database::rows($result);
  }

  /**
   * Создает дескриптор таблицы
   * @param $name string название таблицы
   * @param $prefix string префикс полей таблицы
   */
  public function __construct($name, $prefix = null) {
    $this->name = $name;

    if ($cache = $this->Cache()->properties) {
      $this->columns = $cache['columns'];
      $this->prefix = $cache['prefix'];
      $this->foriegntables = $cache['foriegntables'];
    } else {
      $this->readProperties($prefix);
    }
  }
  /**
   * Читает и запоминает свои свойства
   * @param $prefix string префикс полей таблицы
   */
  public function readProperties($prefix = null)
  {
    $name = $this->name;

    $query = "SELECT * FROM information_schema.TABLES WHERE TABLE_NAME='{$name}' AND TABLE_SCHEMA='" . Database::schema() . "'";
    $result = Database::query($query);
    if (!Database::rows($result)) {
      throw new DatabaseException("Таблица {$name} не существует", 404);
    }

    $sql = "SHOW FULL COLUMNS FROM {$name}";
    $rows = Database::fetchall(Database::query($sql, true));

    foreach ($rows as $row) {
      $this->columns[] = new DatabaseTableColumn($row['Field'], $row['Type'], $row['Null'] == 'NO' ? true : false, $row['Comment'], $row['Default'], $row['Key'] == 'PRI' ? true : false);
    }

    if (!isset($prefix)) { // Вычисляем префикс самостоятельно
      $prefix = '';
      do {
        $prefixes = array();
        foreach ($rows as $row)
          $prefixes[] = substr($row["Field"], strlen($prefix), 1);
        $prefixes = array_unique($prefixes);
        if (count($prefixes) == 1) {
          $end = false;
          $prefix.= $prefixes[0];
        } else
          $end = true;
      } while (!$end);
      $prefixes = explode('_', $prefix);
      if (count($prefixes) > 1) {
        $prefix = $prefixes[0] . '_';
      }
    }
    $this->prefix = $prefix;
    //находим связанные таблицы
    $columnId = $this->prefix . "id";
    $sql = "SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE CONSTRAINT_SCHEMA='".Database::schema()."' AND REFERENCED_TABLE_NAME = '{$this->name}' AND REFERENCED_COLUMN_NAME = '$columnId'";
    $rows = array();
    $KKUresult = Database::query($sql);
    while ($KKUrow = Database::row($KKUresult)) {
      $row = array();
      $row['table'] = $KKUrow['REFERENCED_TABLE_NAME'];
      $row['foreign_table'] = $KKUrow['TABLE_NAME'];
      $row['column'] = $KKUrow['REFERENCED_COLUMN_NAME'];
      $row['foreign_column'] = $KKUrow['COLUMN_NAME'];
      $showCreateTable = Database::fetch(Database::query("SHOW CREATE TABLE {$row['foreign_table']}"));
      $beforeOn = "FOREIGN KEY (`{$row['foreign_column']}`) REFERENCES `{$row['table']}` (`{$row['column']}`) ";
      if (mb_strpos($showCreateTable['Create Table'], $beforeOn."ON DELETE CASCADE", 0, "UTF8")) {
        $row['delete'] = 'CASCADE';
      } elseif (mb_strpos($showCreateTable['Create Table'], $beforeOn."ON DELETE SET NULL", 0, "UTF8")) {
        $row['delete'] = 'SET NULL';
      } elseif (mb_strpos($showCreateTable['Create Table'], $beforeOn."ON DELETE NO ACTION", 0, "UTF8")) {
        $row['delete'] = 'NO ACTION';
      } else {
        $row['delete'] = 'RESTRICT';
      }
      $this->foriegntables[] = $row;
    }
    $this->Cache()->properties = array('columns' => $this->columns, 'prefix' => $this->prefix, 'foriegntables' => $this->foriegntables);
  }
  /**
   * Драйвер таблицы
   * @return string
   */
  public function engine()
  {
    $sql = "SHOW CREATE TABLE {$this->name}";
    $sql = Database::fetch(Database::query($sql, true), 1);
    $start = strpos($sql, 'ENGINE=');
    if ($start !== false) {
      $finish = strpos($sql, ' ', $start);
      if ($finish !== false) {
        $start+= strlen('ENGINE=');
        return substr($sql, $start, $finish - $start);
      }
    }
    return false;
  }
  /**
   * Метод, возвращающий массив constaint'ов
   * @param
   */
  public function constaraints() {
    return $this->foriegntables;
  }
  /**
   * Префикс полей таблицы
   * @return string
   */
  public function prefix() {
    return $this->prefix;
  }

  /**
   * Возвращает поле таблицы по имени
   * @param string $name
   * @return DatabaseTableColumn
   */
  public function Column($name) {
    $name = $this->prefixize($name);
    foreach ($this->columns as $Column) {
      if ($Column->__toString() == $name)
        return $Column;
    }
    throw new DatabaseException("Обращение к несуществующему полю {$name} таблицы {$this->name}");
  }

  /**
   * Возвращает поля таблицы
   * @return DatabaseTableColumn[]
   */
  public function columns() {
    return $this->columns;
  }

  /**
   * Первичный ключ таблицы
   * @return DatabaseTableColumn
   */
  public function PK() {
    foreach ($this->columns as $Column) {
      if ($Column->PK())
        return $Column;
    }
    throw new DatabaseException("В таблице {$this->name} не найден первичный ключ");
  }

  public function __toString() {
    return (string)$this->name();
  }

  /**
   * Название таблицы
   * @return string
   */
  public function name() {
    return $this->name;
  }

  /**
   * Определяет, содержит ли имя поля префикс и если нет - добавляет его
   *
   * @param string $str
   * @return string
   */
  public function prefixize($str) {
    $n = strpos($str, $this->prefix);
    if ($n === false) { // Префикса нет
      return $this->prefix . $str;
    } else if ($n > 0) { // Префикс есть, но не в начале
      return $this->prefix . $str;
    } else { // Префикс есть
      return $str;
    }
  }

  /**
   * Убирает префикс из имени поля
   * @param $str
   * @return string
   */
  private function unprefixize($str) {
    $str = $this->prefixize($str);
    return substr($str, strlen($this->prefix));
  }

}

class DatabaseTableColumn {

  private $name;
  private $type;
  private $cleantype;
  private $required;
  private $comment;
  private $PK;
  private $default;
  private $html = false;

  public function __construct($name, $type, $required, $comment, $default = null, $PK = false) {
    $this->name = $name;
    $this->type = $type;
    $this->required = (boolean) $required;
    $this->comment = $comment;
    $this->PK = (boolean) $PK;
    $this->default = $default;
    preg_match('/(\w+)(\((.+)\))?( (\w+))?/', $type, $matches);
    $this->cleantype = strtoupper(@$matches[1]);
  }
  public function __toString() {
    return (string)$this->name();
  }

  /**
   * Название поля
   * @param $without string отрезать префикс
   * @return string
   */
  public function name($without = false) {
      if ($without)
      return str_replace($without, '', $this->name);
    else
      return $this->name;
  }

  /**
   * Тип поля
   * @param boolean $clean чистый тип; для varchar(50) это varchar
   * @return string
   */
  public function type($clean = false) {
    return $clean ? $this->cleantype : $this->type;
  }

  /**
   * Обязательное поля
   * @return boolean
   */
  public function required() {
    return $this->required;
  }

  /**
   * Значение по умолчанию
   * @return string
   */
  public function defaultValue() {
    return $this->default;
  }

  /**
   * Примечание поля
   * @return string
   */
  public function comment() {
    return $this->comment;
  }

  /**
   * Первичный ключ
   * @return boolean
   */
  public function PK() {
    return $this->PK;
  }

  /**
   * Резрешено ли хранить в поле HTML код
   *
   * FALSE все теги запрещены
   * TRUE все теги разрешены
   * string список разрешенных тегов (см. strip_tags())
   *
   * @param $allow mixed
   * @return string
   */
  public function html($allow = null) {
    if (is_null($allow))
      return $this->html;
    $this->html = $allow;
  }

  /**
   * Применяет правила при записи значения в поле
   *
   * @param mixed $value
   * @return mixed
   */
  final public function castSet($value)
  {
    if (!$this->required() && (is_null($value) || $value === '')) return null;

    switch ($this->type(true)) {
      case 'DATE':
      case 'TIME':
      case 'DATETIME':
      case 'TIMESTAMP':
        if (!$value && !$this->required()) return null;
        $Date = new Date($value);
        $value = $Date->MySQL($this->type(true));
        break;
      case 'INT':
      case 'INTEGER':
      case 'BIGINT':
      case 'SMALLINT':
      case 'MEDIUMINT':
        if (is_object($value)) if ($value instanceof aItem) $value = $value->key();
      case 'BIT':
      case 'TINYINT':
      case 'BOOL':
      case 'BOOLEAN':
        $value = str_replace(' ', '', $value);
        $value*= 1;
        break;
      case 'FLOAT':
      case 'DOUBLE':
      case 'DECIMAL':
      case 'DEC':
        $value = str_replace(' ', '', $value);
        $value = str_replace(',', '.', $value);
        $value = floatval($value);
        /* из-за локали на сервере floatval может ставить разделитель не точку, а запятую */
        $value = str_replace(',', '.', $value);
        break;
      case 'BLOB': case 'TINYBLOB': case 'MEDIUMBLOB': case 'LONGBLOB':
      case 'TEXT': case 'TINYTEXT': case 'MEDIUMTEXT': case 'LONGTEXT':
      case 'VARCHAR': case 'CHAR':
        if (is_object($value)) if ($value instanceof aItem) $value = $value->_toString();
        $value = trim($value);
        break;
    }

    return $value;
  }

  /**
   * Применяет правила при чтении значения из поля
   *
   * @param mixed $value
   * @return mixed
   */
  final public function castGet($value)
  {
    if (is_null($value)) return null;

    switch ($this->type(true)) {
      case 'DATE':
      case 'TIME':
      case 'DATETIME':
      case 'TIMESTAMP':
        $value = new Date($value);
        break;
      case 'BOOL':
      case 'BOOLEAN':
        $value = !!$value;
        break;
      case 'TINYINT':
        if ($this->type(false) == 'TINYINT(1)') $value = !!$value;
        else $value*= 1;
        break;
      case 'BIT':
      case 'SMALLINT':
      case 'MEDIUMINT':
      case 'INT':
      case 'INTEGER':
      case 'BIGINT':
        $value*= 1;
        break;
      case 'FLOAT':
      case 'DOUBLE':
      case 'DECIMAL':
      case 'DEC':
        break;
      case 'BLOB': case 'TINYBLOB': case 'MEDIUMBLOB': case 'LONGBLOB':
      case 'TEXT': case 'TINYTEXT': case 'MEDIUMTEXT': case 'LONGTEXT':
      case 'VARCHAR': case 'CHAR':

        if (!$this->html()) {
          $value = strip_tags($value);
          $value = htmlspecialchars($value);
        } else {
          $value = $this->html() === true ? $value : strip_tags($value, $this->html());
          $translation_table = array(
            "&laquo;"=>"«", "&raquo;"=>"»",
            "&mdash;"=>"—"
          );
          $value = strtr($value, $translation_table);
        }
        break;
    }
    return $value;
  }

}

/**
 * Конструктор запросов
 *
 * FIX Сохранение ссылки на $Object приводит к утечкам памяти
 *
 */
class SQLExpression {

  /**
   * Объект
   * @var aObject
   */
  public $Object = null;
  /**
   * Основная таблица
   * @var DatabaseTable
   */
  private $Table;
  /**
   * Внешние таблицы
   * @var DatabaseTables
   */
  private $Tables;
  private $expressions = array();
  private $where = array();
  private $unions = array();
  private $order = '';
  private $group = '';
  private $having = '';
  private $limit = '';

  /**
   * Создает конструктор запросов для таблицы
   * @param DatabaseTable $Table таблица
   */
  public function __construct($Table) {
    $this->Table = $Table;
    $this->Tables = new DatabaseTables();
  }
  /**
   * Очищает запрос
   * @return SQLExpression
   */
  public function clear()
  {
    $this->where = array();
    $this->expressions = array();
    $this->unions = array();
    $this->order = '';
    $this->group = '';
    $this->having = '';
    $this->limit = '';
    $this->reload();
    return $this;
  }
  /**
   * Перезагружает родителя не теряя ссылку на него
   * @return void
   */
  private function reload() {
    if (isset($this->Object)) {
      $Object = $this->Object;
      $Object->reload();
      $this->Object = $Object;
    }
  }
  /**
   * Превращает массив в условие отбора WHERE
   *
   * column => value		column='value'
   * column => array(value[, value])	column IN (value[, value])
   * !column => value		column != 'value'
   * >=column => value	etc
   * <=column => value
   * >column => value
   * <column => value
   *
   * @param array $row
   * @return string
   */
  public function array2SQL($row)
  {
    $sql = array();
    foreach ($row as $key=>$val) {
      if (strpos($key, '!') === 0) {
        if (is_array($val)) {
          $mod = 'NOT IN';
        } elseif (is_null($val)) {
          $mod = 'IS NOT NULL';
        } else {
          $mod = '!=';
        }
        $column = substr($key, 1);
      } elseif (strpos($key, '>') === 0) {
        $mod = '>';
        $column = substr($key, 1);
      } elseif (strpos($key, '<') === 0) {
        $mod = '<';
        $column = substr($key, 1);
      } elseif (strpos($key, '>=') === 0) {
        $mod = '>=';
        $column = substr($key, 2);
      } elseif (strpos($key, '<=') === 0) {
        $mod = '<=';
        $column = substr($key, 2);
      } else {
        if (is_array($val)) {
          $mod = 'IN';
        } elseif (is_null($val)) {
          $mod = 'IS NULL';
        } else {
          $mod = '=';
        }
        $column = $key;
      }
    	try {
    		$column = $this->Table->Column($column)->name();
    	} catch (DatabaseException $e) {
    		continue;
    	}
      if (is_null($val)) {
        $sql[] = "{$column} {$mod}";
      } elseif (is_array($val)) {
      	$values = array();
	      foreach ($val as $i => $v) {
	        if (is_string($v)) {
	          $values[$i] = "'" . $this->escape($v) . "'";
	        } else {
	          $values[$i] = $this->escape($v);
	        }
	      }
	      $values = implode(', ', $values);
	      $sql[] = "{$column} {$mod} ({$values})";
      } else {
        if (!is_integer($val)) {
          $val = $this->escape($val);
        }
        $sql[] = "{$column}{$mod}'{$val}'";
      }
    }
    unset($this->Object);
    return implode(' AND ', $sql);
  }
  /**
   * Находит данное поле среди полей запроса и возвращает его название
   *
   * Метод позволяет убедиться, что поле действительно присутствует в запросе
   *
   * @param $columnName string
   * @return string
   */
  private function findColumn($columnName) {

    if (in_array($columnName, array_keys($this->expressions)))
      return $columnName;
    try { // Ищем поле в основной таблице
      return $this->Table()->Column($columnName)->name();
    } catch (DatabaseException $e) { // В основной таблице этого поля нет
      return $this->Tables()->Column($columnName)->name();
    }
  }

  /**
   * Сохраняет ссылку на объект и возвращает себя
   * @param $Object aObject
   * @return SQLExpression
   */
  public function __instance($Object) {
    $this->Object = $Object;
    return $this;
  }

  /**
   * Основная таблица запроса
   * @return DatabaseTable
   */
  final public function Table() {
    return $this->Table;
  }

  /**
   * Внешние таблицы в запросе
   * @return DatabaseTables
   */
  final public function Tables() {
    return $this->Tables;
  }

  /**
   * Поле первичного ключа основной таблицы
   * @return DatabaseTableColumn
   */
  final public function PK() {
    unset($this->Object);
    return $this->Table()->PK();
  }

  /**
   * Запрос INSERT
   * @param array $row подготовленные данные
   * @return string
   */
  final public function INSERT($row) {
    unset($this->Object);
    return "INSERT {$this->Table()->__toString()} SET {$this->SET($row)}";
  }

  /**
   * Запрос TRUNCATE
   * @return string
   */
  public function TRUNCATE() {
    unset($this->Object);
    return "TRUNCATE {$this->Table()->__toString()}";
  }

  /**
   * Запрос UPDATE
   * @param $row подготовленные данные
   * @return string
   */
  public function UPDATE($row) {
    unset($this->Object);
    $where = $this->where ? '(' . implode(") AND (", $this->where) . ')' : '';

    $sql = "UPDATE {$this->Table()->__toString()} SET {$this->SET($row)}";
    if ($where)
      $sql.= " WHERE {$where}";
    return $sql;
  }

  /**
   * Запрос REPLACE
   * @param array $row подготовленные данные
   * @return string
   */
  final public function REPLACE($row) {
    unset($this->Object);
    return "REPLACE {$this->Table()->__toString()} SET {$this->SET($row)}";
  }

  final private function SET($row) {

    $set = array();

    foreach ($row as $key => $val) {
      if (is_null($val)) {
        $set[] = "{$key}=NULL";
      } else {
        $set[] = "{$key}='{$this->escape($val)}'";
      }
    }

    return implode(", ", $set);
  }

  /**
   * Запрос COUNT
   * @return string
   */
  final public function COUNT() {
    unset($this->Object);
    return $this->SELECT("COUNT({$this->PK()->__toString()})");
  }

  /**
   * Запрос SELECT
   * @param array $select имя поля или массив имен полей
   * @param boolean $distinct
   * @return string
   */
  final public function SELECT($select = array(), $distinct = false) {
    unset($this->Object);
    if (!is_array($select))
      $select = array($select);
    if (!$select) {
      $select = array('*');
      foreach ($this->expressions as $as => $expression) {
        $select[] = "({$expression}) AS {$this->escape($as)}";
      }
    }
    $select = implode(', ', $select);

    $join = array();
    $from = array();
    $where = $this->where;
    foreach ($this->Tables()->tables() as $FK => $Foreign) {
    	try {
        $Column = $this->Table->Column($FK);
	    	if ($Column->required()) {
	        /* NOT NULL */
	        $from[] = $Foreign->name();
	        $where[] = "{$Column->__toString()}={$Foreign->PK()->__toString()}";
	      } else {
	        /* NULL */
	        $join[] = array($Foreign->name(), "{$Column->__toString()}={$Foreign->PK()->__toString()}");
	      }
    	} catch (DatabaseException $e) {
    		$Column = $this->Tables()->Column($FK);
    		$join[] = array($Foreign->name(), "{$this->Table()->PK()->__toString()}={$Column->__toString()}");
    	}
    }

    $from = implode(", ", $from);
    $where = $where ? '(' . implode(") AND (", $where) . ')' : '';

    $distinct = $distinct ? ' DISTINCT' : '';
    $sql = "SELECT{$distinct} {$select} FROM {$this->Table()->__toString()}";

    foreach ($join as $j) {
      $sql.= " LEFT JOIN {$j[0]} ON ({$j[1]})";
    }
    if ($from)
      $sql.= ", {$from}";
    if ($where)
      $sql.= " WHERE {$where}";
    if ($this->group)
      $sql.= " GROUP BY {$this->group}";
    if ($this->having)
      $sql.= " HAVING {$this->having}";
    if ($this->order)
      $sql.= " ORDER BY {$this->order}";
    if ($this->limit)
      $sql.= " LIMIT {$this->limit}";

    if ($this->unions)
      $sql = "({$sql})";
    foreach ($this->unions as $exp) {
      $sql .= " UNION (" . $exp->SELECT($select, $distinct) . ")";
    }

    return $sql;
  }

  /**
   * Запрос DELETE
   * @return string
   */
  final public function DELETE($where = false) {
    unset($this->Object);
    $where = $where ? $where : ($this->where ? '(' . implode(") AND (", $this->where) . ')' : '');

    $sql = "DELETE FROM {$this->Table()->__toString()}";
    if ($where)
      $sql.= " WHERE {$where}";
    return $sql;
  }

  /**
   * Применяет к строке escape функцию в соответствии с правилами сервера
   * @param $value string
   * @return string
   */
  final public function escape($value) {
    return Database::escape($value);
  }

  /**
   * Добавляет внешнюю таблицу к запросу
   * @param $Table DatabaseTable
   * @param $FK string
   * @return SQLExpression
   */
  final public function append($Table, $FK) {
    $this->Tables->concat($Table, is_object($FK) ? $FK->__toString() : $FK);
    $this->reload();
    return $this;
  }

  /**
   * Добавляет условия отбора к запросу (без оператора WHERE)
   *
   * После вызова метода объект будет вынужден перезагрузить данные
   *
   * @param string $sql
   * @return SQLExpression
   */
  final public function where($sql) {
    if (!in_array($sql, $this->where)) {
      $this->where[] = $sql;
      $this->where = array_unique($this->where);
      foreach ($this->where as $i => $v) {
        if (!$v)
          unset($this->where[$i]);
      }
      $this->reload();
    }
    return $this;
  }

  /**
   * Ограничение =
   * @param string $column имя поля
   * @param mixed $value значение
   * @param boolean $not NOT
   * @return SQLExpression
   */
  final public function equals($column, $value, $not = false) {
    $column = $this->findColumn(is_object($column) ? $column->__toString() : $column);
    $eq = $not ? '!=' : '=';
    if (is_null($value)) {
      return $this->null($column, $not);
    } elseif (is_numeric($value)) {
      return $this->where("{$column}{$eq}{$this->escape($value)}");
    } else {
      return $this->where("{$column}{$eq}'{$this->escape($value)}'");
    }
  }

  /**
   * Ограничение !=
   * @param string $column имя поля
   * @param mixed $value значение
   * @return SQLExpression
   */
  final public function not_equals($column, $value) {
    return $this->equals($column, $value, true);
  }

  /**
   * Ограничение LIKE
   * @param string $column имя поля
   * @param mixed $value значение
   * @return SQLExpression
   */
  final public function like($column, $value) {
    $column = $this->findColumn(is_object($column) ? $column->__toString() : $column);
    return $this->where("{$column} LIKE '{$this->escape($value)}'");
  }

  /**
   * Ограничение [NOT] NULL
   * @param string $column имя поля
   * @param boolean $not NOT
   * @return SQLExpression
   */
  final public function null($column, $not = false) {
    $column = $this->findColumn(is_object($column) ? $column->__toString() : $column);
    $is = $not ? 'IS NOT' : 'IS';
    return $this->where("{$column} {$is} NULL");
  }

  /**
   * Ограничение NOT NULL
   * @param string $column имя поля
   * @return SQLExpression
   */
  final public function not_null($column) {
    return $this->null($column, true);
  }

  /**
   * Ограничение [NOT] IN ()
   * @param string $column имя поля
   * @param mixed $values массив значений или вложенный запрос
   * @param boolean $not NOT
   * @return SQLExpression
   */
  final public function in($column, $values, $not = false) {
    if (!$values) return $this;

    $column = $this->findColumn(is_object($column) ? $column->__toString() : $column);

    if (is_array($values)) {
      foreach ($values as $i => $val) {
        if (is_string($val)) {
          $values[$i] = "'" . $this->escape($val) . "'";
        } else {
          $values[$i] = $this->escape($val);
        }
      }
      $values = implode(', ', $values);
    }

    $in = $not ? 'NOT IN' : 'IN';
    return $this->where("{$column} {$in} ({$values})");
  }

  /**
   * Ограничение NOT IN ()
   * @param string $column имя поля
   * @param mixed $values массив значений или вложенный запрос
   * @return SQLExpression
   */
  final public function not_in($column, $values) {
    return $this->in($column, $values, true);
  }

  /**
   * Добавляет ограничения к запросу (без оператора LIMIT)
   *
   * @param string $sql
   * @return SQLExpression
   */
  final public function limit($sql) {
    $this->limit = $sql;
    $this->reload();
    return $this;
  }

  /**
   * Добавляет сортировку к запросу (без оператора ORDER BY)
   *
   * @param string $sql
   * @return SQLExpression
   */
  final public function order($sql) {
    $this->order = Database::escape($sql);
    $this->reload();
    return $this;
  }

  /**
   * Добавляет группировку к запросу (без оператора GROUP BY)
   *
   * @param string $sql
   * @return SQLExpression
   */
  final public function group($sql) {
    $this->group = Database::escape($sql);
    $this->reload();
    return $this;
  }

  /**
   * Добавляет ограничение к запросу (без оператора HAVING)
   *
   * @param string $sql
   * @return SQLExpression
   */
  final public function having($sql) {
    $this->having = ($sql);
    $this->reload();
    return $this;
  }

  /**
   * Добавляет к этому запросу SQLExpression (для UNION)
   *
   * @param SQLExpression $exp
   * @return SQLExpression
   */
  final public function union($exp) {
    $this->unions[] = $exp;
    $this->reload();
    return $this;
  }

  /**
   * Добавляет выражение в SELECT
   *
   * @param string $sql выражение
   * @param string $as псевдоним
   * @return SQLExpression
   */
  final public function expression($sql, $as) {
    $this->expressions[$as] = $sql;
    $this->reload();
    return $this;
  }

  /**
   * Дополнительные поля
   * @return array
   */
  final public function expressions() {
    unset($this->Object);
    return $this->expressions;
  }

}