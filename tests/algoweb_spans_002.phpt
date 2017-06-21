--TEST--
Algoweb: Start/Stop Timers
--FILE--
<?php

algoweb_enable();

$span1 = algoweb_span_create('php');
algoweb_span_timer_start($span1);
algoweb_span_timer_stop($span1);

$span2 = algoweb_span_create('php');
algoweb_span_timer_start($span2);
algoweb_span_timer_stop($span2);
algoweb_span_timer_start($span2);
algoweb_span_timer_stop($span2);

algoweb_disable();

$spans = algoweb_get_spans();
var_dump($spans);

--EXPECTF--
array(3) {
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
    array(1) {
      [0]=>
      int(%d)
    }
    ["e"]=>
    array(1) {
      [0]=>
      int(%d)
    }
  }
  [2]=>
  array(3) {
    ["n"]=>
    string(3) "php"
    ["b"]=>
    array(2) {
      [0]=>
      int(%d)
      [1]=>
      int(%d)
    }
    ["e"]=>
    array(2) {
      [0]=>
      int(%d)
      [1]=>
      int(%d)
    }
  }
}
