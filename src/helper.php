<?php

if (function_exists('date_quarter')) {
    /**
     * 计算 $month 是属于哪个季度的
     *
     * @param integer $month
     * @return int
     */
    function date_quarter($month)
    {
        if ($month <= 3) return 1;
        if ($month <= 6) return 2;
        if ($month <= 9) return 3;

        return 4;
    }
}
