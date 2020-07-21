<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\Contracts\RPContracts;
use Afiqiqmal\Rpclient\HttpClient\PayResponse;
use Afiqiqmal\Rpclient\Utils\RPUtils;

class RPDPPayee implements RPContracts
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

    public function url() {
        return "$this->path/{$this->client->getCollectionId()}/directPays";
    }

    /**
     * @param array $extras
     * @param string $include
     * @return PayResponse
     */
    public function getDirectPays(array $extras = [], $include = 'account,collection')
    {
        return $this->client->getClient()
            ->urlSegment($this->url(), array_merge([
                'include' => $include,
            ], RPUtils::buildBodyRequest($extras)))
            ->fetch();
    }

    /**
     * @param $dp_code
     * @param string $include
     * @return PayResponse
     */
    public function getDirectPayDetail($dp_code, $include = 'account,collection')
    {
        return $this->client->getClient()
            ->urlSegment("{$this->url()}/{$dp_code}", [
                'include' => $include,
            ])
            ->fetch();
    }

    /**
     * @param $title
     * @param null $code
     * @param null $description
     * @param null $price
     * @param string $include
     * @return PayResponse
     */
    public function create($title, $code = null, $description = null, $price = null, $include = 'account,collection')
    {
        return $this->client->getClient()
            ->urlSegment($this->url(), [
                'include' => $include
            ])
            ->postMethod()
            ->setRequestBody($this->buildBody($title, $code, $description, $price))
            ->fetch();
    }

    /**
     * @param $dp_code
     * @param $title
     * @param null $code
     * @param null $description
     * @param null $price
     * @param string $include
     * @return PayResponse
     */
    public function update($dp_code, $title, $code = null, $description = null, $price = null, $include = 'account,collection')
    {
        return $this->client->getClient()
            ->urlSegment("{$this->url()}/{$dp_code}", [
                'include' => $include
            ])
            ->patchMethod()
            ->setRequestBody($this->buildBody($title, $code, $description, $price))
            ->fetch();
    }

    /**
     * @param $dp_code
     * @return PayResponse
     */
    public function delete($dp_code)
    {
        return $this->client->getClient()
            ->urlSegment("{$this->url()}/{$dp_code}")
            ->deleteMethod()
            ->fetch();
    }

    /**
     * @param $title
     * @param $collection_id
     * @param $description
     * @param $price
     * @return array
     */
    private function buildBody($title, $collection_id, $description, $price)
    {
        return is_array($title) ? $title : [
            'title' => $title,
            'collection_id' => $collection_id,
            'description' => $description,
            'amount' => $price,
        ];
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
