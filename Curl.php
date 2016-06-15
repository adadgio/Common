<?php

namespace Adadgio\Common;

class Curl
{
    /**
     * Default user agent.
     */
    const DEFAULT_USERAGENT = 'Adadgio/1.0 Friendly spider (Version 1.0)';

    /**
     * @var integer Http status code.
     */
    protected $code;

    /**
     * @var string Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $headers;

    /**
     * Class contructor.
     */
    public function __construct()
    {
        $this->headers = array();
    }

    /**
     * Get request status code result.
     *
     * @return integer Http status code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get request status code result.
     *
     * @return integer Http status code
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set headers.
     *
     * @param  array Headers
     * @return \Curl
     */
    public function setHeaders(array $headers = array())
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Curl GET request.
     *
     * @param  string  Remote url
     * @return [string Response
     */
    public function get($url)
    {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL             => $url,
            CURLOPT_USERAGENT       => static::DEFAULT_USERAGENT,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_RETURNTRANSFER  => true,
            //CURLOPT_SSL_VERIFYPEER  => false,
            //CURLOPT_CONNECTTIMEOUT  => 12,
            //CURLOPT_TIMEOUT         => 12,
        ));

        $this->response = curl_exec($ch);
        $this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this;
    }

    /**
     * Curl POST request.
     *
     * @param  string  Remote url
     * @return [string Response
     */
    public function post($url, array $data = array())
    {
        $ch = curl_init();

        // post data can must be json encoded if content type is json
        if (in_array('Content-Type: application/json', $this->headers)) {
            $data = json_encode($data);
        }

        curl_setopt_array($ch, array(
            CURLOPT_URL             => $url,
            CURLOPT_POST            => true,
            CURLOPT_USERAGENT       => static::DEFAULT_USERAGENT,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $this->headers,
            CURLOPT_POSTFIELDS      => $data,
            //CURLOPT_SSL_VERIFYPEER  => false,
            //CURLOPT_CONNECTTIMEOUT  => 12,
            //CURLOPT_TIMEOUT         => 12,
        ));


        $this->response = curl_exec($ch);
        $this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this;
    }
}
