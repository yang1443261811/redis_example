<?php
$uid = uniqid();
$ip = $_SERVER["REMOTE_ADDR"];
if (!$uid) {
    exit('用户ID不能为空');
}

$redis = new Redis();
$ret = $redis->connect('127.0.0.1', 6379, 30);

//Redis Flushall 命令用于清空整个 Redis 服务器的数据(删除所有数据库的所有 key )。
//$redis->flushAll();

//访问频次限制,每个用户间隔两秒请求一次
$lockKey = 'lock' . $uid;
$stop = $redis->get($lockKey);
if ($stop) {
    exit('请稍后再试');
} else {
    $redis->set($lockKey, $uid, 2);
}

//将物品数量事先放入队列中
//$i = 1;
//while ($i <= 100) {
//    $redis->rPush('goods_count', $i);
//    $i++;
//}

//当物品队列读到空值时表示物品已经抢购完了
//注意:使用队列存储物品数量,可以有效的防止在并发情况下超卖的问题,因为队列是原子操作串行执行的,
//而使用字符串类型来保存物品数量 会出现超买的情况
if (!$ret = $redis->rPop('goods_count')) {
    exit('物品已抢购完');
}
//如果用户已成功抢购过物品直接返回,如果没有,记录抢购成功日志
$exist = $redis->hGet('luckyLog', $uid);
if ($exist) {
    exit('你已经抢购过了!不能重复抢购');
} else {
    $redis->hSetNx('luckyLog', $uid, 'success');
}

//将抢购成功的用户存入到队列中,再异步处理
$ret = $redis->lPush('luckyUsers', $uid);
//物品已使用数量加1
$redis->incr('used_goods_count');

exit('物品抢购成功');