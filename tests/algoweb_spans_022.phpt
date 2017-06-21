--TEST--
Algoweb: Limit Size of String Annotation generated from Internal APIs to 1000
--FILE--
<?php

algoweb_enable();

@file_get_contents("http://localhost/?" . str_repeat("a", 2000));

algoweb_disable();
$spans = algoweb_get_spans();
echo strlen($spans[1]['a']['url']);
--EXPECTF--
1000
