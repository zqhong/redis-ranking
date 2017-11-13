<?php

namespace Zqhong\RedisRanking\Test;

use Zqhong\RedisRanking\Ranking\DailyRanking;
use Zqhong\RedisRanking\RankingManger;
use Zqhong\RedisRanking\Test\Fixture\DummyDayDataSource;

class DailyRankingTest extends RankingTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->rankingManager = (new RankingManger())
            ->setDataSource(new DummyDayDataSource())
            ->setRankingClasses([
                DailyRanking::class,
            ])
            ->setRankingName('test')
            ->setRedisClient($this->redisClient)
            ->init();
    }

    public function testTop()
    {
        $this->assertEquals([
            'mike',
            'jian',
            'akira'
        ], $this->rankingManager->dailyRanking->top(3, false));

        $this->assertEquals([
            'mike' => 70,
            'jian' => 60,
            'akira' => 50,
        ], $this->rankingManager->dailyRanking->top(3, true));

    }

    public function testRank()
    {
        $this->assertEquals(1, $this->rankingManager->dailyRanking->rank('mike'));
        $this->assertEquals(2, $this->rankingManager->dailyRanking->rank('jian'));
        $this->assertEquals(3, $this->rankingManager->dailyRanking->rank('akira'));

        $this->assertEquals(null, $this->rankingManager->dailyRanking->rank('user_not_found'));
    }

    public function testScore()
    {
        $this->assertEquals(70, $this->rankingManager->dailyRanking->score('mike'));
        $this->assertEquals(60, $this->rankingManager->dailyRanking->score('jian'));
        $this->assertEquals(50, $this->rankingManager->dailyRanking->score('akira'));

        $this->assertEquals(null, $this->rankingManager->dailyRanking->score('user_not_found'));
    }
}