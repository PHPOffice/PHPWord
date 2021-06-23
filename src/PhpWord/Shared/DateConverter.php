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
        if (new \DateTime(date('Y-m-d', $categories[0])) == new \DateTime(date('Y-m-d', $categories[1]))) {
            $isTime = true;
        }

        foreach ($categories as &$date) {
            //в случаи если берем выгрузку за 1 день приводим количество дней в виде дестичной дроби
            // чтобы получить нужное время (1.5 = 01.01.1900 12:00)
            if ($isTime) {
                $h = (int) date('H', $date);
                $m = (int) date('i', $date);
                if ($h != 0) {
                    $m += $h * self::MINUTES_PER_HOUR;
                }
                $dayPart = $m / self::MINUTES_PER_DAY;
            }

            $diff = date_diff(
                new \DateTime(date('Y-m-d', -2208997817)),
                new \DateTime(date('Y-m-d', $date))
            );

            $date = $diff->days + $dayPart;
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
}