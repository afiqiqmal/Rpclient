<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\HttpClient\ApiRequest;
use Afiqiqmal\Rpclient\HttpClient\ApiResponse;

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
     * @return ApiResponse
     */
    public function create(string $collection_name): ApiResponse
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
     * @return ApiResponse
     */
    public function fetchList(int $page = 1, string $include = 'organization'): ApiResponse
    {
        return $this->client
            ->urlSegment($this->path, [
                'includes' => $include
            ])
            ->fetch();
    }

    /**
     * Fetch all collection by code
     *
     * @param string $code
     * @param string $include
     * @return ApiResponse
     */
    public function fetchByCode(string $code, string $include = 'organization'): ApiResponse
    {
        return $this->client
            ->urlSegment($this->path."/$code", [
                'includes' => $include
            ])
            ->fetch();
    }

    /**
     * Update collection name
     *
     * @param int $collection_id
     * @param string $collection_name
     * @param string $include
     * @return ApiResponse
     */
    public function updateCollectionName(int $collection_id, string $collection_name, string $include = 'organization'): ApiResponse
    {
        return $this->client
            ->patchMethod()
            ->urlSegment($this->path."/$collection_id", [
                'includes' => $include
            ])
            ->setRequestBody([
                'name' => $collection_name
            ])
            ->fetch();
    }
}
