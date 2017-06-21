--TEST--
Algoweb: Check for no memleaks
--FILE--
<?php

algoweb_enable();
$spans = algoweb_get_spans();
$data = algoweb_disable();
$spans = algoweb_get_spans();
--EXPECTF--
