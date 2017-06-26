<?php

if (function_exists('date_quarter')) {
    function date_quarter()
    {
        $month = date('n');

        if ($month <= 3) return 1;
        if ($month <= 6) return 2;
        if ($month <= 9) return 3;

        return 4;
    }
}
