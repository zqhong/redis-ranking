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
     * 当 $withScores 的值为 true 时，返回值为：
     * [
     *  'member1' => 'score1',
     *  'member2' => 'score2',
     * ]
     *
     * 当 $withScores 的值为 false 时，返回值为：
     * [
     *  'member1',
     *  'member2'
     * ]
     * @param integer $num
     * @param bool $withScores
     * @return array
     */
    public function top($num, $withScores = true)
    {
        $num = (int)$num;

        if ($num <= 0) {
            throw new \InvalidArgumentException('num param must great than zero.');
        }

        return $this->redisClient->zrevrange($this->getRankingKey(), 0, $num - 1, [
            'withscores' => $withScores
        ]);
    }

    /**
     * 获取 $memberName 的排行
     *
     * @param string $memberName
     * @return int|null 如果不存在该用户的排名数据，返回 null。否则，返回具体的排名（整形）。
     */
    public function rank($memberName)
    {
        $memberRanking = $this->redisClient->zrevrank($this->getRankingKey(), $memberName);

        if (is_null($memberRanking)) {
            return null;
        } else {
            return $memberRanking + 1;
        }
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
     * @return integer|null 如果不存在该用户的数据，返回 null。否则，返回具体的分数。
     */
    public function score($memberName)
    {
        $memberScore = $this->redisClient->zscore($this->getRankingKey(), $memberName);

        if (is_null($memberScore)) {
            return null;
        } else {
            return (int)$memberScore;
        }
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