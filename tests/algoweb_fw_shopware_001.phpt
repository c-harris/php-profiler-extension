--TEST--
Algoweb: Shopware Support
--FILE--
<?php

include __DIR__ . '/common.php';

abstract class Enlight_Object {
}
abstract class Enlight_Controller_Action extends Enlight_Object {
    public function dispatch($method)
    {
        $this->$method();
    }
}
class ShopController extends Enlight_Controller_Action {
    public function fooAction() {}
    public function barAction() {}
}

class ShopProxyController extends ShopController {
}

algoweb_enable();

$ctrl = new ShopProxyController();
$ctrl->dispatch('fooAction');
$ctrl->dispatch('barAction');

print_spans(algoweb_get_spans());
--EXPECTF--
app: 1 timers - 
php.ctrl: 1 timers - title=ShopProxyController::fooAction
php.ctrl: 1 timers - title=ShopProxyController::barAction
