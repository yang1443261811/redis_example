<?php

$redis = new Redis();
$ret = $redis->connect('127.0.0.1', 6379, 30);


$redis->multi();
$redis->set('msg', 'my name is yang');
$redis->rPop('goods_count');
$redis->exec();