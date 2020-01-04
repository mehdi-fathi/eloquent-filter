<?php


namespace eloquentFilter\QueryFilter;


use Morilog\Jalali\CalendarUtils;

trait HelperFilter
{

    private $_regex_j_date = '/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/';

    public function checkDataIsJallaiDate($date)
    {

        if (is_array($date)) {
            foreach ($date as $item) {

                $item = explode(' ', $item)[0];

                if (preg_match($this->_regex_j_date, $item)) {
                    $output = true;
                } else {
                    return false;
                }
            }
        } else {
            $output = preg_match($this->_regex_j_date, $date);
        }
        return $output;

    }

    public function convertJdateToG($date)
    {
        $output = false;
        if ($this->checkDataIsJallaiDate($date)) {

            if (is_array($date)) {
                foreach ($date as $key => $item) {

                    $output[$key] = CalendarUtils::createCarbonFromFormat('Y/m/d h:i:s', $item)->format('Y-m-d h:i:s');

                }
            } else {
                $item = explode(' ', $date)[0];
                $output = preg_match($this->_regex_j_date, $item);
            }
        }
        return $output;

    }
}
