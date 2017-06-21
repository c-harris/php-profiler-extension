--TEST--
Algoweb: Span Create/Get
--FILE--
<?php

$span = algoweb_span_create('app');
$spans = algoweb_get_spans();

var_dump($span);
var_dump($spans);

algoweb_enable();

$span = algoweb_span_create('php');
algoweb_disable();

$spans = algoweb_get_spans();

var_dump($span);
var_dump($spans);

algoweb_enable();

$span = algoweb_span_create('app');
algoweb_disable();

$spans = algoweb_get_spans();

var_dump($span);
var_dump($spans);

--EXPECTF--
NULL
NULL
int(1)
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
  array(3) {
    ["n"]=>
    string(3) "php"
    ["b"]=>
    array(%d) {
    }
    ["e"]=>
    array(%d) {
    }
  }
}
int(1)
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
  array(3) {
    ["n"]=>
    string(3) "app"
    ["b"]=>
    array(%d) {
    }
    ["e"]=>
    array(%d) {
    }
  }
}
