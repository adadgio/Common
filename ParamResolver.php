<?php

namespace Adadgio\Common;

class ParamResolver
{
    /**
     * Check $input array parameter if is one
     * if it's not the case, use the $default array parameter.
     *
     * @param  array input to check
     * @param  default array used if input empty or null.
     * @return \ArrayCollection
     */
    public static function toArray($input, array $default = array())
    {
        if (empty($input)) {
            return $default;
        }

        if (!is_array($input)) {
            return array($input);
        } else {
            return $input;
        }
    }

    /**
     * Check $input integer parameter if is one and greater or equals to 0
     * if it's not the case, use the $default integer parameter.
     *
     * @param  integer input to check
     * @param  default integer used if input empty or null.
     * @return integer
     */
    public static function toInt($input, $default = null)
    {
        if (!is_numeric($input) OR (int) $input < 0) {
            return $default;
        } else {
            return (int) $input;
        }
    }
}
