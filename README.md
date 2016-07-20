# AdadgioCommonBundle

## Installation

Install with composer

```bash
composer require adadgio/common-bundle
```

Add the bundle to your app kernel.

```php
new Adadgio\GearBundle\AdadgioCommonBundle(),
```

## Table of contents

1. [Curl](#curl)
2. [Urlizer](#urlizer)
3. [ParamResolver](#param-resolver)
4. [JsonResponse](#json-response)

## <a name="curl"></a>Curl

To do Curl requests (get or post) and return results

```php
use Adadgio\Common\Urlizer\Curl;

$curl = new Curl();

// GET method
$response = $curl->get('http://example.com');

//or POST method with params
$response = $curl->post('http://example.com', array(
    'param' => 'value',
    'foo'   => 'val',
));
```

## <a name="urlizer"></a>Urlizer

Help to check if an URL is relative or aboslute, normalise an URL, get protocol, check protocol less...

All methods are commented in [Urlizer.php](Urlizer.php)


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
