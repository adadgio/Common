<?php

namespace Adadgio\Common\Http;

class Url
{
    const HTTP = 'http://';
    const HTTPS = 'https://';

    /**
     * Normalizes a relative or absolute link.
     *
     * @param string
     * @return string
     */
    public static function normalize($link, $referer)
    {
        $host = self::removeProtocol(self::rtrim($referer));
        $protocol = self::getProtocol($referer, '://');

        if (self::isRelative($link)) {
            $link = $protocol.$host.'/'.self::ltrim($link);
        } else {

            if (self::isProtocolLess($link)) {
                $link = $protocol.self::removeProtocol($link);
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
        return str_replace(array('http://', 'https://', '//'), '', $url);
    }

    /**
     * Get protocol from url.
     *
     * @param  string
     * @return string
     */
    public static function getProtocol($url, $with = null)
    {
        return parse_url($url, PHP_URL_SCHEME).$with;
    }

    /**
     * Get domain host from url.
     *
     * @param  string
     * @return string
     */
    public static function getHost($url)
    {
        return self::getDomain($url);
    }

    /**
     * Get domain from url.
     *
     * @param  string
     * @return string
     */
    public static function getDomain($url)
    {
        return parse_url($url, PHP_URL_HOST);
    }

    /**
     * Test if a link is a relative anchor link.
     *
     * @param  string
     * @return boolean
     */
    public static function isAnchor($url)
    {
        if (strpos($url, '#') > -1) {
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
    public static function isProtocolLess($url)
    {
        if (preg_match('~^\/\/~i', $url)) {
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
    public static function isRelative($url)
    {
        if (preg_match('~^https?:\/\/~i', $url) OR preg_match('~^\/\/~i', $url)) {
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
    public static function isAbsolute($url)
    {
        return !self::isRelative($url);
    }

    /**
     * @param string url
     * @param boolean
     */
    public static function isRemote($url)
    {
        $parsed = parse_url($url);

        return (
            isset($parsed['scheme'])
            && in_array($parsed['scheme'], array('http', 'https', 'ftp', 'sftp'))
        ) ? true : false;
    }
}
