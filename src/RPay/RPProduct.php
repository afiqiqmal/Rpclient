<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\Contracts\RPContracts;
use Afiqiqmal\Rpclient\HttpClient\ApiRequest;
use Afiqiqmal\Rpclient\HttpClient\PayResponse;
use Afiqiqmal\Rpclient\Utils\RPUtils;

class RPProduct implements RPContracts
{
    /**
     * @var ApiRequest
     */
    protected $client;

    protected $path = "products";

    public function __construct(RaudhahClient $request)
    {
        $this->client = $request->getClient();
    }

    /**
     * @param array $extras
     * @param string $include
     * @return PayResponse
     */
    public function getList(array $extras = [], string $include = 'organization,product-collections.bill')
    {
        return $this->client
            ->urlSegment($this->url(), array_merge([
                'include' => $include,
            ], RPUtils::buildBodyRequest($extras)))
            ->fetch();
    }

    /**
     * @param $id
     * @param string $include
     * @return PayResponse
     */
    public function get($id, string $include = 'organization,product-collections.bill')
    {
        return $this->client
            ->urlSegment("{$this->url()}/{$id}", [
                'include' => $include,
            ])
            ->fetch();
    }

    /**
     * @param $title
     * @param string|null $code
     * @param string|null $description
     * @param null $price
     * @param string $include
     * @return PayResponse
     */
    public function create($title, string $code = null, string $description = null, $price = null, string $include = 'organization')
    {
        return $this->client
            ->urlSegment($this->url(), [
                'include' => $include
            ])
            ->postMethod()
            ->setRequestBody($this->buildBody($title, $code, $description, $price))
            ->fetch();
    }

    /**
     * @param $id
     * @param $title
     * @param string|null $code
     * @param string|null $description
     * @param null $price
     * @param string $include
     * @return PayResponse
     */
    public function update($id, $title, string $code = null, string $description = null, $price = null, string $include = 'organization,product-collections.bill')
    {
        return $this->client
            ->urlSegment("{$this->url()}/{$id}", [
                'include' => $include
            ])
            ->patchMethod()
            ->setRequestBody($this->buildBody($title, $code, $description, $price))
            ->fetch();
    }

    /**
     * @param $id
     * @return PayResponse
     */
    public function delete($id)
    {
        return $this->client
            ->urlSegment("{$this->url()}/{$id}")
            ->deleteMethod()
            ->fetch();
    }

    /**
     * @param $title
     * @param string $code
     * @param string $description
     * @param $price
     * @return array
     */
    private function buildBody($title, string $code, string $description, $price)
    {
        return is_array($title) ? $title : [
            'title' => $title,
            'code' => $code,
            'description' => $description,
            'price' => $price,
        ];
    }

    /**
     * @param $extras
     * @return array
     */
    private function buildExtraFilter($extras) {
        return [
            'filter[title]' => $extras['title'] ?? null,
            'filter[description]' => $extras['description'] ?? null,
            'filter[code]' => $extras['code'] ?? null,
            'filter[price]' => $extras['price'] ?? null,
            'page' => $extras['page'] ?? 1
        ];
    }

    /**
     * @return ApiRequest
     */
    public function getClient(): ApiRequest
    {
        return $this->client;
    }

    public function url()
    {
        return $this->path;
    }
}
