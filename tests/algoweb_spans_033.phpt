--TEST--
Algoweb: APC/APCu Support
--FILE--
<?php

require_once __DIR__ . '/common.php';

if (!function_exists('apcu_store')) {
    function apcu_store() {}
    function apcu_fetch() {}
}

algoweb_enable();

apcu_fetch("foo");
apcu_store("foo", 1234);

algoweb_disable();

print_spans(algoweb_get_spans());
--EXPECTF--
app: 1 timers - cpu=%d
apc: 1 timers - title=apcu_fetch
apc: 1 timers - title=apcu_store
