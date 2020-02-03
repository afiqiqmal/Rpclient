<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\HttpClient\ApiRequest;

class RaudhahClient
{
    /**
     * RaudhahPay API Key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * RaudhahPay X-Signature Key.
     *
     * @var string|null
     */
    protected $signatureKey;

    /**
     * RaudhahPay X-Signature Key.
     *
     * @var string|null
     */
    protected $is_sandbox;

    /**
     * RaudhahPay Headers
     *
     * @var array
     */
    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * RaudhahPay API endpoint.
     *
     * @var string
     */
    protected $endpoint = 'https://api.raudhahpay.com/api';

    /**
     * API Request
     *
     * @var ApiRequest
     */
    protected $client;

    public function __construct()
    {
        $this->client = new ApiRequest();
        $this->client->setEndpoint($this->endpoint);
        $this->client->jsonRequest();
    }

    public function useSandBox()
    {
        $this->client->setEndpoint("https://stg-api.raudhahpay.com/api");
    }

    /**
     * @param string|null $signatureKey
     */
    public function setSignatureKey(?string $signatureKey): void
    {
        $this->signatureKey = $signatureKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
        $this->client->setHeader('Authorization', 'Bearer '.$apiKey);
    }

    /**
     * @return string
     */
    public function getSignatureKey(): string
    {
        return $this->signatureKey;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }


    /**
     * @return ApiRequest
     */
    public function getClient(): ApiRequest
    {
        return $this->client;
    }

    public function collection() : RPCollection
    {
        return new RPCollection($this);
    }

    public function bill() : RPBill
    {
        return new RPBill($this);
    }

    public function checkIncomingRequest(array $request)
    {
        $checksum = $request['signature'];
        ksort($request);
        unset($request['signature']);

        $keyValues = array_map(function ($key, $value) {
            return "$key:$value";
        }, array_keys($request), $request);

        $generatedSHA = hash_hmac('sha256', implode('|', $keyValues), $this->getSignatureKey());

        return $generatedSHA == $checksum;
    }
}
