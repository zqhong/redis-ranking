<?php

namespace Zqhong\RedisRanking;

/**
 * Interface DataSourceInterface
 *
 * @package Zqhong\RedisRanking
 */
interface DataSourceInterface
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
    public function get($lastId, $fetchNum);
}