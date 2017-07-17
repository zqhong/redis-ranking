<?php

namespace Zqhong\RedisRanking\Test;

use PHPUnit\Framework\TestCase;
use Predis\Client;
use Zqhong\RedisRanking\RankingManger;

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
        $this->redisClient = new Client([
            'scheme' => getenv('REDIS_SCHEME'),
            'host' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'password' => getenv('REDIS_PASSWORD'),
            'database ' => getenv('REDIS_DATABASE'),
        ]);
    }

    protected function tearDown()
    {
        $this->redisClient->flushall();
    }
}