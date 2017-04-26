<?

pre(date("Y.m.d H:i:s"). " (". time(). ")", http_response_code());
pre(apache_request_headers());
pre('$_GET', $_GET, '$_POST', $_POST);
pre('$_SERVER', $_SERVER);
pre($conf['user']);

