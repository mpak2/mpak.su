<?php
/**
 * Класс хранения и обработки конфигурации.
 * Умеет хранить конфигурацию в разных форматах. Формат определяется либо "насильно" либо автоматически
 * исходя из расширения файла настроек. К примеру, файлы с расширениями json и php автоматически 
 * определяются как конфигурационные файлы в формате json и php, и для них выбирается соответствующие
 * функции сохранения и загрузки.<br>
 * Если функция загрузки или сохранения не найдена - будет выведена ошибка и на этом все закончится.<br>
 * Также есть специальный формат - называется "tmp" - это формат хранящий настройки "в памяти", т.е.
 * никуда их не сохраняющий. 
 * @copyright ArtFactor
 * @author mihanentalpo@yandex.ru
 */
class AF_Config
{
	/**
	 * Переменная хранящая флаг автосохранения - если true, то после каждого изменения настроек происходит сохранение.
	 * Если нет - можно в любой момент вызвать AF_Config::saveOptions чтобы сохранить настройки
	 * @var unknown_type
	 */
	public $autosave=false;
	
	
	/**
	 * Переменная хранит флаг загруженности. Если данные хотя-бы раз успешно загрузились - равна true	 
	 * @var boolean
	 */
	public $optionsLoaded=false;
	
	/**
	 * Переменная для временного хранения данных - если формат данных выбран "tmp" то в качестве хранилища
	 * будет использована именно эта переменная.
	 */
	public $tmp_storage=array();
	
	/**
	 * Переменная храняющая формат хранения данных - может содержать "json","php","xml" и так далее.
	 * Для каждого формата данных нужна своя функция чтения и записи данных
	 * называются они соответственно json_load(), json_save(), php_load() , php_save() и так далее. 
	 * @var string
	 */
	public $format;
	
	/**
	 * Путь и имя файла, хранящего настройки
	 * @var string
	 */
	public $config_file;
	/**
	 * Констурктор, принимает имя конфигурационного файла
	 * @param text $r_path путь к корню сайта
	 * @param text $c_path путь к папке с конфигурационными файлами
	 */
	
	// массив настроек
	public $options = array();
		
	/**
	 * Конструктор настроечного класса
	 * В качестве параметра передается полный путь к конфигфайлу.<br>	
	 * @param string $c_file имя конфигурационного файла
	 * @param string $format указание формата конфигурационного файла. Если не указано - определяется по расширению.
	 * @param boolean $autosave нужно ли автосохранение?
	 */
	public function __construct($c_file='',$format='auto',$autosave=FALSE)
	{		
		$this->autosave=$autosave;
		
		$this->setConfigFile($c_file,$format);
		$this->loadOptions();
	}
	
	/**
	 * Деструктор. Пытается сохранить настройки (если надо)
	 */
	public function __destruct()
	{
		if ($this->autosave) $this->saveOptions();
	}
	
	/**
	 * Функция задает файл конфигурационный файл и формат данных
	 * Формат определяемый из расширения файла, либо через опциональный параметр $format
	 * 
	 * @param string $c_file
	 * @param string $format
	 */
	public function setConfigFile($c_file='',$format='auto')
	{
		$this->config_file=$c_file;
		if (file_exists($c_file))
		{
			dprint('Options file <' . $c_file . '> has been found');
			//Запишем формат файла
			if ($format=='auto')
			{
			//print_r('->setConfigFile:' . $c_file . '<br>');
			$this->format=getFileExtension($c_file);
			}
		}
	}
	
	/** Получение настроек
	 * 
	 * @param text $key - ключ в дереве настроек. 
	 * Если пустой - будет возвращено все дерево
	 * @return multitype:|NULL|array|text|integer возвращает 
	 * параметр настроек, либо поддерево настроек, либо все дерево настроек 
	 */
	public function getOptions($key = NULL) {
		
		// Если файла настроек нет
		if (!is_file($this->config_file)) {
			//Создадим навые значения (они автоматически запишутся в новый файл
			$this->setOptions("", array(),true);
			dprint(dprint("File <b>{$this->config_file}</b> doesn't exist. "));
		}
	
		// Если мы еще не распаковывали настройки
		if (!$this->optionsLoaded) { 
			$this->loadOptions();		
		}
		else {
			$opt = $this->options;
		}
	
		// Начинаем с самого верхнего уровня
		$destination = $this->options;
		if ($key === NULL) { //значит, нужно все дерево настроек
			dprint("Full tree requested");			
			return $this->options;
		}
	
		// Ключи формата key.foo.bar
		$tree_path = explode('.', $key);
	
		// Проходим все узлы
		foreach ($tree_path as $k=>$v) {
			// Есть ли такой узел на данном уровне?
			if (!isset($destination[$v])) {
				dprint("Getting by key - $key. Depth $k, node [$v] of options tree: node $v doesn't exist.");
				return NULL;
			}
			// Спускаемся на уровень вниз
			$destination = &$destination[$v];
		}
		dprint("Options returned by key <b>$key</b>");
		return $destination;
	}
	
