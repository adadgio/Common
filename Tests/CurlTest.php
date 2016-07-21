<?php

// -----------------------------------------------------
// ------------------- Please note ---------------------
// It's not unit tests because response of Curl request
// depending of the server (status, page loaded...)
// These tests let to know if all methods of Curl class work
// -----------------------------------------------------
// -----------------------------------------------------

namespace Adadgio\Tests;

use Adadgio\Common\Curl;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    public function testCurlGet()
    {
        $url = 'http://example.com/';

        $curl = new Curl();
        $response = $curl->get($url);
    }

    public function testCurlPost()
    {
        $url = 'http://example.com/';

        $curl = new Curl();
        $response = $curl->post($url);

    }

    public function testComplexCurlPost()
    {
        $url = 'http://example.com/';

        $params = array(
            'blue'      => 'green',
            'yellow'    => 'red',
            'white'     => 'black',
        );

        $curl = new Curl();
        $response = $curl->setUrl($url)
            ->setParams($params)
            ->addParam('key','foo')
            ->addOption(CURLOPT_SSL_VERIFYPEER, true)
            ->setContentType('application/json')
            ->setAuthorization('user', 'pass')
            ->setAuthentication('username', 'password')
            ->setCookie()
            ->setRandomUserAgent()
            ->silence(true)
            ->post();
    }
}
