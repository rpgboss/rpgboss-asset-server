<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 05.03.15
 * Time: 05:53
 */

class Model_Utils_Statistics {

    protected static function getDayTimestamp($range1, $range2) {
        return (time() - (24*60*60)*$range1) + (24*60*60)*$range2;
    }

    protected static function getLast7DaysByType($type, $userid, $packageid) {
        $result = array();
        for($i=1;$i<7;$i++) {
           $results = Model_Statistic::find('all',array(
                'where' => array(
                    array('created_at','>=', static::getDayTimestamp(7, 0)),
                    array('created_at','<=', static::getDayTimestamp(7, $i)),
                    'user_id' => $userid,
                    'package_id' => $packageid,
                    'type'=>$type
                )
            ));
            $result[] = count($results);
        }
        return $result;
    }

    public static function getLast7DaysDownloads($userid, $packageid)
    {
        return json_encode(static::getLast7DaysByType(1,$userid, $packageid));
    }

    public static function getLast7DaysVisits($userid, $packageid)
    {
        return json_encode(static::getLast7DaysByType(0,$userid, $packageid));
    }

    public static function getLast7DaysAsJSON()
    {
        $now = new DateTime( "7 days ago", new DateTimeZone('America/New_York'));
        $interval = new DateInterval( 'P1D'); // 1 Day interval
        $period = new DatePeriod( $now, $interval, 7); // 7 Days

        $result = array();
        foreach( $period as $day) {
            $key = $day->format( 'M d');
            $result[] = $key;
        }
        array_shift($result);
        return json_encode($result);
    }

}