--TEST--
XHProf: Function Argument Profiling
--FILE--
<?php

include_once dirname(__FILE__).'/common.php';

function test_stringlength($string)
{
    return substr($string, 0, 3);
}

algoweb_enable(ALGOWEB_FLAGS_MEMORY, array('functions' => array('substr')));
test_stringlength('foo_array');
$output = algoweb_disable();

if (count($output) == 2 && isset($output['main()']) && isset($output['main()==>substr'])) {
    echo "Test passed";
} else {
    var_dump($output);
}
?>
--EXPECT--
Test passed