	/**
	 * Функция находит элемент, на который указывает ключ, и возвращает ссылку на него (чтобы им можно было полноценно манипулировать)
	 * @param string $key ключ (адрес в конфиге - например "main.site.title")
	 */
	function getLinkToOption(&$key)
	{
		//получаем массив ключей
		$tree_path = explode('.', $key);
		//получаем ссылку на массив настроек
		$destination = &$this->options;
		// Проходим все узлы
		foreach ($tree_path as $k=>$v) {
			// Есть ли такой узел на данном уровне?
			if (!isset($destination[$v])) {				
				dprint("Getting by key - $key. Depth $k, node [$v] of options tree: node $v doesn't exist.");				
				return NULL;
			}
			// Спускаемся на уровень вниз
			$destination = &$destination[$v];
		}		
		return $destination;
	} 
	
	/**
	 * Функция сохранения настроек в файл - вызывает подходящую функцию в соответствии с форматом	 
	 */
	function saveOptions()
	{
		//Имя функции (должно получиться например 'json_save')
		$func=$this->format . "_save";
		
		//вызовем функцию
		if (!method_exists($this, $this->format  . "_load"))
		{
			die("AF_Config::saveOptions Обработчик сохранения формата '" . $this->format . "' не найден");
		}		
		$this->$func();
	}
	
	/**
	 * Функция загрузки настроек из файла - вызывает подходящую функцию в соответствии с форматом	 
	 */
	function loadOptions()
	{
		//Имя функции (должно получиться например 'json_save')
		$func= $this->format . "_load";
		//print_r("func = $func<br>");
		//вызовем функцию
		if (!method_exists($this, $this->format  . "_load"))
		{
			die("AF_Config::loadOptions Обработчик загрузки формата '" . $this->format . "' не найден");
		}		
		$this->$func();
		$this->optionsLoaded=true;
	}
	
	/**
	 * Обработчик сохранения для json
	 */
	function json_save()
	{
		dprint('>AF_Config::json_save()');
		$need_chmod=(!file_exists($this->config_file));
		file_put_contents($this->config_file, json_encode($this->options));
		if ($need_chmod) chmod($this->config_file,0777);		
	}
	
	/**
	 * Обработчик загрузки для json
	 * @return boolean возвращает true если файл загрузить удалось и false если нет.	  
	 */
	function json_load()
	{
		dprint('>AF_Config::json_load()');
		//Если файл не существует - вернем ЛОЖЬ
		if (!file_exists($this->config_file)) return false;
		$this->options = json_decode(file_get_contents($this->config_file),true);
		return true;		
	}
	
	/**
	 * Функция загрузки для php - внутри php-файла должен быть код, задающий массив настроек $config
	 * @return boolean возвращает false если загрузить не удалось, true - если удалось
	 */
	function php_load()
	{
		dprint('>AF_Config::php_load()');
		//Если файл не существует - вернем ЛОЖЬ
		if (!file_exists($this->config_file)) return false;
		//подгрузим файл - в не мобязательно должна быть переменная $config
		//но на случай если ее нет - сделаем ее пустой заранее
		$config=array();
		include($this->config_file);
		$this->options=$config;
		//И уничтожим
		unset($config);
		return true;
	}
	
	/**
	 * Обработчик сохранения в формат php - создает в файле конструкцию вида <?php $config = array(...) ? > 
	 * где вместо "..." подставляется дерево настроек
	 */
	function php_save()
	{
		dprint('>AF_Config::php_save()');
		//Зададим сохраняемую переменную:
		$data='<?php $config = ' . var_export($this->options,true) . "; ?>";
		//Сохраним.
		$need_chmod=(!file_exists($this->config_file));
		file_put_contents($this->config_file, $data);
		if ($need_chmod) chmod($this->config_file,0777);	
	}
	
	
	/**
	 * Обработчик загрузки файлов *.plain_php	 
	 * @return boolean возвращает FALSE если файл открыть не удалось
	 */
	function plain_php_load()
	{
		dprint('>AF_Config::plain_php_load()');
		//Если файл не существует - вернем ЛОЖЬ
		if (!file_exists($this->config_file)) return false;
		//подгрузим файл - в не мобязательно должна быть переменная $config
		//но на случай если ее нет - сделаем ее пустой заранее
		$config=array();
		include($this->config_file);
		$this->options=$config;
		//И уничтожим
		unset($config);
		return true;
		
	}
	
