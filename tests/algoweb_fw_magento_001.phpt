--TEST--
Algoweb: Magento Support
--FILE--
<?php

include __DIR__ . '/common.php';

class Mage_Core_Model_App
{
    public function _initModules()
    {
        $config = new Mage_Core_Model_Config();
        $config->loadModules();
        $config->loadDb();
    }
}

class Mage_Core_Model_Config
{
    public function loadModules() {}
    public function loadDb() {}
}

abstract class Mage_Core_Block_Abstract
{
    public function toHtml() {}
}

class SomeView extends Mage_Core_Block_Abstract
{
}

algoweb_enable();

$app = new Mage_Core_Model_App();
$app->_initModules();

$block = new SomeView();
$block->toHtml();

print_spans(algoweb_get_spans());
--EXPECT--
app: 1 timers - 
php: 1 timers - title=Mage_Core_Model_App::_initModules
php: 1 timers - title=Mage_Core_Model_Config::loadModules
php: 1 timers - title=Mage_Core_Model_Config::loadDb
view: 1 timers - title=SomeView
