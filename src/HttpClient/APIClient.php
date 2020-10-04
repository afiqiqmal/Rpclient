<?php

namespace Afiqiqmal\Rpclient\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Zttp\Zttp;

class APIClient
{
    /**
     * @var string
     */
    protected $endpoint = null;

    /**
     * @var array
     */
    protected $requestBody = [];

    /**
     * @var array
     */
    protected $param = [];

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var string
     */
    protected $urlSegment = null;

    /**
     * @var array
     */

    protected $queryString = null;

    /**
     * @var array
     */
    protected $header = [];

    /**
     * @var bool
     */
    protected $isJsonRequest = false;

    public function setEndpoint($url)
    {
        $this->endpoint = $url;

        return $this;
    }

    public function setRequestBody($param = [])
    {
        $this->requestBody = $param;

        return $this;
    }

    public function getMethod()
    {
        $this->method = 'GET';

        return $this;
    }

    public function postMethod()
    {
        $this->method = 'POST';

        return $this;
    }

    public function patchMethod()
    {
        $this->method = 'PATCH';

        return $this;
    }

    public function deleteMethod()
    {
        $this->method = 'DELETE';

        return $this;
    }

    public function setHeader($key, $value = null)
    {
        if (is_array($key)) {
            $this->header = array_merge($this->header, $key);
        } else {
            $this->header[$key] = $value;
        }

        return $this;
    }

    public function urlSegment($requestUrl, array $query = [])
    {
        $this->urlSegment = $requestUrl;
        $this->queryString = $query;

        return $this;
    }

    public function fetch() : PayResponse
    {
        if (! $this->endpoint) {
            throw new \RuntimeException('Endpoint URL need to be set!!');
        }

        $url = trim($this->endpoint, '/').'/'.trim($this->urlSegment, '/');

        $client = Zttp::asJson()->withHeaders($this->header);
        switch ($this->method) {
            case 'GET':
                $response = $client->get($url, array_merge($this->requestBody, $this->queryString));
                break;
            case 'POST':
                $response = $client->post($url, array_merge($this->requestBody, $this->queryString));
                break;
            case 'PATCH':
                $response = $client->patch($url, array_merge($this->requestBody, $this->queryString));
                break;
            case 'DELETE':
                $response = $client->delete($url, array_merge($this->requestBody, $this->queryString));
                break;
            default:
                throw new \RuntimeException("Invalid method");
        }

        return new PayResponse($response);
    }
}
