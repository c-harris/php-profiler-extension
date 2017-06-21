--TEST--
Algoweb: Test NO_TXDETECT and NO_SPANS options
--FILE--
<?php

include __DIR__ . '/algoweb_symfony.php';

function get_sidebar() {
}

echo "Has Root Span with NO_SPANS\n";
algoweb_enable(ALGOWEB_FLAGS_NO_SPANS);
algoweb_disable();
echo (count(algoweb_get_spans()) === 1) ? "Success": "Failure";

echo "\n\n";

echo "No Autodetects spans with NO_SPANS\n";
algoweb_enable(ALGOWEB_FLAGS_NO_SPANS);
get_sidebar(); // auto wordpress fn.
algoweb_disable();
echo (count(algoweb_get_spans()) === 1) ? "Success": "Failure";

echo "\n\n";

echo "Autodetects spans without NO_SPANS\n";
algoweb_enable();
get_sidebar(); // auto wordpress fn.
algoweb_disable();
echo (count(algoweb_get_spans()) === 2) ? "Success": "Failure";

--EXPECT--
Has Root Span with NO_SPANS
Success

No Autodetects spans with NO_SPANS
Success

Autodetects spans without NO_SPANS
Success
