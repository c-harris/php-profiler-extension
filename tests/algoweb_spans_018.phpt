--TEST--
Algoweb: Queue Support
--FILE--
<?php

require_once __DIR__ . '/common.php';
require_once __DIR__ . '/algoweb_queue.php';

algoweb_enable();

$queue = new \Pheanstalk\Pheanstalk;
$queue->put();
$queue->putInTube("foo");
$queue->putInTube("bar");

$queue = new \PhpAmqpLib\Channel\AMQPChannel;
$queue->basic_publish(array(), 'amqp.foo');
$queue->basic_publish(array(), 'amqp.bar');

algoweb_disable();
print_spans(algoweb_get_spans());
--EXPECTF--
app: 1 timers - cpu=%d
queue: 1 timers - title=default
queue: 1 timers - title=foo
queue: 1 timers - title=bar
queue: 1 timers - title=amqp.foo
queue: 1 timers - title=amqp.bar
