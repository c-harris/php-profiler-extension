--TEST--
Algoweb: Symfony Support
--FILE--
<?php

include __DIR__ . '/common.php';
include __DIR__ . '/algoweb_symfony.php';

algoweb_enable();

$kernel = new \Symfony\Component\HttpKernel\Kernel();
$kernel->boot();

$httpKernel = new \Symfony\Component\HttpKernel\HttpKernel();
$httpKernel->handle('indexAction');
$httpKernel->handle('helloAction');

print_spans(algoweb_get_spans());

algoweb_disable();

--EXPECTF--
app: 1 timers - 
php: 1 timers - title=Symfony\Component\HttpKernel\Kernel::boot
php.ctrl: 1 timers - title=Acme\DemoBundle\Controller\DefaultController::indexAction
php.ctrl: 1 timers - title=Acme\DemoBundle\Controller\DefaultController::helloAction
