<?php

namespace Afiqiqmal\Rpclient\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class ApiRequest
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
    protected $header = null;


    /**
     * @var bool
     */
    protected $isRaw = false;

    /**
     * @var bool
     */
    protected $verifySSL = true;

    /**
     * @var bool
     */
    protected $isJsonRequest = false;

    public function setEndpoint($url)
    {
        $this->endpoint = $url;

        return $this;
    }

    public function setParam($option = [])
    {
        $this->param = $option;

        return $this;
    }

    public function setRequestBody($param = [])
    {
        $this->requestBody = $param;

        return $this;
    }

    public function jsonRequest()
    {
        $this->isJsonRequest = true;

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

    public function setMethod($method = 'GET')
    {
        $this->method = $method;

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

    public function getRaw()
    {
        $this->isRaw = true;

        return $this;
    }

    public function ignoreSSL()
    {
        $this->verifySSL = false;

        return $this;
    }

    public function fetch() : PayResponse
    {
        if (count($this->param) > 0 && $this->requestBody == null) {
            $this->requestBody = $this->param;
        }

        if (! $this->endpoint) {
            throw new \RuntimeException('Endpoint URL need to be set!!');
        }

        $url = trim($this->endpoint, '/').DIRECTORY_SEPARATOR.trim($this->urlSegment, '/');

        try {
            $client = new Client();
            switch ($this->method) {
                case 'GET':
                    $param = [
                        'query' => array_merge($this->requestBody, $this->queryString),
                    ];
                    break;
                case 'POST':
                case 'PATCH':
                case 'DELETE':
                    $param = [
                        $this->isJsonRequest ? 'json' : 'form_params' => $this->requestBody,
                        'query' => $this->queryString
                    ];
                    break;
                default:
                    $param = null;
                    break;
            }

            if (isset($this->header)) {
                $param['headers'] = $this->header;
            }

            if (! $this->verifySSL) {
                $param['verify'] = false;
            }

            $param = array_merge($param, $this->param);
            $response = $client->request($this->method, $url, $param);

            return new PayResponse($response);

        } catch (BadResponseException $ex) {
            return new PayResponse($ex);
        } catch (\Exception $ex) {
            return new PayResponse($ex);
        }
    }
}
