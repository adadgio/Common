<?php

namespace Adadgio\Common\Http;

class Url
{
    const WS = 'ws://';
    const WSS = 'wss://';
    const FTP = 'ftp://';
    const FTPS = 'ftps://';
    const HTTP = 'http://';
    const HTTPS = 'https://';

    protected $url;
    protected $partials;

    public function __construct($url)
    {
        $this->url = $url;
        $this->partials = parse_url($this->url);
    }

    /**
     * Get full input url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get domain host from url.
     *
     * @param  string
     * @return string
     */
    public function getHost()
    {
        return $this->partials['host'];
    }

    /**
     * Get full input url.
     *
     * @return string
     */
    public function getPath()
    {
        return rtrim($this->partials['path'], '/').'/';
    }

    /**
     * Get full input url.
     *
     * @return string
     */
    public function getHashtag()
    {
        return $this->partials['fragment'];
    }

    /**
     * Get query as string without "?"
     *
     * @return string
     */
    public function getQueryString()
    {
        return parse_str($this->partials['query']);
    }
    
    /**
     * Get query array
     *
     * @return array
     */
    public function getQueryArray()
    {
        return $this->partials['query'];
    }

    /**
     * Get protocol from url.
     *
     * @param  string
     * @return string
     */
    public function getProtocol($with = null)
    {
        return $this->partials['scheme'];
    }

    /**
     * Get only domain name without subdomain.
     *
     * @param  string
     * @return string
     */
    public function getDomain()
    {
        $hostParts = explode('.', $this->getHost());
        array_shift($hostParts);

        return implode('.', $hostParts);
    }

    /**
     * Get full hostname domain and protocol.
     *
     * @param  string
     * @return string
     */
    public function getSchemeAndHttpHost()
    {
        return $this->getProtocol().'://'.$this->getHost();
    }

    /**
     * Normalizes a relative or absolute link.
     *
     * @param string
     * @return string
     */
    public function normalize($link, $referer)
    {
        $host = self::removeProtocol(self::rtrim($referer));
        $protocol = $this->getProtocol($referer, '://');

        if ($this->isRelative($link)) {
            $link = $protocol.$host.'/'.self::ltrim($link);
        } else {

            if ($this->isProtocolLess($link)) {
                $link = $protocol.$this->removeProtocol($link);
            }
        }

        return $link;
    }

    /**
     * Remove protocol from url string.
     *
     * @param  string
     * @return string
     */
    public static function removeProtocol($url)
    {
        return str_replace(array('http://', 'https://', '//', 'ws://', 'ftp://'), '', $url);
    }

    /**
     * Test if a link is a relative anchor link.
     *
     * @param  string
     * @return boolean
     */
    public function hasAnchor()
    {
        if (strpos($this->url, '#') > -1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes leading and trailing slashes.
     *
     * @param  string
     * @return string
     */
    public static function trim($url)
    {
        return trim($url, '/');
    }

    /**
     * Removes trailing slashe(s).
     *
     * @param  string
     * @return string
     */
    public static function rtrim($url)
    {
        return rtrim($url, '/');
    }

    /**
     * Removes leading slashe(s).
     *
     * @param  string
     * @return string
     */
    public static function ltrim($url)
    {
        return ltrim($url, '/');
    }

    /**
     * Test if a link is an absolute link but without a protocol (like "//truc.com/blabla")
     *
     * @param  string
     * @return boolean
     */
    public function isProtocolLess()
    {
        if (preg_match('~^\/\/~i', $this->url)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Test if a link is a relative link (either not protocol less or with http or https in front)
     *
     * @param  string
     * @return boolean
     */
    public function isRelative()
    {
        if (preg_match('~^https?:\/\/~i', $this->url) OR preg_match('~^\/\/~i', $this->url)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Test if a link is an absolute link (either protocol less or without http or https in front)
     *
     * @param  string
     * @return boolean
     */
    public function isAbsolute()
    {
        return !$this->isRelative($this->url);
    }

    /**
     * @param string url
     * @param boolean
     */
    public function isRemote()
    {
        $parsed = parse_url($this->url);

        return (
            isset($parsed['scheme'])
            && in_array($parsed['scheme'], array('http', 'https', 'ftp', 'sftp'))
        ) ? true : false;
    }
}
