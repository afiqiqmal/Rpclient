<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\HttpClient\ApiRequest;
use Afiqiqmal\Rpclient\HttpClient\PayResponse;

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
     * @param int $page
     * @param string $include
     * @return PayResponse
     */
    public function fetchList(int $page = 1, string $include = 'organization'): PayResponse
    {
        return $this->client
            ->urlSegment($this->path, [
                'include' => $include
            ])
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
