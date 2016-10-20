<?php

namespace Adadgio\Common\Moment;

class Since
{
    const NOW = 'Now';
    const YESTERDAY = 'Yesterday';
    
    const FORMAT_EN = 'Y/m/d';
    const FORMAT_FR = 'd/m/Y';

    /**
     * Formats a date to know since when it origins from. Show classic
     * data when this is more than yesterday.
     *
     * @param  \DateTime The date to be transformed
     * @param  \DateTime A refernce date, should be always "null" or "now" unless you are unit testing
     * @return Formatted date
     */
    public static function format(\DateTime $datetime, $defaultFormat = 'd/m/Y', \DateTime $referentialDate = null)
    {
        // no referential date? Use "now" date as referential date
        if(null == $referentialDate) { $referentialDate = new \DateTime(); }
        $referentialMidnight = new \DateTime($referentialDate->format('Y-m-d'));

        $datetimeMidnight = new \DateTime($datetime->format('Y-m-d'));

        // yesterday (base on the midnight!)
        if ((int)$referentialMidnight->diff($datetimeMidnight)->format("%R%a") == -1) {
            return self::YESTERDAY;
        }

        // just now
        elseif ($datetime->getTimestamp() - $referentialDate->getTimestamp() == 0) {
            return self::NOW;
        }

        // today and less than few seconds (<60 seconds)
        elseif ($referentialDate->getTimestamp() - $datetime->getTimestamp() < 60 && $referentialDate->diff($datetime)->format("%R%a") == 0) {
            return $datetime->diff($referentialDate)->format("%s") . ' seconds';
        }

        // today and >= 1 min display hour
        elseif ($referentialDate->getTimestamp() - $datetime->getTimestamp() >= 60 && $referentialDate->diff($datetime)->format("%R%a") == 0) {
            //date is less than 24h with referential and > 1 min
            return $datetime->format('H:i');
        }

        // older than yesterday or in the future
        return $datetime->format($defaultFormat);
    }

}
