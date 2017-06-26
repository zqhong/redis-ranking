<?php

namespace Zqhong\RedisRanking\Ranking;

/**
 * 总排行榜
 *
 * @package Zqhong\RedisRanking
 */
class TotalRanking extends Ranking
{
    /**
     * 总排行榜不忽略任何数据
     *
     * @param array $item
     * @return bool
     */
    protected function ignore(array $item)
    {
        return false;
    }

    public function getRankingKey()
    {
        return sprintf('%s:total', $this->rankingName);
    }
}