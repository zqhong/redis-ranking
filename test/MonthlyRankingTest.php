<?php

namespace Zqhong\RedisRanking\Test;

class MonthlyRankingTest extends RankingTestCase
{
    public function testGetExpiredAt()
    {
        $expiredAt = strtotime(date('Y-m-d 0:0:0', strtotime('first day of next month'))) - 1;

        $this->assertEquals($expiredAt, $this->rankingManager->monthlyRanking->getExpiredAt());
    }
}
