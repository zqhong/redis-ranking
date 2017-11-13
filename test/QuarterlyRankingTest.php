<?php

namespace Zqhong\RedisRanking\Test;

class QuarterlyRankingTest extends RankingTestCase
{
    public function testGetExpiredAt()
    {
        // 获取当前季度最后一个月份 + 1
        $month = (date_quarter(date('m')) * 3 + 1) % 12;
        $year = date('Y');
        if ($month < date('m')) {
            $year += 1;
        }

        $expiredAt = strtotime(date(sprintf('%d-%d-1 0:0:0', $year, $month))) - 1;

        $this->assertEquals($expiredAt, $this->rankingManager->quarterlyRanking->getExpiredAt());
    }
}