<?php


namespace Afiqiqmal\Rpclient;

use Afiqiqmal\Rpclient\RPay\RaudhahClient;

class RaudhahPay
{
    /**
     * @var RaudhahClient
     */
    protected $pay;

    public function __construct()
    {
        $this->pay = new RaudhahClient();
    }

    /**
     * @param array $config
     * @return RaudhahClient
     */
    public static function make(array $config = []) : RaudhahClient
    {
        $client = (new self())->pay;

        if (function_exists('config')) {
            $config = empty($config) ? config('raudhahpay'): $config;
        }

        if (!isset($config['api_key'])) {
            throw new \RuntimeException("Api Key is required");
        }

        if (!isset($config['signature_key'])) {
            throw new \RuntimeException("Signature Key is required");
        }

        $client->setApiKey($config['api_key']);
        $client->setSignatureKey($config['signature_key']);

        if ($config['is_sandbox'] ?? true) {
            $client->useSandBox();
        }

        return $client;
    }
}
