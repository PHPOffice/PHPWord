<?php


namespace PhpOffice\PhpWord\Shared;


class DateConverter
{

    const MINUTES_PER_DAY = 1440;
    const MINUTES_PER_HOUR = 60;

    /**
     * переводим timestamp в формат времени WS Word,
     * количество дней с 01.01.1900
     *
     * @param array $categories
     *
     * @return array $categories
     */
    public static function officeDateFormat(array $categories)
    {
        $dayPart = 0;

        $isTime = false;

        // если время приходит в милисекундах преобразуем в секунды
        $categories = array_map(function ($date){

            if (iconv_strlen($date) > 10) {
                $date = $date/1000;
            }
            return $date;
        }, $categories);

//         устанавливает метку типа формата дата/время
//        if (new \DateTime(date('Y-m-d', $categories[0])) == new \DateTime(date('Y-m-d', $categories[1]))) {
//            $isTime = true;
//        }

        foreach ($categories as &$date) {
            //добавляем 3 часа так как ворд использует UTC для


            $h = (int) date('H', $date);
            $m = (int) date('i', $date);
            if ($h != 0) {
                $m += $h * self::MINUTES_PER_HOUR;
            }
            $dayPart = $m / self::MINUTES_PER_DAY;

//            $date += 10800;

            $date = self::formattedPHPToExcel(date('Y', $date), date('m', $date), date('d', $date));
            $date +=$dayPart;
        }
        return $categories;
    }

    public static function isTime(array $date)
    {
        $date = array_map(function ($day) {
            return $day = floor($day);
        }, $date);

        return (count(array_unique($date)) < 2) ? true : false;
    }

    public static function formattedPHPToExcel($year, $month, $day, $hours = 0, $minutes = 0, $seconds = 0)
    {
        //
        //    Fudge factor for the erroneous fact that the year 1900 is treated as a Leap Year in MS Excel
        //    This affects every date following 28th February 1900
        //
        $excel1900isLeapYear = true;
        if (($year == 1900) && ($month <= 2)) {
            $excel1900isLeapYear = false;
        }
        $myexcelBaseDate = 2415020;


        //    Julian base date Adjustment
        if ($month > 2) {
            $month -= 3;
        } else {
            $month += 9;
            --$year;
        }

        //    Calculate the Julian Date, then subtract the Excel base date (JD 2415020 = 31-Dec-1899 Giving Excel Date of 0)
        $century = substr($year, 0, 2);
        $decade = substr($year, 2, 2);
        $excelDate = floor((146097 * $century) / 4) + floor((1461 * $decade) / 4) + floor((153 * $month + 2) / 5) + $day + 1721119 - $myexcelBaseDate + $excel1900isLeapYear;

        $excelTime = (($hours * 3600) + ($minutes * 60) + $seconds) / 86400;

        return (float) $excelDate + $excelTime;
    }
}