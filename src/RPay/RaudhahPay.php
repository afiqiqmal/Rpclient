<?php


namespace Afiqiqmal\Rcplient\RPay;


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

    public static function make($sandbox = false, $apiKey = null, $signatureKey = null) : RaudhahClient
    {
        $client = (new self())->pay;
        $client->setApiKey($apiKey ?? config('raudhahpay.api_key'));
        $client->setSignatureKey($signatureKey ?? config('raudhahpay.signature_key'));

        if ($sandbox) {
            $client->useSandBox();
        }

        return (new self())->pay;
    }
}
