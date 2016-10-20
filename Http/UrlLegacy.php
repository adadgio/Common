<?php

namespace Adadgio\Common\Http;

// the old Medical\SourceBundle\Component\Parser\Helper\Url
// Step 1: unit test IT  (every method)
// Step 2, create the new url class, unit test IT (every method)
// Step3 replace methods safely inside 360medical
class UrlLegacy
{
    /**
     *
     */
    private $url;

    /**
     *
     */
    private $host;

    /**
     *
     */
    private $protocol;

    /**
     *
     */
    public function __construct($url)
    {
        $this->url = trim($url, '/');
        $schema    = parse_url($this->url);

        $this->host = trim($schema['host'], '/');
        $this->protocol = $schema['scheme'];
    }

    /**
     * @return {string} Base input url backslase(s) trimed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return {string} Concatenation of protocol and domain
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return {string} Domain without the protocol
     */
    public function getDomain()
    {
        return $this->protocol. '://' . $this->host;
    }

    /**
     * @return {string} Http or https protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }
}
