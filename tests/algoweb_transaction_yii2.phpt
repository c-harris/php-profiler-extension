--TEST--
Algoweb: yii2 Transaction Detection
--FILE--
<?php

namespace yii\base;

abstract class Module
{
    public function runAction($actionId, $params = array())
    {
    }
}

class UserController extends Module
{
}

algoweb_enable(0, array('transaction_function' => 'yii\base\Module::runAction'));

$ctrl = new UserController();
$ctrl->runAction('view');

echo 'view: ' . algoweb_transaction_name() . "\n";

algoweb_disable();

algoweb_enable(0, array('transaction_function' => 'yii\base\Module::runAction'));
$ctrl = new UserController();
$ctrl->runAction(NULL);
echo 'empty: ' . algoweb_transaction_name() . "\n";
--EXPECTF--
view: yii\base\UserController::view
empty: 

