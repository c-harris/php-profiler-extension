--TEST--
Algoweb: Fetch Error Exception on PHP 7
--SKIPIF--
<?php
if (PHP_VERSION_ID < 70000) {
    die("skip: PHP7+");
}
--FILE--
<?php

register_shutdown_function(function () {
    var_dump("shutdown", algoweb_last_detected_exception()->getMessage());
});

algoweb_enable();
try {
    foobar();
} catch (Error $e) {
    foobar();
}
algoweb_disable();
--EXPECTF--
Fatal error: Uncaught Error: Call to undefined function foobar() in %salgoweb_errors_005.php:11
Stack trace:
#0 {main}
  thrown in %salgoweb_errors_005.php on line 11
string(8) "shutdown"
string(35) "Call to undefined function foobar()"
