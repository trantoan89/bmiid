<?php

namespace App;

use DateTime;

class Utils
{
    const MONTHS = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    
    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    /**
     * Convert array of objects to array of arrays.
     *
     * @param Array of stdClass $ao
     * @param Array of string $keys Keys of object
     * @return Array of array
     */
    public static function ao2aa($ao, $keys) 
    {
        $it = 0;
        $aa = [];
        foreach ($ao as &$ri)
        {
            $aa[] = [];
            foreach ($keys as $c) {                
                $aa[$it][] = $ri->{$c};
            }
            $it++;
        }
        unset($ri);
        
        return $aa;
    }
}