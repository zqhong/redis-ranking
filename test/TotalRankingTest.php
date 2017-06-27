<?php

namespace Zqhong\RedisRanking\Test;

use PHPUnit\Framework\TestCase;
use Predis\Client;
use Zqhong\RedisRanking\Data\DummyDataSource;
use Zqhong\RedisRanking\Ranking\TotalRanking;
use Zqhong\RedisRanking\RankingManger;

class TotalRankingTest extends TestCase
{
    protected $redisClient;

    /**
     * @var RankingManger
     */
    protected $rankingManager;

    protected function setUp()
    {
        $redisClient = new Client([
            'scheme' => getenv('REDIS_SCHEME'),
            'host' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'password' => getenv('REDIS_PASSWORD'),
            'database ' => getenv('REDIS_DATABASE'),
        ]);

        $this->rankingManager = (new RankingManger())
            ->setDataSource(new DummyDataSource())
            ->setRankingClasses([
                TotalRanking::class,
            ])
            ->setRankingName('test')
            ->setRedisClient($redisClient)
            ->init();
    }

    public function testTotalRankingTop()
    {
        $this->assertEquals([
            'akira',
            'jian',
            'mike',
        ], $this->rankingManager->totalRanking->top(3, false));
    }

//    public function testTotalRankingRank()
//    {
//
//    }
//
//    public function testRankingAdd()
//    {
//
//    }
//
//    public function testRankingScore()
//    {
//
//    }

}