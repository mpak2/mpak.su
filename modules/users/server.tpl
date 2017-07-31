<?

pre(
	date("Y.m.d H:i:s"). " (". time(). ")",
	$conf['user'],
	"http_response_code()", http_response_code(),
	"apache_request_headers()", apache_request_headers(),
	'$_COOKIE', $_COOKIE,
	'$_GET', $_GET,
	'$_POST', $_POST,
	'$_SERVER', $_SERVER
);

if(!$Memcached = new Memcached()){ mpre("Ошибка создания класса Мемкаш");
}elseif(!$Memcached->addServer('localhost', 11211)){ mpre("Ошибка подключения к серверу Мемкаш");
//}elseif(mpre(get_class_methods("Memcached"), $Memcached)){
//}elseif(!$cache = $Memcached->set("Проверка", "Пример")){ mpre("Ошибка выборки данных из кеша");
}elseif(!$cache = $Memcached->getStats()){ mpre("Ошибка выборки данных из кеша");
}else{ pre($cache); }
