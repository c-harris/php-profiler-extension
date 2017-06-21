--TEST--
Algoweb: Function that checks if prepend was overwritten
--FILE--
<?php

$exists = file_exists(ini_get("extension_dir") . "/Algoweb.php");

if (algoweb_prepend_overwritten() === $exists) {
    echo "OK!";
}
--EXPECTF--
OK!
