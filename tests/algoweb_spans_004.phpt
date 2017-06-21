--TEST--
Algoweb: Autodetect file_get_contents() HTTP Spans
--FILE--
<?php

include __DIR__ . '/common.php';

algoweb_enable(0, array());

@file_get_contents("http://localhost");
@file_get_contents("http://localhost:8080");
@file_get_contents("http://localhost/phpinfo.php?query=param");
file_get_contents(__FILE__);

print_spans(algoweb_get_spans());

algoweb_disable();

--EXPECTF--
app: 1 timers - 
http: 1 timers - url=http://localhost
http: 1 timers - url=http://localhost:8080
http: 1 timers - url=http://localhost/phpinfo.php?query=param
