--TEST--
Algoweb: Memcache Support
--SKIPIF--
<?php
if (!extension_loaded('memcache')) {
    echo "skip: memcache missing\n";
}
--FILE--
<?php

include __DIR__ . '/common.php';

algoweb_enable();

$memcache = new Memcache();
$memcache->connect('localhost');

$memcache->set('foo', 'bar');
$memcache->get('foo', 'bar');

algoweb_disable();

print_spans(algoweb_get_spans());
--EXPECT--
app: 1 timers - 
memcache: 1 timers - title=Memcache::set
memcache: 1 timers - title=Memcache::get
