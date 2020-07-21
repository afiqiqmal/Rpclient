<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\HttpClient\ApiRequest;
use Afiqiqmal\Rpclient\HttpClient\PayResponse;
use Afiqiqmal\Rpclient\Utils\RPUtils;

class RPCollection
{
    /**
     * @var ApiRequest
     */
    protected $client;

    protected $path = "collections";

    public function __construct(RaudhahClient $request)
    {
        $this->client = $request->getClient();
    }

    /**
     * Create Collection
     *
     * @param $collection_name
     * @return PayResponse
     */
    public function create(string $collection_name): PayResponse
    {
        return $this->client
            ->urlSegment($this->path)
            ->postMethod()
            ->setRequestBody([
                'name' => $collection_name
            ])
            ->fetch();
    }

    /**
     * Fetch all Collections
     *
     * @param array $extras
     * @param string $include
     * @return PayResponse
     */
    public function fetchList(array $extras = [], string $include = 'organization'): PayResponse
    {
        return $this->client
            ->urlSegment($this->path, array_merge([
                'include' => $include,
            ], RPUtils::buildBodyRequest($extras)))
            ->fetch();
    }

    /**
     * Fetch all collection by code
     *
     * @param string $code
     * @param string $include
     * @return PayResponse
     */
    public function fetchByCode(string $code, string $include = 'organization'): PayResponse
    {
        return $this->client
            ->urlSegment($this->path."/$code", [
                'include' => $include
            ])
            ->fetch();
    }

    /**
     * Update collection name
     *
     * @param int $collection_id
     * @param string $collection_name
     * @param string $include
     * @return PayResponse
     */
    public function updateCollectionName(int $collection_id, string $collection_name, string $include = 'organization'): PayResponse
    {
        return $this->client
            ->patchMethod()
            ->urlSegment($this->path."/$collection_id", [
                'include' => $include
            ])
            ->setRequestBody([
                'name' => $collection_name
            ])
            ->fetch();
    }
}
