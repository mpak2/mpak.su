<?php
/**
 * Список исключений для ORM
 * @package ORM
 * @subpackage Exceptions
 * @version 1.0
 * @since 2009-01-16
 * @copyright 2009, 101 Media
 * @author Cellard
 */


/**
 * Отсутствует обязательное свойства объекта
 */
define("EXCEPTION_MISSED_OBJECT_MANDATORY_PROPERTY", 111111);
/**
 * Объект не загружен
 */
define("EXCEPTION_OBJECT_NOT_LOADED", 111122);
/**
 * Пропущено обязательное поле
 */
define("EXCEPTION_MISSED_MANDATORY_FIELD", 111133);
/**
 * Встречено поле, которого нет в структуре данных
 */
define("EXCEPTION_WASTE_FIELD", 111144);
/**
 * Изменение поля только для чтения
 */
define("EXCEPTION_CHANGE_READ_ONLY_FIELD", 111155);

/**
 * Исключение, в котором настраивается вид сообщений об ошибке
 *
 */
class ORM_Exception extends Exception {
  protected $domain = '';
//  public function __construct($message = '', $code = 0)
//  {
//    parent::__construct($message, $code);
//  }
  public function __toString()
  {
    if (function_exists('fb')) fb($this);
//    $echo = $this->getTraceAsString();
//    $echo = str_replace("\n", " ", $echo);
//    $echo = str_replace("#", "\n#", $echo);
//    $echo = get_class($this) . ":\n[{$this->code}]: {$this->message}{$echo}";
    return $this->toHuman() . ($this->getCode() ? ' ' . $this->getMessage() : '');
  }
  protected function toHuman()
  {
    switch ($this->getCode()) {
      case EXCEPTION_MISSED_OBJECT_MANDATORY_PROPERTY:
        return "Отсутствует обязательное свойство объекта";
      case EXCEPTION_OBJECT_NOT_LOADED:
        return "Объект не загружен";
      case EXCEPTION_MISSED_MANDATORY_FIELD:
        return "Пропущено обязательное поле";
      case EXCEPTION_WASTE_FIELD:
        return "Встречено поле, которого нет в структуре данных таблицы";
      case EXCEPTION_CHANGE_READ_ONLY_FIELD:
        return "Изменение поля, которое доступно только для чтения";
      default:
        return $this->domain . ' ' . $this->getMessage();
    }
  }
  public function toArray()
  {
    $json = array();
    $json["domain"] = $this->domain;
    $json["type"] = get_class($this);
    $json["line"] = $this->getLine();
    $json["file"] = $this->getFile();
//    $json["trace"] = $this->getTrace();
    $json["message"] = $this->toHuman();
    $json["property"] = $this->getCode() ? $this->getMessage() : false;
    return $json;
  }
}

/**
 * Ошибка при работе с базой данных
 */
class DatabaseException extends ORM_Exception {
  protected $domain = "Ошибка при работе с базой данных";
}

/**
 * Ошибка при конструировании объекта
 */
class ConstructorException extends ORM_Exception {
  protected $domain = "Ошибка при конструировании объекта";
}
/**
 * Неверная структура данных объекта
 */
class ObjectValidationException extends ORM_Exception {
  protected $domain = "Неверная структура данных объекта";
}
/**
 * Ошибка приведения типов
 */
class CastException extends ORM_Exception {
  protected $domain = "Ошибка приведения типов";
}
/**
 * Ошибка сохранении объекта
 */
class SaveException extends ORM_Exception {
  protected $domain = "Ошибка сохранении объекта";
}
/**
 * Ошибка при сохранении поля объекта
 */
class PropertySetException extends ORM_Exception {
  protected $domain = "Ошибка при сохранении поля объекта";
}
/**
 * Ошибка при получении поля объекта
 */
class PropertyGetException extends ORM_Exception {
  protected $domain = "Ошибка при получении поля объекта";
}
/**
 * Ошибка при добавлении нового объекта
 */
class ObjectAddException extends ORM_Exception {
  protected $domain = "Ошибка при добавлении нового объекта";
}
/**
 * Ошибка вызванная пользователем
 */
class CustomException extends ORM_Exception {
  protected $domain = "";
}