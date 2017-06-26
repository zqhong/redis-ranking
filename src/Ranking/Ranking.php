<?php

namespace Zqhong\RedisRanking\Ranking;

use Predis\Client;

/**
 * Class Ranking
 *
 * @package Zqhong\RedisRanking
 */
abstract class Ranking
{
    /**
     * @var Client
     */
    protected $redisClient;

    /**
     * 排行榜名称
     *
     * @var string
     */
    protected $rankingName;

    public function __construct($rankingName, Client $client)
    {
        $this->rankingName = $rankingName;
        $this->redisClient = $client;
    }

    /**
     * 导入数据
     *
     * @param array $items
     */
    public function import(array $items)
    {
        foreach ($items as $item) {
            if (!$this->ignore($item)) {
                $this->redisClient->zincrby($this->getRankingKey(), $item['score'], $item['member']);
            }
        }
    }

    /**
     * 获取 TOP x 的用户（根据分数从大到小排序）
     *
     * @param integer $num
     * @return array
     */
    public function top($num)
    {
        $num = (int)$num;

        if ($num <= 0) {
            throw new \InvalidArgumentException('num param must great than zero.');
        }

        return $this->redisClient->zrevrange(0, $num - 1, [
            'withscores' => true
        ]);
    }

    /**
     * 获取 $memberName 的排行
     *
     * @param string $memberName
     * @return int
     */
    public function rank($memberName)
    {
        $memberRanking = $this->redisClient->zrevrank($this->getRankingKey(), $memberName);

        return $memberRanking + 1;
    }

    /**
     * 添加一条分数变更记录
     *
     * @param string $member
     * @param integer $score
     * @return string
     */
    public function add($member, $score)
    {
        return $this->redisClient->zincrby($this->getRankingKey(), $score, $member);
    }

    /**
     * 获取 $memberName 的分数
     *
     * @param string $memberName
     * @return integer
     */
    public function score($memberName)
    {
        return (int)$this->redisClient->zscore($this->getRankingKey(), $memberName);
    }

    /**
     * 获取初始化键值（主要用于判断该排行榜是否导入过数据）
     *
     * @return string
     */
    public function getInitKey()
    {
        $shortClassName = (new \ReflectionClass($this))->getShortName();
        return sprintf('%s:%s:%s', $this->rankingName, $shortClassName, 'init');
    }

    /**
     * 获取当前排行榜在 sorted set 中的 key 值
     *
     * @return string
     */
    abstract public function getRankingKey();

    /**
     * 根据需要，判断是否忽略该 $item
     * @param array $item
     * @return boolean
     */
    abstract protected function ignore(array $item);
}