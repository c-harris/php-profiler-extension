--TEST--
Algoweb: Span Callback no Leaks
--FILE--
<?php
function foo() {}
function foo_cb() {}

algoweb_enable();
algoweb_span_callback('foo', 'foo_cb');
foo();
algoweb_disable();
--EXPECTF--
