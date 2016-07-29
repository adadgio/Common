<?php

namespace Adadgio\Tests;

use Adadgio\Common\HumanDate;

class HumanDateTest extends \PHPUnit_Framework_TestCase
{
    public function testDates()
    {
        $referencialDate = new \DateTime('2016-07-29 12:00:00');

        $dataset = array(
            array(
                'date'      => '2016-07-29 09:00:00',
                'result'    => '09:00',
            ),
            array(
                'date'      => '2016-07-28 22:00:00',
                'result'    => 'Yesterday',
            ),
            array(
                'date'      => '2016-06-29 09:00:00',
                'result'    => '29/06/2016',
            ),
            array(
                'date'      => '2016-07-29 00:00:00',
                'result'    => '00:00',
            ),
            array(
                'date'      => '2016-07-29 11:59:50',
                'result'    => '10 seconds',
            ),
            array(
                'date'      => '2016-07-29 11:59:01',
                'result'    => '59 seconds',
            ),
            array(
                'date'      => '2015-06-29 11:59:00',
                'result'    => '29/06/2015',
            ),
            array(
                'date'      => '2016-07-29 12:00:00',
                'result'    => 'Now',
            ),
            array(
                'date'      => '2016-07-29 11:59:55',
                'result'    => '5 seconds',
            ),
            array(
                'date'      => '2016-09-30 11:59:40',
                'result'    => '30/09/2016',
            ),
            array(
                'date'      => '2016-07-29 11:59:00',
                'result'    => '11:59',
            ),
        );

        foreach ($dataset as $value) {
            $this->assertEquals(HumanDate::format(new \DateTime($value['date']), $referencialDate), $value['result']);
        }
    }

}
