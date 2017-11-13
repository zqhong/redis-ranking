<?php

namespace Zqhong\RedisRanking\Test;

use PHPUnit\Framework\TestCase;
use Predis\Client;
use Zqhong\RedisRanking\Ranking\DailyRanking;
use Zqhong\RedisRanking\Ranking\MonthlyRanking;
use Zqhong\RedisRanking\Ranking\QuarterlyRanking;
use Zqhong\RedisRanking\Ranking\TotalRanking;
use Zqhong\RedisRanking\Ranking\WeeklyRanking;
use Zqhong\RedisRanking\Ranking\YearlyRanking;
use Zqhong\RedisRanking\RankingManger;
use Zqhong\RedisRanking\Test\Fixture\DummyDayDataSource;

abstract class RankingTestCase extends TestCase
{
    /**
     * @var Client
     */
    protected $redisClient;

    /**
     * @var RankingManger
     */
    protected $rankingManager;

    protected function setUp()
    {
        $options = [
            'scheme' => getenv('REDIS_SCHEME'),
            'host' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'database ' => getenv('REDIS_DATABASE'),
        ];

        if (!empty(getenv('REDIS_PASSWORD'))) {
            $options = array_merge($options, [
                'password' => getenv('REDIS_PASSWORD'),
            ]);
        }

        $this->redisClient = new Client($options);

        $this->rankingManager = (new RankingManger())
            ->setDataSource(new DummyDayDataSource())
            ->setRankingClasses([
                DailyRanking::class,
                WeeklyRanking::class,
                MonthlyRanking::class,
                QuarterlyRanking::class,
                YearlyRanking::class,
                TotalRanking::class,
            ])
            ->setRankingName('test')
            ->setRedisClient($this->redisClient)
            ->init();
    }

    protected function tearDown()
    {
        $this->redisClient->flushdb();
    }
}