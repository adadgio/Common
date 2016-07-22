<?php

namespace Adadgio\Common;

use \Adadgio\Common\UserAgentList;

class Curl
{
    /**
     * Default user agent.
     */
    private $defaultOptions = array(
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_FOLLOWLOCATION  => true,
        CURLOPT_USERAGENT       => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:11.0) Gecko/20100101 Firefox/11.0',
    );

    /**
     * @var Contains the Curl request lifecycle and options
     */
    protected $curl;

    /**
     * @var string URL targeted
     */
    protected $url;

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
    protected $errors;

    /**
     * @var boolean silence
     */
    protected $silence;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $options;

    /**
     * Class contructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();
        $this->errors = array();
        $this->silence = false;
        $this->headers = array();
        $this->params = array();
        $this->setDefaultOptions();
    }

    /**
     * Set URL option
     *
     * @param  {string} URL to target
     * @return {object} Adadgio/Common/Curl
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->addOption(CURLOPT_URL, $url);

        return $this;
    }

    /**
     * Get request status code result.
     *
     * @return {integer} Http status code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get request response.
     *
     * @return {string} Http response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get request json response.
     *
     * @return {string} Http json response
     */
    public function getJsonResponse()
    {
        return json_decode($this->response, true);
    }

    /**
     * Get errors
     *
     * @return {array} Curl errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set silence mode to catch CURL errors.
     *
     * @param  {boolean} silence mode
     * @return {object} Adadgio/Common/Curl
     */
    public function silence($boolean)
    {
        $this->silence = $boolean;

        return $this;
    }

    /**
     * Set headers.
     *
     * @param  {array} Headers
     * @return {object} Adadgio/Common/Curl
     */
    public function setHeaders(array $headers = array())
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Add an header for the request
     *
     * @param  {string} Header to add formated like this: "XXXX: YYYY"
     * @return {object} Adadgio/Common/Curl
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;

        return $this;
    }

    /**
     * Set HTTP authentication header
     *
     * @param  {string} username
     * @param  {string} password
     * @return {object} Adadgio/Common/Curl
     */
    public function setAuthorization($user, $pass)
    {
        $this->addHeader('Authorization: ' . base64_encode($user . ':' . $pass));

        return $this;
    }

    /**
     * Set Content Type header
     *
     * @param  {string} Content-Type format
     * @return {object} Adadgio/Common/Curl
     */
    public function setContentType($format)
    {
        $this->addHeader('Content-Type: ' . $format);

        return $this;
    }

    /**
     * Set options.
     *
     * @param  {array} Options
     * @return {object} Adadgio/Common/Curl
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set default options.
     *
     * @return {object} Adadgio/Common/Curl
     */
    public function setDefaultOptions()
    {
        $this->setOptions($this->defaultOptions);

        return $this;
    }

    /**
     * Add an option for the request
     *
     * @param  {string} Option name
     * @param  {string} Option value
     * @return {object} Adadgio/Common/Curl
     */
    public function addOption($optionName, $optionValue)
    {
        $this->options[$optionName] = $optionValue;

        return $this;
    }

    /**
     * Set user agent string
     *
     * @param {string} User agent string
     * @return {object} Adadgio/Common/Curl
     */
    public function setUserAgent($userAgent)
    {
        $this->addOption(CURLOPT_USERAGENT, $userAgent);

        return $this;
    }

    /**
     * Set random user agent
     *
     * @return {object} Adadgio/Common/Curl
     */
    public function setRandomUserAgent()
    {
        $this->addOption(CURLOPT_USERAGENT, UserAgentList::getOneRandomize());

        return $this;
    }

    /**
     * Set HTTP authentication
     *
     * @param  {string} username
     * @param  {string} password
     * @return {object} Adadgio/Common/Curl
     */
    public function setAuthentication($username, $password)
    {
        $this->addOption(CURLOPT_USERPWD, $username . ':' . $password);

        return $this;
    }

    /**
     * Set Curl options to use cookies
     *
     * @param  {string} (optional) cookie file used
     * @return {object} Adadgio/Common/Curl
     */
    public function setCookie($cookieFile = "cookie.txt")
    {
        $this->addOption(CURLOPT_COOKIESESSION, true);
        $this->addOption(CURLOPT_COOKIEJAR, $cookieFile);
        $this->addOption(CURLOPT_COOKIEFILE, $cookieFile);

        return $this;
    }

    /**
     * Set params
     *
     * @param  {array} params
     * @return {object} Adadgio/Common/Curl
     */
    public function setParams(array $params = array())
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Add a param
     *
     * @param  {string} Param name to add
     * @param  {string} Param value to add
     * @return {object} Adadgio/Common/Curl
     */
    public function addParam($paramName, $paramValue)
    {
        $this->params[$paramName] = $paramValue;

        return $this;
    }


    /**
     * Curl GET request.
     *
     * @param  {string} (optional) Url targeted
     * @param  {array}  (optional) Params used in the request
     * @return {string} Server response
     */
    public function get($url = null, $params = null)
    {
        //if get method is used with $url param directly
        if (null !== $url) {
            $this->setUrl($url);
        }

        //if get method is used with $params directly
        if (!empty($params)) {
            $this->setParams($params);
        }

        // transform params array to URL params
        if (!empty($this->params)) {
            $this->setUrl($this->url . '?' . http_build_query($this->params));
        }

        $this->exec();
        return $this->response;
    }

    /**
     * Curl POST request.
     *
     * @param  {string} (optional) Url targeted
     * @param  {array}  (optional) Params used in the request
     * @return {string} Server response
     */
    public function post($url = null, array $params = array())
    {
        //if get method is used with $url param directly
        if (null !== $url) {
            $this->setUrl($url);
        }

        //if get method is used with $params directly
        if (!empty($params)) {
            $this->setParams($params);
        }

        // post data can must be json encoded if content type is json
        $data = $this->params;
        if (in_array('Content-Type: application/json', $this->headers)) {
            $data = json_encode($this->params);
        }

        $this->addOption(CURLOPT_POST, true);
        $this->addOption(CURLOPT_POSTFIELDS, $data);

        $this->exec();
        return $this->response;
    }

    /**
     * Curl execution
     */
    private function exec()
    {
        //add headers to options
        $this->addOption(CURLOPT_HTTPHEADER, $this->headers);

        // set all options
        curl_setopt_array($this->curl, $this->options);

        // execute and get response and status code returned
        $this->response = curl_exec($this->curl);
        $this->code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        // catch potential errors
        $curl_errno = curl_errno($this->curl);
        $curl_error = curl_error($this->curl);

        // if silence is on => no catch errors and get them in $errors variable
        if ($this->silence === true) {
            if ($curl_errno > 0 OR !empty($curl_error)) {
                $this->errors[] = sprintf('Curl err[%s]: %s', $curl_errno, $curl_error);
            }
        } else {
            if ($curl_errno > 0 OR !empty($curl_error)) {
                throw new \Exception(sprintf('Curl err[%s]: %s', $curl_errno, $curl_error));
            }
        }

        curl_close($this->curl);
    }
}
