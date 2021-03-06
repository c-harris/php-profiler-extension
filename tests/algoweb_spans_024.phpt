--TEST--
Algoweb: Doctrine CouchDB Support
--FILE--
<?php

require_once __DIR__ . '/common.php';
require_once __DIR__ . '/algoweb_doctrine.php';

algoweb_enable();

$client = new \Doctrine\CouchDB\HTTP\StreamClient();
$client->request('GET', '/_foo');

$client = new \Doctrine\CouchDB\HTTP\SocketClient();
$client->request('GET', '/_bar');

print_spans(algoweb_get_spans());
--EXPECTF--
string(9) "GET /_foo"
string(9) "GET /_bar"
app: 1 timers - 
http: 1 timers - method=GET service=couchdb url=/_foo
http: 1 timers - method=GET service=couchdb url=/_bar
