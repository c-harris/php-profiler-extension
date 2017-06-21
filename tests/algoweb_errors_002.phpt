--TEST--
Algoweb: exception function
--FILE--
<?php

function foo($e) {}
function bar($name) {}
function baz($other, \Exception $e, $another) {}

algoweb_enable(0, array('exception_function' => 'foo'));

foo($original = new Exception("foo"));

$fetched = algoweb_last_detected_exception();
algoweb_disable();

var_dump($fetched->getMessage());
var_dump($fetched === $original);

algoweb_enable(0, array('exception_function' => 'foo'));

foo($original = new RuntimeException("detect mode"));

$fetched = algoweb_last_detected_exception();
algoweb_disable();

var_dump($fetched->getMessage());

algoweb_enable(0, array('exception_function' => 'baz'));
baz(1, $original = new RuntimeException("random argument"), 2);
$fetched = algoweb_last_detected_exception();
algoweb_disable();

var_dump($fetched->getMessage());

--EXPECTF--
string(3) "foo"
bool(true)
string(11) "detect mode"
string(15) "random argument"
