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
//    ->makeBill()
//    ->setCustomer("Amirul", "Amirul", "seed.email93@gmail.com", "60123456789", "Melaka")
//    ->setReference("Testing")
//    ->setProduct("Product 1", 10.30, 1)
//    ->create("GU0O6HT7")
//    ->output();

//$response = RaudhahPay::make()
//    ->collection()
//    ->create("Collection Name");

$response = RaudhahPay::make([
    'api_key' => getenv('RAUDHAH_API_KEY'),
    'signature_key' => getenv('RAUDHAH_X_SIGNATURE')
])
    ->directPay()
    ->payee("GU0O6HT7")
    ->getDirectPays();


header('Content-type: application/json');
echo $response;
