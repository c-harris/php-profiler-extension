--TEST--
Algoweb: Auto Prepend File 1
--INI--
auto_prepend_file=tests/algoweb_039_prepend.php
algoweb.auto_prepend_library=0
--FILE--
<?php
--EXPECT--
Hello Auto Prepend!
