<?php
/*----------------------------------------------------------------------------------------------------------------------
 |  Redis 消息发布订阅实例
 |----------------------------------------------------------------------------------------------------------------------
 | 消息订阅脚本:
 | 消息订阅者，即subscribe客户端，需要独占链接，即进行subscribe期间，redis-client无法穿插其他操作，此时client以阻塞的方式等待“publish端”的消息，
 | 所以我们需要当前脚本在命令行来执行,作为一个长连接来监听消息的发布
 |
 */
$redis = new Redis();
//连接参数：ip、端口、连接超时时间，连接成功返回true，否则返回false
$ret = $redis->connect('127.0.0.1', 6379, 30);
//subscribe 的第一个参数为订阅消息的频道,可以同时监听多个频道以数组方式传递
//subscribe 的第二个参数为接收到消息时的回调处理函数
$redis->subscribe(array('chat'), 'callback');

// 回调函数,这里写处理逻辑
function callback($instance, $channelName, $message)
{
    echo $channelName, "==>", $message, PHP_EOL;
}