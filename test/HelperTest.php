<?php

namespace Zqhong\RedisRanking\Test;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testDateQuarter()
    {
        // 1-3月份属于第一季度
        $this->assertEquals(1, date_quarter(1));
        $this->assertEquals(1, date_quarter(2));
        $this->assertEquals(1, date_quarter(3));

        // 4-6月份属于第二季度
        $this->assertEquals(2, date_quarter(4));
        $this->assertEquals(2, date_quarter(5));
        $this->assertEquals(2, date_quarter(6));

        // 7-9月份属于第三季度
        $this->assertEquals(3, date_quarter(7));
        $this->assertEquals(3, date_quarter(8));
        $this->assertEquals(3, date_quarter(9));

        // 10-12月份属于第四季度
        $this->assertEquals(4, date_quarter(10));
        $this->assertEquals(4, date_quarter(11));
        $this->assertEquals(4, date_quarter(12));

        // 异常情况
        $this->assertEquals(null, date_quarter(0));
        $this->assertEquals(null, date_quarter(-1));
        $this->assertEquals(null, date_quarter(13));
        $this->assertEquals(null, date_quarter(233));
        $this->assertEquals(null, date_quarter('非法月份'));
        $this->assertEquals(null, date_quarter('invalid month'));
    }
}