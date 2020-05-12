<?php

namespace GodSpeed\Essentials\Utils;

use Carbon\Carbon;

class VideoDurationFormatter
{
    public static function toSeconds($duration)
    {
        $group_digit = explode(":", $duration);

        $ISO8601Interval = self::getISO8601Format($group_digit);

        trace_log($ISO8601Interval);
        if (!is_null($ISO8601Interval)) {
            try {
                $dtInterval = new \DateInterval($ISO8601Interval);
                $start = Carbon::now();
                $end = Carbon::now()->addYears($dtInterval->y)
                    ->addMonths($dtInterval->m)
                    ->addDays($dtInterval->d)
                    ->addHours($dtInterval->h)
                    ->addMinutes($dtInterval->i)
                    ->addSeconds($dtInterval->s);
                return $end->diffInSeconds($start);
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    private static function getISO8601Format($groupsDigit = [])
    {

        $length = count($groupsDigit);
        $duration = [
            "d" => 0,
            "h" => 0,
            "m" => 0,
            "s" => 0
        ];

        switch ($length) {
            case 2:
                $duration['m'] = (int) $groupsDigit[0];
                $duration['s'] =  (int) $groupsDigit[1];
                $durationISO8601String = "PT".$duration['m']."M"
                                                .$duration['s']."S";
                break;
            case 3:
                $duration['h'] = (int) $groupsDigit[0];
                $duration['m'] =  (int) $groupsDigit[1];
                $duration['s'] =  (int) $groupsDigit[2];
                $durationISO8601String = "PT".$duration['h']."H"
                                            .$duration['m']."M"
                                            .$duration['s']."S";

                break;
            case 4:
                $duration['d'] = (int) $groupsDigit[0];
                $duration['h'] = (int) $groupsDigit[1];
                $duration['m'] =  (int) $groupsDigit[2];
                $duration['s'] =  (int) $groupsDigit[3];
                $durationISO8601String = "PT".$duration['d']."D"
                                        .$duration['h']."H"
                                        .$duration['m']."M"
                                        .$duration['s']."S";

                break;
            default:
                $durationISO8601String = null;
        }

        return $durationISO8601String;
    }
}
