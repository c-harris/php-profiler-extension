--TEST--
Algoweb: Cross Limit of 1500 spans
--FILE--
<?php

function testing(array $annotations) {
}

algoweb_enable();
algoweb_span_callback('testing', function ($context) {
    $id = algoweb_span_create('php');
    algoweb_span_annotate($id, $context['args'][0]);
    return $id;
});

$id = algoweb_span_create("test");
algoweb_span_annotate($id, array("test" => "before"));

for ($i = 0; $i < 2000; $i++) {
    testing(array("foo" => "$i"));
}

$id = algoweb_span_create("test");
algoweb_span_annotate($id, array("test" => "before"));

$spans = algoweb_get_spans();
echo count($spans) . "\n";
var_dump($spans[1]);
var_dump($spans[1499]);
var_dump(count($spans[1500]['b']));
var_dump($spans[1500]['a']);
var_dump($spans[1501]);

--EXPECTF--
1502
array(4) {
  ["n"]=>
  string(4) "test"
  ["b"]=>
  array(0) {
  }
  ["e"]=>
  array(0) {
  }
  ["a"]=>
  array(1) {
    ["test"]=>
    string(6) "before"
  }
}
array(4) {
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
  ["a"]=>
  array(2) {
    ["foo"]=>
    string(4) "1497"
    ["fn"]=>
    string(7) "testing"
  }
}
int(502)
array(3) {
  ["foo"]=>
  string(4) "1999"
  ["fn"]=>
  string(7) "testing"
  ["trunc"]=>
  string(1) "1"
}
array(4) {
  ["n"]=>
  string(4) "test"
  ["b"]=>
  array(0) {
  }
  ["e"]=>
  array(0) {
  }
  ["a"]=>
  array(1) {
    ["test"]=>
    string(6) "before"
  }
}
