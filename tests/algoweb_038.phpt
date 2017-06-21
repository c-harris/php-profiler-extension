--TEST--
Algoweb: Test CPU Timer and Main() only CPU Timer
--FILE--
<?php

algoweb_enable(ALGOWEB_FLAGS_CPU);

sleep(1);

function foo() {
    for ($i = 0; $i < 1000000; $i++) {}
}
foo();

$data = algoweb_disable();

if (($data['main()']['wt'] - $data['main()']['cpu']) > 1000000) {
    echo "OK\n";
} else {
    echo "FAIL\n";
}
--EXPECTF--
OK
