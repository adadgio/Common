# AdadgioCommonBundle

## Installation

Install with composer

```bash
composer require adadgio/common
```

## Table of contents

1. [Curl](#curl)
2. [Url](#url-helper)
3. [ParamResolver](#param-resolver)
4. [JsonResponse](#json-response)

## <a name="curl"></a>Curl

To do Curl requests (get or post) and return results

```php
use Adadgio\Common\Http\Curl;


// GET request
$curl = new Curl();
$response = $curl
    ->get('http://example.com', array('query_param_1' => 'query param value'));


// or POST request with params
$curl = new Curl();
$response = $curl
    ->setContentType(Curl::JSON) // other options are JSON|XML|TEXT|FORL_URLENCODED|FORM_MULTIPART
    ->post('http://example.com', array('post_field_1' => 'psot field value'));

// POST request with more options
$curl = new Curl();
$response = $curl
    ->setContentType(Curl::JSON) // other options are JSON|XML|TEXT|FORL_URLENCODED|FORM_MULTIPART
    ->setAuthorizationBasic('Token', 'user', 'pass') // or ->setAuthorization('Basic', base64_encode('user:pass'))
    // or ->setAuthorization('Token', yO3my4To3en)
    ->setCookies(true)
    // ->addHeader('X-Custom-Header', 'CUst0mApiK3y')
    ->verifyHost(false) // or ->addOption(CURLOPT_SSL_VERIFYHOST, false)
    ->verifyPeer(true, "/home/my/server/cacert.pem") // second param is empty but you should set it in php.ini
    ->post('http://example.com', array('post_field_1' => 'psot field value'));
```

## <a name="url-helper"></a>UrlHelper

Help to check if an URL is relative or aboslute, normalise an URL, get protocol, check protocol less...

All methods are commented in [UrlHelper.php](UrlHelper.php)


## <a name="param-resolver"></a>ParamResolver

Two methods let to know if value is a valid array or valid integer (>0). If it's not the case, return default value parameter.

```php
use Adadgio\Common\ParamResolver;

$arrayToTest = array(
    'one'    => true,
    'barbar' => 29393,
    'first'  => 'foo',
);

$defaultArray = array(
    'one'     => false,
    'second'  => 'yolo',
);

$array = ParamResolver::toArray($arrayToTest, $defaultArray);
```

```php
use Adadgio\Common\ParamResolver;

$integer = ParamResolver::toInt(430, 0);
```

## <a name="json-response"></a>JsonResponse

A custom easy response object handler to respond with JSON data

```php
use Adadgio\Common\JsonResponse;

// $data is an object or an array
$data = array('yes' => 'no', 'bar' => 'foo');

// default response code is 200
$code = 200;

// specific headers, default: array()
$headers = array();

$array = JsonResponse::fire($data, $code, $headers);
```

## <a name="human-date"></a>Since

This tool return an human date to display, like few some seconds...
Default referential is the current date and time, a custom referential can be set in second parameter.

```php
use Adadgio\Common\Moment\Since;
// Examples with referential date: "2016-07-29 12:00:00"

Since::format(new \DateTime('2016-06-29 09:00:00'));
// 09:00

Since::format(new \DateTime('2016-07-28 22:00:00'));
// Yesterday

Since::format(new \DateTime('2016-07-29 12:00:00'));
// Now

Since::format(new \DateTime('2016-07-29 11:59:55'));
// 5 seconds

```
