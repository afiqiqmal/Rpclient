<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\Contracts\RPContracts;
use Afiqiqmal\Rpclient\HttpClient\ApiRequest;
use Afiqiqmal\Rpclient\HttpClient\PayResponse;
use Afiqiqmal\Rpclient\Utils\RPUtils;

class RPCustomer implements RPContracts
{
    /**
     * @var ApiRequest
     */
    protected $client;

    protected $path = "customers";

    public function __construct(RaudhahClient $request)
    {
        $this->client = $request->getClient();
    }

    /**
     * @param array $extras
     * @param string $include
     * @return PayResponse
     */
    public function getList(array $extras = [], string $include = 'organization,account-groups.group')
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
    public function get($id, string $include = 'organization')
    {
        return $this->client
            ->urlSegment("{$this->url()}/{$id}", [
                'include' => $include,
            ])
            ->fetch();
    }

    /**
     * @param $firstName
     * @param string|null $lastName
     * @param string|null $phoneNumber
     * @param string|null $email
     * @param string $include
     * @return PayResponse
     */
    public function create($firstName, string $lastName = null, string $phoneNumber = null, string $email = null, string $include = 'organization')
    {
        return $this->client
            ->urlSegment($this->url(), [
                'include' => $include
            ])
            ->postMethod()
            ->setRequestBody($this->buildBody($firstName, $lastName, $phoneNumber, $email))
            ->fetch();
    }

    /**
     * @param $id
     * @param null $firstName
     * @param string|null $lastName
     * @param string|null $phoneNumber
     * @param string|null $address
     * @param string $include
     * @return PayResponse
     */
    public function update($id, $firstName = null, string $lastName = null, string $phoneNumber = null, string $address = null, string $include = 'organization')
    {
        return $this->client
            ->urlSegment("{$this->url()}/{$id}", [
                'include' => $include
            ])
            ->patchMethod()
            ->setRequestBody($this->buildBody($firstName, $lastName, $phoneNumber, null, $address))
            ->fetch();
    }

    /**
     * @param $extras
     * @return array
     */
    private function buildExtraFilter($extras)
    {
        return [
            'filter[group_id]' => $extras['group_id'] ?? null,
            'filter[first_name]' => $extras['first_name'] ?? null,
            'filter[last_name]' => $extras['last_name'] ?? null,
            'filter[acc_no]' => $extras['address'] ?? null,
            'filter[address]' => $extras['address'] ?? null,
            'filter[mobile]' => $extras['mobile'] ?? null,
            'page' => $extras['page'] ?? 1
        ];
    }


    /**
     * @param null $firstName
     * @param string|null $lastName
     * @param string|null $phoneNumber
     * @param string|null $email
     * @param string|null $address
     * @return array|null
     */
    private function buildBody($firstName = null, string $lastName = null, string $phoneNumber = null, string $email = null, string $address = null)
    {
        return is_array($firstName) ? $firstName : [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'mobile' => $phoneNumber,
            'email' => $email,
            'address' => $address,
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
