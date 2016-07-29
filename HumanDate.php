<?php

namespace Adadgio\Common;

/**
 * Format a PHP DateTimew to a human date format
 */
class HumanDate
{

    const YESTERDAY = 'Yesterday';
    const NOW = 'Now';

    public static function format(\DateTime $datetime, \DateTime $referentialDate = null)
    {
        //no referential date? Use now date as refertential date
        if(null == $referentialDate) { $referentialDate = new \DateTime(); }
        $referentialMidnight = new \DateTime($referentialDate->format('Y-m-d'));

        $datetimeMidnight = new \DateTime($datetime->format('Y-m-d'));

        //Yesterday (base on the midnight!)
        if ((int)$referentialMidnight->diff($datetimeMidnight)->format("%R%a") == -1) {
            return self::YESTERDAY;
        }
        // just now
        elseif ($datetime->getTimestamp() - $referentialDate->getTimestamp() == 0) {
            return self::NOW;
        }
        //Today and less than few seconds (<60 seconds)
        elseif ($referentialDate->getTimestamp() - $datetime->getTimestamp() < 60 && $referentialDate->diff($datetime)->format("%R%a") == 0) {
            return $datetime->diff($referentialDate)->format("%s") . ' seconds';
        }
        //Today and >= 1 min display hour
        elseif ($referentialDate->getTimestamp() - $datetime->getTimestamp() >= 60 && $referentialDate->diff($datetime)->format("%R%a") == 0) {
            //date is less than 24h with referential and > 1 min
            return $datetime->format('H:i');
        }

        // more than yesterday or in the future
        return $datetime->format('d/m/Y');

    }

}
