<?php

namespace Zqhong\RedisRanking\Ranking;

use Carbon\Carbon;

/**
 * 日排行榜
 *
 * @package Zqhong\RedisRanking
 */
class DailyRanking extends Ranking
{
    /**
     * 忽略不在当天内的数据
     *
     * @param array $item
     * @return bool
     */
    public function ignore(array $item)
    {
        $start = (new Carbon('today'))->timestamp;
        $end = (new Carbon('tomorrow'))->timestamp;
        $itemCreatedAt = $item['created_at'];

        return ($itemCreatedAt >= $start && $itemCreatedAt < $end) ? false : true;
    }

    public function getRankingKey()
    {
        return sprintf('%s:day:%s', $this->rankingName, date('Ymd'));
    }

    /**
     * 获取排行榜的过期时间，仅在大于0的时候有效
     *
     * @return integer
     */
    public function getExpiredAt()
    {
        return (new Carbon('tomorrow'))->timestamp;
    }
}