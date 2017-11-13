<?php

namespace Zqhong\RedisRanking\Test;

class TotalRankingTestCase extends RankingTestCase
{
    public function testTotalRankingTop()
    {
        $this->assertEquals([
            'yang',
            'kulu',
            'mike',
        ], $this->rankingManager->totalRanking->top(3, false));

        $this->assertEquals([
            'yang' => '90',
            'kulu' => '80',
            'mike' => '70',
        ], $this->rankingManager->totalRanking->top(3, true));
    }

    public function testTotalRankingRank()
    {
        $this->assertEquals(1, $this->rankingManager->totalRanking->rank('yang'));
        $this->assertEquals(2, $this->rankingManager->totalRanking->rank('kulu'));
        $this->assertEquals(3, $this->rankingManager->totalRanking->rank('mike'));

        $this->assertEquals(null, $this->rankingManager->totalRanking->rank('user_not_found'));
    }

    public function testRankingAdd()
    {
        $memberName = 'must_not_exists_user_abc';

        // 添加一个不存在的用户
        $this->rankingManager->totalRanking->add($memberName, 1);
        $this->assertEquals(1, $this->rankingManager->totalRanking->score($memberName));

        // 用户已存在的情况下，更新用户分数
        $this->rankingManager->totalRanking->add($memberName, 1);
        $this->assertEquals(2, $this->rankingManager->totalRanking->score($memberName));
    }

    public function testRankingScore()
    {
        $this->assertEquals(90, $this->rankingManager->totalRanking->score('yang'));
        $this->assertEquals(80, $this->rankingManager->totalRanking->score('kulu'));
        $this->assertEquals(70, $this->rankingManager->totalRanking->score('mike'));

        $this->assertEquals(null, $this->rankingManager->totalRanking->score('user_not_found'));
    }


    public function testCardinality()
    {
        $totalRank = $this->rankingManager->totalRanking;
        $this->assertEquals($totalRank->cardinality(), 5);

        $newMemberKey = 'user_' . uniqid();
        $totalRank->add($newMemberKey, 1000);
        $this->assertEquals($totalRank->cardinality(), 6);

        $this->rankingManager->getRedisClient()->zrem($totalRank->getRankingKey(), $newMemberKey);
        $this->assertEquals($totalRank->cardinality(), 5);
    }

    public function testGetExpiredAt()
    {
        $this->assertTrue($this->rankingManager->totalRanking->getExpiredAt() <= 0);
    }
}