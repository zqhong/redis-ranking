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
    }

    protected function tearDown()
    {
        $this->redisClient->flushdb();
    }
}