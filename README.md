[![Packagist](https://img.shields.io/packagist/dt/afiqiqmal/Rpclient.svg)](https://packagist.org/packages/afiqiqmal/Rpclient)
[![Packagist](https://img.shields.io/packagist/v/afiqiqmal/Rpclient.svg)](https://packagist.org/packages/afiqiqmal/Rpclient)

# Raudhah Pay Client Library using PHP Framework

It is simple wrapper class written in php to ease use of [RaudhahPay Payment Gateway](https://www.raudhahpay.com/) 

## Directory
* [Installation](#installation)
* [Usages](#usages)

## Installation

### Composer
```
composer require afiqiqmal/rpclient
```
Alternatively, you can specify as a dependency in your project's existing composer.json file
```
{
   "require": {
      "afiqiqmal/rpclient": "^1.0"
   }
}
```


## Usages
After installing, you need to require Composer's autoloader and add your code.

Setup config
```$xslt
$config = [
    'api_key' => getenv('RAUDHAH_API_KEY'),
    'signature_key' => getenv('RAUDHAH_X_SIGNATURE')
];
```

Or use Laravel config file name it as `raudhahpay.php` and leave `make()` blank
```
return [
    'api_key' => env('RAUDHAH_API_KEY'),
    'signature_key' => env('RAUDHAH_X_SIGNATURE', null),
    'is_sandbox' => env('RAUDHAH_SANDBOX', env('APP_ENV') != 'production'),
];

```


### Create collection
```$xslt
RaudhahPay::make()
    ->collection()
    ->create("Collection Name");
```


### Create Bill
```$xslt
RaudhahPay::make()
    ->bill()
    ->makeBill("COLLECTION CODE")
    ->setCustomer("Amirul", "Amirul", "seed.email93@gmail.com", "60123456789", "Melaka")
    ->setReference("Testing")
    ->setProduct("Product 1", 10.30, 1)
    ->create();
```

### Create product
```$xslt
RaudhahPay::make()
    ->product()
    ->create(string|array $title/$arrays, string $code, string $description, $price);
```

### Get products
```$xslt
RaudhahPay::make()
    ->product()
    ->getList();
```

### Create customer
```$xslt
RaudhahPay::make()
    ->customer()
    ->create(string|array $firstName/$arrays, string $lastName = null, string $phoneNumber = null, string $email = null);
```

### Get customers
```$xslt
RaudhahPay::make()
    ->customer()
    ->getList();
```

### DirectPay Payee
```
$response = RaudhahPay::make()
    ->directPay()
    ->payee("COLLECTION CODE")
    ->getDirectPays();

```

### DirectPay Payeer
```
$response = RaudhahPay::make()
    ->directPay()
    ->payee("COLLECTION CODE")
    ->getTransactions($direct_pay_payer_code);

```

### Check checksum from Redirect/Webhook
```$xslt
RaudhahPay::make()->isCheckSumValid($payload); //boolean
```

## Source
[Raudhah Pay Docs](https://documenter.getpostman.com/view/9723080/SWE57zKG?version=latest)

## Todo
- Other Raudhah Pay features. Still under development

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT)
