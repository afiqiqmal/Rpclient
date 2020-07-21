<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\HttpClient\ApiRequest;

class RPBill
{
    /**
     * @var ApiRequest
     */
    protected $client;

    protected $path = "collections";

    protected $collectionId = null;

    public function __construct(RaudhahClient $request)
    {
        $this->client = $request->getClient();
    }

    /**
     * @return null
     */
    public function getCollectionId()
    {
        if (!$this->collectionId) {
            throw new \RuntimeException("Collection Code cant be empty");
        }

        return $this->collectionId;
    }

    /**
     * @param string $collectionId
     * @return RPBill
     */
    public function setCollectionId(string $collectionId)
    {
        $this->collectionId = $collectionId;
        return $this;
    }

    /**
     * @param string|null $collection_code
     * @return RPBillDetail
     */
    public function makeBill(string $collection_code = null) : RPBillDetail
    {
        $this->collectionId = $collection_code ?? $this->collectionId;
        return new RPBillDetail($this);
    }

    /**
     * @param string $bill_code
     * @param string $transaction_ref_no
     * @param string $include
     * @return \Afiqiqmal\Rpclient\HttpClient\PayResponse
     */
    public function getTransactions(string $bill_code, string $transaction_ref_no, string $include = 'bill.collection')
    {
        return $this->client
            ->urlSegment("{$this->path}/{$this->getCollectionId()}/bills/{$bill_code}/transactions/{$transaction_ref_no}", [
                'include' => $include
            ])
            ->fetch();
    }

    /**
     * @param string $bill_code
     * @param string $include
     * @return \Afiqiqmal\Rpclient\HttpClient\PayResponse
     */
    public function getAllBillTransactions(string $bill_code, string $include = 'bill.collection')
    {
        return $this->client
            ->urlSegment("{$this->path}/{$this->getCollectionId()}/bills/{$bill_code}/transactions", [
                'include' => $include
            ])
            ->fetch();
    }

    /**
     * @param string|null $bill_code
     * @param string $include
     * @return \Afiqiqmal\Rpclient\HttpClient\PayResponse
     */
    public function getBill($bill_code, string $include = 'payments.transaction,collection.organization')
    {
        return $this->client
            ->urlSegment("{$this->path}/{$this->getCollectionId()}/bills/{$bill_code}", [
                'include' => $include
            ])
            ->fetch();
    }

    /**
     * @param string $include
     * @return \Afiqiqmal\Rpclient\HttpClient\PayResponse
     */
    public function getAllCollectionBills(string $include = 'payments,collection.organization')
    {
        return $this->client
            ->urlSegment("{$this->path}/{$this->getCollectionId()}/bills/", [
                'include' => $include
            ])
            ->fetch();
    }

    /**
     * @return ApiRequest
     */
    public function getClient(): ApiRequest
    {
        return $this->client;
    }

}
