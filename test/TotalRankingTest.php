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

    protected function tearDown()
    {
        $this->rankingManager->getRedisClient()->flushall();
    }


    public function testTotalRankingTop()
    {
        $this->assertEquals([
            'akira',
            'jian',
            'mike',
        ], $this->rankingManager->totalRanking->top(3, false));

        $this->assertEquals([
            'akira' => '100',
            'jian' => '87',
            'mike' => '59',
        ], $this->rankingManager->totalRanking->top(3, true));
    }

    public function testTotalRankingRank()
    {
        $this->assertEquals(1, $this->rankingManager->totalRanking->rank('akira'));
        $this->assertEquals(2, $this->rankingManager->totalRanking->rank('jian'));
        $this->assertEquals(3, $this->rankingManager->totalRanking->rank('mike'));

        $this->assertEquals(null, $this->rankingManager->totalRanking->rank('user_not_found'));
    }

//    public function testRankingAdd()
//    {
//
//    }

    public function testRankingScore()
    {
        $this->assertEquals(100, $this->rankingManager->totalRanking->score('akira'));
        $this->assertEquals(87, $this->rankingManager->totalRanking->score('jian'));
        $this->assertEquals(59, $this->rankingManager->totalRanking->score('mike'));

        $this->assertEquals(null, $this->rankingManager->totalRanking->score('user_not_found'));
    }



}