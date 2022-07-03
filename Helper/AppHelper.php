<?php
use Carbon\Carbon;
    function fdate($value, $format = null)
    {
        if ($value == '') {
            return '';
        }

        if ($format == null) {
            $format = 'Y-m-d';
        }
        return \Carbon\Carbon::parse($value)->format($format);
    }

    function inWords($amount)
    {
        $converter = new \App\Services\NumberToWordConverter();

        $number = number_format($amount, 2);

        $numbers = explode(".", $number);


        $words = $converter->convert_number(str_replace(",", "", $numbers[0])) . ' Taka';

        if ($numbers[1] > 0) {
            $words = $words . ' and ' . $converter->convert_number(str_replace(",", "", $numbers[1])) . ' Poysa';
        }
        return $words . ".";
    }

    function getValidMobileNumber($number = null)
    {
        if (strlen($number) == 13) {
            return $number;
        } else if (strlen($number) < 13) {
            return '8801' . substr($number, -9);
        }
        return $number;
    }



