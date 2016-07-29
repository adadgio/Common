# AdadgioCommonBundle

## Installation

Install with composer

```bash
composer require adadgio/common-bundle
```

## Table of contents

1. [Curl](#curl)
2. [UrlHelper](#url-helper)
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

## <a name="human-date"></a>HumanDate

This tool return an human date to display, like few some seconds...
Default referential is the current date and time, a custom referential can be set in second parameter.

```php
use Adadgio\Common\HumanDate;

// Examples with referential date: 2016-07-29 12:00:00

HumanDate::format(new \DateTime('2016-06-29 09:00:00'));
// 09:00

HumanDate::format(new \DateTime('2016-07-28 22:00:00'));
// Yesterday

HumanDate::format(new \DateTime('2016-07-29 12:00:00'));
// Now

HumanDate::format(new \DateTime('2016-07-29 11:59:55'));
// 5 seconds

```
