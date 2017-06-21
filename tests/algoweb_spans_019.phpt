--TEST--
Algoweb: MongoDB Support
--INI--
extension=mongo.so
--SKIPIF--
<?php
if (!extension_loaded('mongo')) {
    die('skip: mongo extension required');
}
if (!file_exists(__DIR__ . '/../modules/mongo.so')) {
    die('skip: must symlink mongo.so into modules directory');
}
try {
    $mongo = new MongoClient();
    $algoweb = $mongo->algowebtest;
    $mongo->dropDB('algowebtest');
} catch (\Exception $e) {
    die('skip: could not connect to mongo and create algowebtest db');
}
--FILE--
<?php

require __DIR__ . '/common.php';

algoweb_enable();

$mongo = new MongoClient();
$algoweb = $mongo->algowebtest;

$algoweb->items->find();
$algoweb->items->findOne();

for ($i = 0; $i < 10; $i++) {
    $algoweb->items->save(array('x' => 1));
}

$cursor = $algoweb->items->find();
$cursor->count();

while ($row = $cursor->next()) {
}

iterator_to_array($cursor);

algoweb_disable();
print_spans(algoweb_get_spans());

--EXPECTF--
app: 1 timers - cpu=%d
mongo: 1 timers - collection=items title=MongoCollection::find
mongo: 1 timers - collection=items title=MongoCollection::findOne
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::save
mongo: 1 timers - collection=items title=MongoCollection::find
mongo: 1 timers - collection=algowebtest.items title=MongoCursor::count
mongo: 1 timers - collection=algowebtest.items title=MongoCursor::next
mongo: 1 timers - collection=algowebtest.items title=MongoCursor::rewind
