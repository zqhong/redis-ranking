<?php

namespace Zqhong\RedisRanking\Ranking;

use Carbon\Carbon;

/**
 * 季度排行榜
 *
 * @package Zqhong\RedisRanking\Ranking
 */
class QuarterlyRanking extends Ranking
{

    /**
     * 获取当前排行榜在 sorted set 中的 key 值
     *
     * @return string
     */
    public function getRankingKey()
    {
        return sprintf('%s:quarter:%s', $this->rankingName, date('Y') . date_quarter());
    }

    /**
     * @param array $item
     * @return boolean
     */
    protected function ignore(array $item)
    {
        $start = (new Carbon())->firstOfQuarter()->timestamp;
        $end = (new Carbon())->lastOfQuarter()->timestamp;

        $itemCreatedAt = $item['created_at'];
        return ($itemCreatedAt >= $start && $itemCreatedAt <= $end) ? false : true;
    }
}