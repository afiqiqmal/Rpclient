<?php


namespace Afiqiqmal\Rpclient\HttpClient;


use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

class PayResponse
{
    /**
     * @var bool
     */
    protected $error;

    /**
     * @var string|array
     */
    protected $body;

    /**
     * @var int
     */
    protected $status_code;


    public function __construct($response)
    {
        try {
            $this->error = !$response->isOk();
            $this->status_code = $response->status();
            $this->body = $response->json();
        } catch (\Exception $exception) {
            $this->error = true;
            $this->status_code = $response->status();
            $this->body = $response->json();
        }
    }

    public function __toString()
    {
        return json_encode($this->output());
    }

    public function output()
    {
        return [
            'error' => $this->error,
            'status_code' => $this->status_code,
            'body' =>  $this->body,
        ];
    }
}
