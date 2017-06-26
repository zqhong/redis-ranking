<?php

namespace Zqhong\RedisRanking\Ranking;

use Carbon\Carbon;

/**
 * 周排行榜
 *
 * @package Zqhong\RedisRanking
 */
class WeeklyRanking extends Ranking
{
    /**
     * 忽略不在本周内的数据
     *
     * @param array $item
     * @return bool
     */
    public function ignore(array $item)
    {
        $start = (new Carbon('this monday'))->timestamp;
        $end = (new Carbon('next monday'))->timestamp;
        $itemCreatedAt = $item['created_at'];

        return ($itemCreatedAt >= $start && $itemCreatedAt <= $end) ? false : true;
    }

    public function getRankingKey()
    {
        return sprintf('%s:week:%s', $this->rankingName, date('YW'));
    }
}