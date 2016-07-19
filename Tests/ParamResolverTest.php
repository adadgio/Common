<?php

namespace Adadgio\Tests;

use Adadgio\Common\ParamResolver;

class ParamResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testParamResolverArray()
    {
        $dataset = array(
            array(
                'data' => array(
                    'one'    => true,
                    'barbar' => 29393,
                    'yes'    => 'bar',
                    'first'  => 'foo',
                ),
                'default' => array(
                    'yes' => 'go'
                ),
                'result' => array(
                    'one'    => true,
                    'barbar' => 29393,
                    'yes'    => 'bar',
                    'first'  => 'foo',
                )
            ),
            array(
                'data' => null,
                'default' => array(
                    'yes' => 'go'
                ),
                'result' => array(
                    'yes' => 'go'
                )
            ),
            array(
                'data' => array(),
                'default' => array(
                    'expression'    => false,
                    'regular'       => 29393,
                    'no'            => 'foo',
                ),
                'result' => array(
                    'expression'    => false,
                    'regular'       => 29393,
                    'no'            => 'foo',
                )
            ),
            array(
                'data' => array(),
                'result' => array()
            ),
            array(
                'data' => 'no',
                'result' => array('no')
            ),
        );

        foreach ($dataset as $value) {
            if(!empty($value['default'])) {
                $this->assertEquals(ParamResolver::toArray($value['data'], $value['default']), $value['result']);
            }
            else {
                $this->assertEquals(ParamResolver::toArray($value['data']), $value['result']);
            }
        }

    }

    public function testParamResolverInt()
    {
        $dataset = array(
            array(
                'data' => 4,
                'default' => 9,
                'result' => 4
            ),
            array(
                'data' => 483,
                'default' => 0,
                'result' => 483
            ),
            array(
                'data' => 0,
                'default' => 23,
                'result' => 0
            ),
            array(
                'data' => 'no',
                'default' => 23,
                'result' => 23
            ),
            array(
                'data' => 'no',
                'result' => null
            ),
            array(
                'data' => -29,
                'default' => 'default',
                'result' => 'default'
            ),
        );

        foreach ($dataset as $value) {
            if(!empty($value['default'])) {
                $this->assertEquals(ParamResolver::toInt($value['data'], $value['default']), $value['result']);
            }
            else {
                $this->assertEquals(ParamResolver::toInt($value['data']), $value['result']);
            }
        }
    }


}
