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

    /**
     * 获取排行榜的过期时间，仅在大于0的时候有效
     *
     * @return integer
     */
    public function getExpiredAt()
    {
        // 设置排行榜永不过期
        return -1;
    }
}