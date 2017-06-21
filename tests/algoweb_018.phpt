--TEST--
Algoweb: Exclude userland functions from profilling
--FILE--
<?php

include_once dirname(__FILE__).'/common.php';

function foo($x) {
    return bar($x);
}
function bar($x) {
    return substr($x, 0, 1);
}

algoweb_enable(ALGOWEB_FLAGS_NO_USERLAND);
foo("foo");
$data = algoweb_disable();

echo "Output:\n";
print_canonical($data);
?>
--EXPECTF--
Output:
main()                                  : ct=       1; wt=*;
main()==>algoweb_disable                : ct=       1; wt=*;
main()==>substr                         : ct=       1; wt=*;

