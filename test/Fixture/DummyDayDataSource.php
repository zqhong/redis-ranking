<?php

namespace Zqhong\RedisRanking\Test\Fixture;

use Carbon\Carbon;
use Zqhong\RedisRanking\DataSourceInterface;

class DummyDayDataSource implements DataSourceInterface
{

    /**
     * 获取数据源，每次返回 $fetchNum 条
     *
     * 返回格式：
     * [
     *  {
     *      "id": 1,
     *      "member": "akira",
     *      "score": 100,
     *      "created_at": 1498469135,
     *  },
     *  {
     *      "id": 2,
     *      "member": "jian",
     *      "score": 87,
     *      "created_at": 1498469136,
     *  },
     * ]
     *
     * @param integer $lastId
     * @param integer $fetchNum
     * @return array 无数据时，返回空数组
     */
    public function get($lastId, $fetchNum)
    {
        if (is_null($lastId)) {
            return [
                // 明天的数据
                [
                    'id' => 5,
                    'member' => 'yang',
                    'score' => 90,
                    'created_at' => (Carbon::tomorrow())->timestamp,
                ],
                // 昨天的数据
                [
                    'id' => 4,
                    'member' => 'kulu',
                    'score' => 80,
                    'created_at' => (Carbon::yesterday())->timestamp,
                ],
                [
                    'id' => 3,
                    'member' => 'mike',
                    'score' => 70,
                    'created_at' => (Carbon::today())->timestamp,
                ],
                [
                    'id' => 2,
                    'member' => 'jian',
                    'score' => 60,
                    'created_at' => (Carbon::today())->timestamp,
                ],
                [
                    'id' => 1,
                    'member' => 'akira',
                    'score' => 50,
                    'created_at' => (Carbon::today())->timestamp,
                ],
            ];
        } else {
            [];
        }
    }
}