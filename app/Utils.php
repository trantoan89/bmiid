<?php

namespace App;

use DateTime;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;


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
	
    /**
     * Manually paginating.
     *
     * @param  Array   $rows      Array of objects
     * @param  Number  $per_page  The number of rows per page
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public static function manPaginate($rows, $per_page) {
        $request = request();
        $total = count($rows);
        $cur_page = $request->input("page") ?? 1;
        $start_point = ($cur_page * $per_page) - $per_page;

        $rows = array_slice($rows, $start_point, $per_page, true);

        return new Paginator($rows, $total, $per_page, $cur_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

}