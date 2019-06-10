<?php
/*----------------------------------------------------------------------------------------------------------------------
 |  Redis 消息发布订阅实例
 |----------------------------------------------------------------------------------------------------------------------
 | 消息发布脚本:
 | 基于事件的系统中，Pub/Sub是目前广泛使用的通信模型，它采用事件作为基本的通信机制，
 | 提供大规模系统所要求的松散耦合的交互模式：订阅者(如客户端)以事件订阅的方式表达出它有兴趣接收的一个事件或一类事件；
 | 发布者(如服务器)可将订阅者感兴趣的事件随时通知相关订阅者
 */

$redis = new Redis();
//连接参数：ip、端口、连接超时时间，连接成功返回true，否则返回false
$ret = $redis->connect('127.0.0.1', 6379, 30);
//chat为发布的频道名称,hello,world为发布的消息
$res = $redis->publish('chat','hello,world');