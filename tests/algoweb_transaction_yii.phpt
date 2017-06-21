--TEST--
Algoweb: yii Framework Detection
--FILE--
<?php

abstract class CController
{
    public function run($actionID)
    {
    }
}

class UserController extends CController
{
}

algoweb_enable(0, array('transaction_function' => 'CController::run'));

$ctrl = new UserController();
$ctrl->run('view');

echo 'view: ' . algoweb_transaction_name() . "\n";

algoweb_disable();

algoweb_enable(0, array('transaction_function' => 'CController::run'));
$ctrl = new UserController();
$ctrl->run(NULL);
echo 'empty: ' . algoweb_transaction_name() . "\n";
--EXPECTF--
view: UserController::view
empty: 

