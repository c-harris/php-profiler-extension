--TEST--
XHProf: eval()
--FILE--
<?php

include_once dirname(__FILE__).'/common.php';

algoweb_enable();

eval("strlen('Hello World!');");

$data = algoweb_disable();

$spans = algoweb_get_spans();
echo $spans[0]['a']['cct'];

--EXPECTF--
1
