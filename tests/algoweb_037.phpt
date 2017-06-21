--TEST--
Algoweb: Enable stops and discards running trace
--FILE--
<?php

require_once dirname(__FILE__) . "/common.php";

algoweb_enable();

strlen("foo");

algoweb_enable();

substr("foo", 0, 3);

$output = algoweb_disable();
print_canonical($output);
echo "\n";
--EXPECT--
algoweb_disable                         : ct=       1; wt=*;
main()                                  : ct=       1; wt=*;
substr                                  : ct=       1; wt=*;

