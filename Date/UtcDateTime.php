<?php

namespace Adadgio\Common\Date;

use \DateTime;
use \DateTimeZone;

class UtcDateTime extend DateTime {

    public static NOW = 'now';
    
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, new DateTimeZone('UTC'));
    }
}
