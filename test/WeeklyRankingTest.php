<?php

namespace Zqhong\RedisRanking\Test;

class WeeklyRankingTest extends RankingTestCase
{
    public function testGetExpiredAt()
    {
        $expiredAt = strtotime('Monday next week') - 1;
        $this->assertEquals($expiredAt, $this->rankingManager->weekRanking->getExpiredAt());
    }
}