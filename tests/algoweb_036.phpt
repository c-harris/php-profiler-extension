--TEST--
Algoweb: Skip require/include and eval profiling
--FILE--
<?php

require_once dirname(__FILE__) . "/common.php";

algoweb_enable(ALGOWEB_FLAGS_NO_COMPILE);

include dirname(__FILE__) . "/algoweb_004_inc.php";

eval("function evaledfoo() {}");

evaledfoo();

$output = algoweb_disable();
print_canonical($output);
echo "\n";
--EXPECTF--
abc,def,ghi
I am in foo()...
main()                                  : ct=       1; wt=*;
main()==>algoweb_disable                : ct=       1; wt=*;
main()==>dirname                        : ct=       1; wt=*;
main()==>evaledfoo                      : ct=       1; wt=*;
main()==>explode                        : ct=       1; wt=*;
main()==>foo                            : ct=       1; wt=*;
main()==>implode                        : ct=       1; wt=*;

