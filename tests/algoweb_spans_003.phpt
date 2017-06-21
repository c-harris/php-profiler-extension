--TEST--
Algoweb: Span Annotations
--FILE--
<?php

algoweb_enable();

$span = algoweb_span_create('app');
algoweb_span_annotate($span, array('foo' => 'bar', 'bar' => 'baz'));
algoweb_disable();

algoweb_span_annotate($span, array('baz' => 1));

var_dump(algoweb_get_spans());
--EXPECTF--
array(2) {
  [0]=>
  array(4) {
    ["n"]=>
    string(3) "app"
    ["b"]=>
    array(1) {
      [0]=>
      int(%d)
    }
    ["e"]=>
    array(1) {
      [0]=>
      int(%d)
    }
    ["a"]=>
    array(1) {
      ["cpu"]=>
      string(%d) "%d"
    }
  }
  [1]=>
  array(4) {
    ["n"]=>
    string(3) "app"
    ["b"]=>
    array(0) {
    }
    ["e"]=>
    array(0) {
    }
    ["a"]=>
    array(3) {
      ["foo"]=>
      string(3) "bar"
      ["bar"]=>
      string(3) "baz"
      ["baz"]=>
      string(1) "1"
    }
  }
}
