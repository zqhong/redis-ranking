<?php

namespace Zqhong\RedisRanking\Ranking;

use Carbon\Carbon;

/**
 * 年排行榜
 *
 * @package Zqhong\RedisRanking\Ranking
 */
class YearlyRanking extends Ranking
{

    /**
     * 获取当前排行榜在 sorted set 中的 key 值
     *
     * @return string
     */
    public function getRankingKey()
    {
        return sprintf('%s:year:%d', $this->rankingName, date('Y'));
    }

    /**
     * @param array $item
     * @return boolean
     */
    protected function ignore(array $item)
    {
        $start = (new Carbon())->startOfYear()->timestamp;
        $end = (new Carbon())->lastOfYear()->timestamp + 24 * 60 * 60 - 1;
        $itemCreatedAt = $item['created_at'];

        return ($itemCreatedAt >= $start && $itemCreatedAt <= $end) ? false : true;
    }

    /**
     * 获取排行榜的过期时间，仅在大于0的时候有效
     *
     * @return integer
     */
    public function getExpiredAt()
    {
        return (new Carbon())->lastOfYear()->timestamp + 24 * 60 * 60 - 1;
    }
}