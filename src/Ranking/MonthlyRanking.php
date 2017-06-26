<?php

namespace Zqhong\RedisRanking\Ranking;

use Carbon\Carbon;

/**
 * 月排行榜
 *
 * @package Zqhong\RedisRanking\Ranking
 */
class MonthlyRanking extends Ranking
{

    /**
     * 获取当前排行榜在 sorted set 中的 key 值
     *
     * @return string
     */
    public function getRankingKey()
    {
        return sprintf('%s:month:%s', $this->rankingName, date('Ym'));
    }

    /**
     * @param array $item
     * @return boolean
     */
    protected function ignore(array $item)
    {
        $start = (new Carbon())->startOfMonth()->timestamp;
        $end = (new Carbon())->endOfMonth()->timestamp;
        $itemCreatedAt = $item['created_at'];

        return ($itemCreatedAt >= $start && $itemCreatedAt <= $end) ? false : true;
    }
}