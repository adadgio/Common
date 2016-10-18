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
        'xml' => 'application/xml',
        'text' => 'text/plain',
        'json' => 'application/json',
        'form_multipart' => 'multipart/form-data',
        'form_urlencoded' => 'application/x-www-form-urlencoded',
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
        $this->configureDefaultsOptions();
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
            // note that http_build_query already encodes url/url partials
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
        curl_setopt($this->curl, CURLOPT_POST, 1);
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

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postFields);

        $result = curl_exec($this->curl);
        $code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $error = curl_error($this->curl);
        curl_close($this->curl);

        return $this->handleResponse($result, $code, $error);
    }

    private function handleResponse($result, $code, $error)
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

    public function setAuthorization($tokenTypeAndString)
    {
        $this->addHeader('Authorization: '.$tokenTypeAndString);

        return $this;
    }

    // @todo This does not seem to work, shoudl be the same as CURLOPT_USERPWD :(
    public function setBasicAuthorization($userOrToken, $pass = null)
    {
        // Note that appending a colon to your API Secret tells cURL you intentionally
        // want to leave the HTTP Basic Auth password blank. Otherwise, cURL will prompt you for a password.
        $authString = (null === $pass) ? $userOrToken.':' : base64_encode($userOrToken.':'.$pass);

        //$this->setAuthorization('Basic '.$authString);
        $this->addHeader('Authorization: Basic '.$authString);

        return $this;
    }

    public function setUserPwdAuthentication($userOrToken, $pass = null)
    {
        $authString = (null === $pass) ? $userOrToken : $userOrToken.':'.$pass;
        curl_setopt($this->curl, CURLOPT_USERPWD, $authString);

        return $this;
    }

    public function addOption($option, $value)
    {
        curl_setopt($this->curl, $option, $value);

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

    private function configureDefaultsOptions()
    {
        foreach ($this->defaultOptions as $option => $value) {
            curl_setopt($this->curl, $option, $value);
        }
    }
}
