# Redis-Ranking
Redis-Ranking 库使用 Redis 的 SortedSet 存储排行数据。其中，
1. 使用 ZINCRBY 导入单条分数变更记录；
2. 使用 ZREVRANGE 获取 top x 的用户数据（按照 score 从大到小排序）；
3. 使用 ZRANK 获取某个用户的排名；
4. 使用 ZSCORE 获取某个用户的分数；

# 安装
```bash
$ composer require -vvv zqhong/redis-ranking
```

# 使用
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Predis\Client;
use Zqhong\RedisRanking\Ranking\TotalRanking;
use Zqhong\RedisRanking\RankingManger;
use Zqhong\RedisRanking\Test\Fixture\DummyTotalDataSource;

$redisClient = new Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379,
    'password' => 'secret',
    'database ' => 1,
]);

$rankingManager = (new RankingManger())
    // 这里的 DataSource 类需要自己实现
    ->setDataSource(new DummyTotalDataSource())
    // 设置需要获取的排行数据，目前有：
    // 总排行、日排行、周排行、月排行、季排行、年排行
    ->setRankingClasses([
        TotalRanking::class,
    ])
    // 设置当前排行数据的 namespace
    ->setRankingName('test')
    // 注入 Redis 客户端
    ->setRedisClient($redisClient)
    // 初始化
    ->init();

// 获取总排行榜中前三名用户的数据（带分数）
$rankingManager->totalRanking->top(3);

// 获取总排行榜中前三名用户的数据（不带分数）
$rankingManager->totalRanking->top(3, false);

// 获取用户 akira 的排名（总排行榜中）
$rankingManager->totalRanking->rank('akira');

// 获取用户 akira 的分数（总排行榜中）
$rankingManager->totalRanking->score('akira');

// 添加一条分数变更记录（总排行榜）
// 如果用户 test 不存在，则会添加该用户，分数为 1
// 如果用户 test 已存在，该用户的分数则会在原分数基础上 + 1
$rankingManager->totalRanking->add('test', 1);

// 获取参与该排行榜的人数
echo $rankingManager->totalRanking->cardinality();
```

# 目前存在问题：
如果排名规则不仅仅是依靠 score 的话，这种方式的实现就不大合适。
