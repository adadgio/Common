<?php

namespace Adadgio\Tests;

use Adadgio\Common\Http\UrlLegacy;

class UrlLegacyTest extends \PHPUnit_Framework_TestCase
{
    public function testLegacyUrlMethods()
    {
        $urlObject = new UrlLegacy('https://www.google.com/truc-much/?rien=46&test=4#page-anchor');

        $this->assertEquals($urlObject->getUrl(), 'https://www.google.com/truc-much/?rien=46&test=4#page-anchor');
        $this->assertEquals($urlObject->getHost(), 'www.google.com');
        $this->assertEquals($urlObject->getDomain(), 'https://www.google.com');
        $this->assertEquals($urlObject->getProtocol(), 'https');
    }
}
