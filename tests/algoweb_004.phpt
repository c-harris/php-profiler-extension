--TEST--
Algoweb: Test Include File (load/run_init operations)
--FILE--
<?php

include_once dirname(__FILE__).'/common.php';

algoweb_enable();

// Include File:
//
// Note: the 2nd and 3rd attempts should be no-ops and
// will not show up in the profiler data. Only the first
// one should.

include_once dirname(__FILE__).'/algoweb_004_inc.php';
include_once dirname(__FILE__).'/algoweb_004_inc.php';
include_once dirname(__FILE__).'/algoweb_004_inc.php';


// require_once:
// Note: the 2nd and 3rd attempts should be no-ops and
// will not show up in the profiler data. Only the first
// one should.

require_once dirname(__FILE__).'/algoweb_004_require.php';
require_once dirname(__FILE__).'/algoweb_004_require.php';
require_once dirname(__FILE__).'/algoweb_004_require.php';

$output = algoweb_disable();

echo "Test for 'include_once' & 'require_once' operation\n";

$spans = algoweb_get_spans();

echo "Includes: " . $spans[0]['a']['cct'] . "\nTime: " . $spans[0]['a']['cwt'] . "\n";
?>
--EXPECTF--
abc,def,ghi
I am in foo()...
11
I am in bar()...
Test for 'include_once' & 'require_once' operation
Includes: 2
Time: %d
