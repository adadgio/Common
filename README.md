# AdadgioCommonBundle

## Installation

Install with composer

```bash
composer require adadgio/common-bundle
```

# Curl

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

# Urlizer

Help to check if an URL is relative or aboslute, normalise an URL, get protocol, check protocol less...

All methods are commented in [Urlizer.php](Urlizer.php)


# ParamResolver

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