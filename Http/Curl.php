<?php

namespace Adadgio\Common\Http;

use \Adadgio\Common\Http\UserAgent;

class Curl
{
    const TEXT = 'text';
    const JSON = 'json';
    const XML = 'xml';
    const FORM_MULTIPART = 'form_multipart';
    const FORM_URLENCODED= 'form_urlencoded';

    /**
     * Defaut curl options
     */
    protected $defaultOptions = array(
        CURLOPT_RETURNTRANSFER  => 1,
        CURLOPT_FOLLOWLOCATION  => 1,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:11.0) Gecko/20100101 Firefox/11.0',
    );

    /**
     * Default supported content types
     */
    private $contentTypes = array(
        XML => 'application/xml',
        TEXT => 'text/plain',
        JSON => 'application/json',
        FORM_MULTIPART => 'multipart/form-data',
        FORM_URLENCODED => 'application/x-www-form-urlencoded',
    );

    /**
     * @var Contains the Curl request lifecycle and options
     */
    protected $curl;

    /**
     * @var integer
     */
    protected $code;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var string
     */
    private $contentType;

    /**
     * Class contructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();
        $this->headers = array();
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getError()
    {
        return $this->error;
    }

    public function get($url, array $getParams = array())
    {
        // concat http get string if
        if (!empty($getParams)) {
            $url .= '?'.http_build_query($getParams);
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);

        $result = curl_exec($this->curl);
        $code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $error = curl_error($this->curl);
        curl_close($this->curl);

        return $this->handleResponse($result, $code, $error);
    }

    public function post($url, array $postFields = array())
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);

        // post field must be differently formated
        // depending on content type
        switch ($this->contentType) {
            case static::JSON:
                $postFields = json_encode($postFields);
            break;
            case static::FORM_URLENCODED:
                $postFields = http_build_query($postFields);
            break;
            case static::FORM_MULTIPART:
                // post fields stay as array here
            break;
            default:
                // nothing special
            break;
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);

        $result = curl_exec($this->curl);
    }

    private function handleResponse($result, $code, $errors)
    {
        $response = null;
        $this->code = $code;
        $this->error = $error;

        switch ($this->contentType) {
            case static::JSON:
                $response = json_decode($result, true);
            break;
            default:
                $response = $result;
            break;
        }

        return $response;
    }

    public function verifyHost($bool)
    {
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, $bool);

        return $this;
    }

    public function verifyPeer($bool, $cacertPath = null)
    {
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, $bool);
        
        if (!is_null($cacertPath)) {
            if (!is_file($cacertPath)) { throw new \Exception("Wrong path to certificate, {$cacertPath} does not exist"); }
            curl_setopt($this->curl, CURLOPT_CAINFO, $cacertPath);
        }

        return $this;
    }

    public function setUserAgent($random = true)
    {
        // @todo
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        switch ($this->contentType) {
            case static::JSON:
                $this->addHeader('Content-Type: application/json');
            break;
            case static::TEXT:
                $this->addHeader('Content-Type: text/plain');
            break;
            case static::XML:
                $this->addHeader('Content-Type: application/xml');
            break;
            case static::FORM_URLENCODED:
                $this->addHeader('Content-Type: application/x-www-form-urlencoded');
            break;
            case static::FORM_MULTIPART:
                $this->addHeader('Content-Type: multipart/form-data');
            break;
            default:
                // no specific headers to add
            break;
        }

        return $this;
    }

    public function setCookies($bool = true)
    {
        if (true === $bool) {
            curl_setopt($this->curl, CURLOPT_COOKIESESSION, 1);
            curl_setopt($this->curl, CURLOPT_COOKIEJAR, 'cookie.txt');
            curl_setopt($this->curl, CURLOPT_COOKIEFILE, 'cookie.txt');
        }

        return $this;
    }

    public function setAuthorization($type, $token)
    {
        $this->addHeader('Authorization: '.$type.'='.$token);

        return $this;
    }

    public function setAuthorizationBasic($user, $pass)
    {
        $this->addHeader('Authorization: ' . base64_encode($user . ':' . $pass));

        return $this;
    }

    public function addOption($name, $value)
    {
        curl_setopt($this->curl, $name, $value);

        return $this;
    }

    public function addHeader($headerStr)
    {
        if (!is_string($headerStr)) {
            throw new \Exception("Header must be a string");
        }

        if (!in_array($headerStr, $this->headers)) {
            $this->headers[] = $headerStr;
        }

        return $this;
    }
}
