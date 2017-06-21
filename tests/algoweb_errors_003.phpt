--TEST--
Algoweb: algoweb_last_fatal_error() for B/C reasons
--SKIPIF--
<?php
if (PHP_VERSION_ID < 70000) { echo "skip: php7 only\n"; }
--FILE--
<?php

register_shutdown_function(function() {
    var_dump(algoweb_last_fatal_error());
});

foo();
--EXPECTF--
%s: Call to undefined function foo() in %s/tests/algoweb_errors_003.php%s7
Stack trace:
#0 {main}
  thrown in %s/tests/algoweb_errors_003.php on line 7
array(4) {
  ["type"]=>
  int(1)
  ["message"]=>
  string(%d) "%sCall to undefined function foo()%s
Stack trace:
#0 {main}
  thrown"
  ["file"]=>
  string(%d) "%s/tests/algoweb_errors_003.php"
  ["line"]=>
  int(7)
}
