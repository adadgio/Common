# AdadgioCommonBundle

## Installation

Install with composer

```bash
composer require adadgio/common-bundle
```

## Table of contents

1. [Curl](#curl)
2. [UrlHelper](#urlhelper)
3. [ParamResolver](#param-resolver)
4. [JsonResponse](#json-response)

## <a name="curl"></a>Curl

To do Curl requests (get or post) and return results

```php
use Adadgio\Common\Curl;

$params = array(
    'id'    => 102,
);

// GET request
$curl = new Curl();
$response = $curl
    ->setUrl('http://example.com')
    ->setParams($params)
    ->get();

// Same GET request with a simple format
$response = $curl->get('http://example.com', $params);

// or POST request with params
$curl = new Curl();
$response = $curl->setUrl('http://example.com')
    ->setParams($params)
    ->setContentType('application/json')
    ->post();

// POST request with more options
$curl = new Curl();
$response = $curl->setUrl('http://example.com')
            ->setParams($params)
            ->addParam('foo','bar')
            ->addOption(CURLOPT_SSL_VERIFYPEER, true)
            ->setContentType('application/json')
            ->setAuthorization('username', 'pass')
            ->setCookie()
            ->setRandomUserAgent()
            ->post();
```

## <a name="urlhelper"></a>UrlHelper

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
