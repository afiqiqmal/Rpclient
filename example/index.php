<?php


use Afiqiqmal\Rpclient\RaudhahPay;

require_once __DIR__ .'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();


//$response = RaudhahPay::make([
//    'api_key' => getenv('RAUDHAH_API_KEY'),
//    'signature_key' => getenv('RAUDHAH_X_SIGNATURE')
//])
//    ->bill()
//    ->makeBill("N09HEFP1")
//    ->setCustomer("Amirul", "Amirul", "seed.email93@gmail.com", "60123456789", "Melaka")
//    ->setReference("Testing")
//    ->setProduct("Product 1", 10.30, 1)
//    ->create();

//$response = RaudhahPay::make([
//    'api_key' => getenv('RAUDHAH_API_KEY'),
//    'signature_key' => getenv('RAUDHAH_X_SIGNATURE')
//])
//    ->collection()
//    ->create("Tsting123444");

$response = RaudhahPay::make([
    'api_key' => getenv('RAUDHAH_API_KEY'),
    'signature_key' => getenv('RAUDHAH_X_SIGNATURE')
])
    ->customer()
    ->getList();

//$response = RaudhahPay::make([
//    'api_key' => getenv('RAUDHAH_API_KEY'),
//    'signature_key' => getenv('RAUDHAH_X_SIGNATURE')
//])
//    ->directPay()
//    ->payee("GU0O6HT7")
//    ->getDirectPays();


header('Content-type: application/json');
echo $response;
