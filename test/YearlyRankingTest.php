<?php

namespace Zqhong\RedisRanking\Test;

class YearlyRankingTest extends RankingTestCase
{
    public function testGetExpiredAt()
    {
        $expiredAt = strtotime('last day of december this year') + 24 * 60 * 60 - 1;
        $this->assertEquals($expiredAt, $this->rankingManager->yearlyRanking->getExpiredAt());
    }
}