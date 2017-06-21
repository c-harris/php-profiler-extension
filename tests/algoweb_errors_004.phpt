--TEST--
Algoweb: algoweb_last_fatal_error() for B/C reasons
--SKIPIF--
<?php
if (PHP_VERSION_ID >= 70000) { echo "skip: php5 only\n"; }
--FILE--
<?php

register_shutdown_function(function() {
    var_dump(algoweb_last_fatal_error());
});

foo();
--EXPECTF--
Fatal error: Call to undefined function foo() in %s/tests/algoweb_errors_004.php on line 7
array(4) {
  ["type"]=>
  int(1)
  ["message"]=>
  string(32) "Call to undefined function foo()"
  ["file"]=>
  string(%d) "%s/tests/algoweb_errors_004.php"
  ["line"]=>
  int(7)
}