	/**
	 * Обработчик сохранения для "плоского" PHP
	 * отличается от не плоского тем, что с помощью функции unroll_array() разворачивает
	 * массив и делает его более простым для понимания. Импорт настроек происходит также легко как и у *.php
	 */
	function plain_php_save()
	{
		//получим максимальную глубину вложенности
		$max_depth=get_array_max_depth($this->options);
		//развернем массив с ";" в конце и разделенными пробелами строками от 2-го уровня
		$arr = unroll_array($this->options, '$config',true,$max_depth-1);
		$data = "<?php \n" . implode("\n", $arr);
		//Сохраним.
		$need_chmod=(!file_exists($this->config_file));
		file_put_contents($this->config_file, $data);
		if ($need_chmod) chmod($this->config_file,0777);
	}
	
	/**
	 * Обработчик "сохранения" в память - используется когда формат настроен на хранение настроек в памяти
	 */
	function tmp_save()
	{
		$this->tmp_storage=$this->options;
	}
	
	/**
	 * Обработчик "загрузки" из памяти
	 * @return boolean - всегда возвращает true;
	 */
	function tmp_load()
	{
		$this->options=$this->tmp_storage;
		return true;
	}
	
	
	/** Выставление настроек
	 * @param string $key ключ в дереве настроек (e.g. options.model.undeletable_items)
	 * @param mixed $value выставляемое значение (array/string/int/float/boolean) 
	 * @param boolean $autosave сохранять ли настройки сразу после изменения?
	 * @param boolean $append - если равен true, то новые настройки не переписывают старые, а дополняют
	 */
	public function setOptions($key, $value,$autosave=-1,$append=false) {
	
		//Если параметр автосохранения не задан - используем параметр заложенный в объекте
		if ($autosave==-1) $autosave=$this->autosave;
		//Есть ли файл с настройками?
		if (!is_file($this->config_file)) {
			dprint("File <b>{$this->config_file}</b> doesn't exist. Creating..");
			// создаем пустой файл настроек
			$this->options=array();
			if (autosave) $this->saveOptions();
		}
		if (!$this->optionsLoaded) $this->loadOptions();
		if ($key===NULL) {
			dprint("Root options were set.");
			if (!$append) $this->options=$value;
			if ( $append) $this->options = array_merge($this->options,$value);
			if ($autosave) $this->saveOptions();
			return TRUE;
		}
	
		// Начинаем с верхнего уровня
		$destination = &$this->options;
	
		// Разбиваем путь на узлы
		$tree_path = explode('.', $key);
		foreach ($tree_path as $k=>$v) {
			if (!is_array($destination)) {
				// Если уровня не существует - создаем
				dprint ("Set by key - <b>$key</b>. Destination is not array, overwriting..");
				$destination = array();
			}
			elseif (!isset($destination[$v])) { // если нет узла - добавляем
				dprint("Set by key - <b>$key</b>. Depth $k, node $destination of options tree: node $v doesn't exist. Appending..");
				$destination[$v] = NULL; //Чтобы меньше кидался warning'ами
			}
	
			// Спускаемся на уровень вниз
			$destination = &$destination[$v];
		}
	
		// Выставляем, собственно, значение
		if (!$append) $destination = $value;
		if ($append) $destination = array_merge($destiantion,$value);
		
	
		dprint("Options set by key <b>$key</b>");
		
		if ($autosave) $this->saveOptions();
	
		return TRUE;
	
	}
	
	/**
	 * Функция ищет исходные значения, и если они есть - копирует их в новое место.
	 * Например, если в конфиге есть параметры site.default.title = "сайт" и user.data.name = "вася", то могут быть произведены такие действия:<br>
	 * [code]copy("user","site")[/code] - это приведет к тому, что все что находится в "user" скопируется в "site", и кроме старых данных появятся: 
	 * site.data.name="вася" (все старые данные останутся как были)<br>
	 * [code]copy("user.data","")[/code] - содержимое user.data скопируется в корень конфигурации, появится корневая запись name = "вася"<br>
	 * [cody]copy("site","user.data.name")[/code] - вместо значения "Вася" в параметре user.data.name появится дополнительная вложенности, содержащая:
	 * user.data.name.default.title="сайт".<br>
	 * И так далее...	 
	 * @param string $from Исходный адрес
	 * @param string $to Конечный адрес
	 * @return boolean возвращает true в случае успеха, false в случае неудачи
	 */
	public function copyOptions($from,$to)
	{
		if ($to=='') $to=NULL;
		$data = $this->getOptions($from);
		//Добавляем настройки в режиме append, 
		$this->setOptions($to, $data,-1,true);
		
		return true;
		
	}
	
	
}
