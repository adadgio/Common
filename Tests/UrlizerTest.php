<?php

namespace Adadgio\Tests;

use Adadgio\Common\Urlizer;

class UrlizerTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalizeLink()
    {
        $referer = 'http://www.has-sante.fr/portail/';

        $links = array(
            '/jcms/c_2055287',
            '/jcms/c_2055287/',
            'jcms/r_1455081/Home-page',
            'jcms/c_2055287/',
            '//www.has-sante.fr/portail/jcms/fc_1249588/fr/accueil',
            'http://www.has-sante.fr/portail/jcms/fc_1249588/fr/accueil',
            'http://www.has-sante.fr/portail/jcms/fc_1249588/fr/accueil/',
            'https://www.has-sante.fr/portail/jcms/fc_1249588/fr/accueil/',
        );

        $link01 = Urlizer::normalize($links[0], $referer);
        $this->assertEquals($link01, 'http://www.has-sante.fr/portail/jcms/c_2055287');

        $link02 = Urlizer::normalize($links[1], $referer);
        $this->assertEquals($link02, 'http://www.has-sante.fr/portail/jcms/c_2055287/');

        $link03 = Urlizer::normalize($links[2], $referer);
        $this->assertEquals($link03, 'http://www.has-sante.fr/portail/jcms/r_1455081/Home-page');

        $link04 = Urlizer::normalize($links[3], $referer);
        $this->assertEquals($link04, 'http://www.has-sante.fr/portail/jcms/c_2055287/');

        $link05 = Urlizer::normalize($links[4], $referer);
        $this->assertEquals($link05, 'http://www.has-sante.fr/portail/jcms/fc_1249588/fr/accueil');

        $link06 = Urlizer::normalize($links[5], $referer);
        $this->assertEquals($link06, 'http://www.has-sante.fr/portail/jcms/fc_1249588/fr/accueil');

        $link07 = Urlizer::normalize($links[6], $referer);
        $this->assertEquals($link07, 'http://www.has-sante.fr/portail/jcms/fc_1249588/fr/accueil/');

        $link08 = Urlizer::normalize($links[7], $referer);
        $this->assertEquals($link08, 'https://www.has-sante.fr/portail/jcms/fc_1249588/fr/accueil/');
    }

    public function testProtocolLess()
    {
        $links = array(
            '/wiki/CloudFlare',
            'https://en.wikipedia.org/wiki/Internet_Assigned_Numbers_Authority',
            '#5xx_Server_Error',
            '//truc.com',
        );

        $protocolLess01 = Urlizer::isProtocolLess($links[0]);
        $this->assertEquals($protocolLess01, false);

        $protocolLess02 = Urlizer::isProtocolLess($links[1]);
        $this->assertEquals($protocolLess02, false);

        $protocolLess03 = Urlizer::isProtocolLess($links[2]);
        $this->assertEquals($protocolLess03, false);

        $protocolLess04 = Urlizer::isProtocolLess($links[3]);
        $this->assertEquals($protocolLess04, true);
    }


    public function testAnchorLink()
    {
        $links = array(
            '/wiki/CloudFlare',
            'https://en.wikipedia.org/wiki/Internet_Assigned_Numbers_Authority',
            '#5xx_Server_Error',
        );

        $isAnchor01 = Urlizer::isAnchor($links[0]);
        $this->assertEquals($isAnchor01, false);

        $isAnchor02 = Urlizer::isAnchor($links[1]);
        $this->assertEquals($isAnchor02, false);

        $isAnchor03 = Urlizer::isAnchor($links[2]);
        $this->assertEquals($isAnchor03, true);
    }

    public function testRelativeLink()
    {
        $links = array(
            '/wiki/CloudFlare',
            'https://en.wikipedia.org/wiki/Internet_Assigned_Numbers_Authority',
            '#5xx_Server_Error',
            '//es.wikipedia.org/wiki/HTTP_403',
        );

        $isRelative01 = Urlizer::isRelative($links[0]);
        $this->assertEquals($isRelative01, true, 'Test if is relative 0');

        $isRelative02 = Urlizer::isRelative($links[1]);
        $this->assertEquals($isRelative02, false, 'Test if is relative 1');

        $isRelative03 = Urlizer::isRelative($links[2]);
        $this->assertEquals($isRelative03, true, 'Test if is relative 2');

        $isRelative04 = Urlizer::isRelative($links[3]);
        $this->assertEquals($isRelative04, false, 'Test if is relative 3');
    }

    public function testDomainAndProtocol()
    {
        $referer = 'https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#1xx_Informational';

        $protocol = Urlizer::getProtocol($referer);
        $protocol2 = Urlizer::getProtocol($referer, '://');
        $domain = Urlizer::getDomain($referer);

        $this->assertEquals($protocol, 'https');
        $this->assertEquals($protocol2, 'https://');
        $this->assertEquals($domain, 'en.wikipedia.org');
    }
}
