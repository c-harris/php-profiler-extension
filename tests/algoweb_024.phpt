--TEST--
XHProf: Transaction name detection when disabling userland
--FILE--
<?php

function get_query_template($name) {
}

// Test 1: Detection with layers
algoweb_enable(
    ALGOWEB_FLAGS_NO_USERLAND | ALGOWEB_FLAGS_NO_BUILTINS,
    array('transaction_function' => 'get_query_template')
);

get_query_template("home");

echo "Wordpress 1: " . algoweb_transaction_name() . "\n";
$data = algoweb_disable();

// Test 2: Repetition to catch global state errors
algoweb_enable(
    ALGOWEB_FLAGS_NO_USERLAND | ALGOWEB_FLAGS_NO_BUILTINS,
    array('transaction_function' => 'get_query_template')
);
get_query_template("page");

echo "Wordpress 2: " . algoweb_transaction_name() . "\n";
$data = algoweb_disable();

// Test 3: Without any layers defined
algoweb_enable(
    ALGOWEB_FLAGS_NO_USERLAND | ALGOWEB_FLAGS_NO_BUILTINS,
    array('transaction_function' => 'get_query_template')
);
get_query_template("post");

echo "Wordpress 3: " . algoweb_transaction_name() . "\n";
$data = algoweb_disable();

--EXPECTF--
Wordpress 1: home
Wordpress 2: page
Wordpress 3: post
