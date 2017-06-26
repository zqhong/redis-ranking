<?php

namespace Zqhong\RedisRanking;

use Illuminate\Support\Arr;
use Predis\Client;
use Zqhong\RedisRanking\Ranking\DailyRanking;
use Zqhong\RedisRanking\Ranking\MonthlyRanking;
use Zqhong\RedisRanking\Ranking\QuarterlyRanking;
use Zqhong\RedisRanking\Ranking\Ranking;
use Zqhong\RedisRanking\Ranking\TotalRanking;
use Zqhong\RedisRanking\Ranking\WeeklyRanking;
use Zqhong\RedisRanking\Ranking\YearlyRanking;

/**
 * Class RankingManger
 *
 * @package Zqhong\RedisRanking
 */
class RankingManger
{
    /**
     * 每次从数据源中获取的数据条数
     *
     * @var int
     */
    protected $fetchNum = 100;

    /**
     * @var Client
     */
    protected $redisClient;

    /**
     * @var DataSourceInterface
     */
    protected $dataSource;

    /**
     * @var string
     */
    protected $rankingName;


    /**
     * @var Ranking[]
     */
    protected $rankingObjects;

    /**
     * 总排行榜
     *
     * @var TotalRanking
     */
    protected $totalRanking;

    /**
     * 日排行榜
     *
     * @var DailyRanking
     */
    protected $dayRanking;

    /**
     * 周排行榜
     *
     * @var WeeklyRanking
     */
    protected $weekRanking;

    /**
     * 月排行榜
     *
     * @var MonthlyRanking
     */
    protected $monthlyRanking;

    /**
     * 季度排行榜
     *
     * @var QuarterlyRanking
     */
    protected $quarterlyRanking;

    /**
     * 年排行榜
     *
     * @var YearlyRanking
     */
    protected $yearlyRanking;

    /**
     * RankingManger constructor.
     *
     * @param string $rankingName
     * @param DataSourceInterface $dataSource
     * @param array $rankingClasses
     */
    public function __construct($rankingName, DataSourceInterface $dataSource, array $rankingClasses)
    {
        $this->rankingName = $rankingName;
        $this->dataSource = $dataSource;

        foreach ($rankingClasses as $rankingClass) {
            if (class_exists($rankingClass)) {
                $instance = new $rankingClass($this->rankingName, $this->redisClient);
                $this->rankingObjects[] = $instance;

                switch ($rankingClass) {
                    case DailyRanking::class:
                        $this->dayRanking = $instance;
                        break;
                    case MonthlyRanking::class:
                        $this->monthlyRanking = $instance;
                        break;
                    case QuarterlyRanking::class:
                        $this->quarterlyRanking = $instance;
                        break;
                    case WeeklyRanking::class:
                        $this->weekRanking = $instance;
                        break;
                    case YearlyRanking::class:
                        $this->yearlyRanking = $instance;
                        break;
                    default:
                        break;
                }
            }
        }

        $this->import();
    }

    /**
     * 循环执行 $rankingClasses 下所有元素的 add 方法
     *
     * @param string $member
     * @param integer $score
     */
    public function add($member, $score)
    {
        foreach ($this->rankingObjects as $ranking) {
            $ranking->add($member, $score);
        }
    }

    /**
     * 批量导入当前类所管理的排行类数据
     */
    public function import()
    {
        $now = time();
        $lastId = null;

        $needInitObjects = [];
        foreach ($this->rankingObjects as $ranking) {
            if (empty($this->redisClient->get($ranking->getInitKey()))) {
                $needInitObjects[] = $ranking;
            }
        }

        while(($items = $this->dataSource->get($lastId, $this->fetchNum)) != []) {
            foreach ($this->$needInitObjects as $ranking) {
                /**@var Ranking $ranking */
                $ranking->import($items);
                $this->redisClient->set($ranking->getInitKey(), $now);
            }

            $lastItem = Arr::last($items);
            $lastId = $lastItem['id'];
        }


    }
}