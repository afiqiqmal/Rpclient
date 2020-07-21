<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\Contracts\RPContracts;
use Afiqiqmal\Rpclient\HttpClient\PayResponse;
use Afiqiqmal\Rpclient\Utils\RPUtils;

class RPDPPayer implements RPContracts
{
    /**
     * @var RPDirectPay
     */
    protected $client;

    protected $path = "collections";

    public function __construct(RPDirectPay $request)
    {
        $this->client = $request;
    }

    public function url()
    {
        return "$this->path/{$this->client->getCollectionId()}/directPayPayers";
    }

    /**
     * @param $direct_pay_payer_code
     * @param array $extras
     * @param string $include
     * @return PayResponse
     */
    public function getTransactions($direct_pay_payer_code, array $extras = [], $include = 'account,collection,payment')
    {
        return $this->client->getClient()
            ->urlSegment("{$this->url()}/{$direct_pay_payer_code}/transactions", array_merge([
                'include' => $include,
            ], RPUtils::buildBodyRequest($extras)))
            ->fetch();
    }

    /**
     * @param $direct_pay_payer_code
     * @param $transaction_ref_direct_pay_id
     * @param array $extras
     * @param string $include
     * @return PayResponse
     */
    public function getTransaction($direct_pay_payer_code, $transaction_ref_direct_pay_id, array $extras = [], $include = 'account,collection,payment')
    {
        return $this->client->getClient()
            ->urlSegment("{$this->url()}/${$direct_pay_payer_code}/transactions/${transaction_ref_direct_pay_id}", array_merge([
                'include' => $include,
            ], RPUtils::buildBodyRequest($extras)))
            ->fetch();
    }

    /**
     * @param array $extras
     * @param string $include
     * @return PayResponse
     */
    public function getPaidList(array $extras = [], $include = 'account,collection,payment')
    {
        return $this->client->getClient()
            ->urlSegment($this->url(), array_merge([
                'include' => $include,
            ], RPUtils::buildBodyRequest($extras)))
            ->fetch();
    }

    /**
     * @param $direct_pay_payer_code
     * @param array $extras
     * @param string $include
     * @return PayResponse
     */
    public function getPaidDetail($direct_pay_payer_code, array $extras = [], $include = 'account,collection,payment')
    {
        return $this->client->getClient()
            ->urlSegment("{$this->url()}/{$direct_pay_payer_code}", array_merge([
                'include' => $include,
            ], RPUtils::buildBodyRequest($extras)))
            ->fetch();
    }

    /**
     * @param array $extras
     * @return array
     */
    private function buildExtraFilter(array $extras) {
        return [
            'filter[collection_id]' => $extras['collection_id'] ?? null,
            'filter[collection_code]' => $extras['collection_code'] ?? null,
            'filter[account_first_name]' => $extras['account_first_name'] ?? null,
            'filter[account_last_name]' => $extras['account_last_name'] ?? null,
            'filter[title]' => $extras['title'] ?? null,
            'filter[direct_pay_no]' => $extras['direct_pay_no'] ?? null,
            'page' => $extras['page'] ?? 1
        ];
    }
}
