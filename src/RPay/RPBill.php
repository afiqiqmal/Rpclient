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

    public function __construct(RaudhahClient $request)
    {
        $this->client = $request->getClient();
    }

    public function makeBill() : RPBillDetail
    {
        return new RPBillDetail($this);
    }

    public function getTransactions($collection_code, $bill_code, $transaction_ref_no, string $include = 'bill.collection')
    {
        return $this->client
            ->urlSegment("{$this->path}/{$collection_code}/bills/{$bill_code}/transactions/{$transaction_ref_no}", [
                'includes' => $include
            ])
            ->fetch();
    }

    public function getAllBillTransactions($collection_code, $bill_code, string $include = 'bill.collection')
    {
        return $this->client
            ->urlSegment("{$this->path}/{$collection_code}/bills/{$bill_code}/transactions", [
                'includes' => $include
            ])
            ->fetch();
    }

    public function getBill($collection_code, $bill_code, string $include = 'payments.transaction,collection.organization')
    {
        return $this->client
            ->urlSegment("{$this->path}/{$collection_code}/bills/{$bill_code}", [
                'includes' => $include
            ])
            ->fetch();
    }

    public function getAllCollectionBills($collection_code, string $include = 'payments,collection.organization')
    {
        return $this->client
            ->urlSegment("{$this->path}/{$collection_code}/bills/", [
                'includes' => $include
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
