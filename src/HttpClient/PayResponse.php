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
     * @var string[]
     */
    protected $header;

    /**
     * @var int
     */
    protected $status_code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $reference;


    public function __construct($response)
    {
        if ($response instanceof ResponseInterface) {
            $this->setError(false);
            $this->setBody(json_decode($response->getBody(), true));
            $this->setHeader($response->getHeaders());
            $this->setStatusCode($response->getStatusCode());
        }

        if ($response instanceof BadResponseException) {
            $responseBody = json_decode($response->getResponse()->getBody(), true);
//            $body = call_user_func_array('array_merge', $responseBody ?? []);
//            $body = array_values($body);
            $this->setError(true);
            $this->setReference($responseBody);
            $this->setMessage("Request Failed");
            $this->setStatusCode($response->getResponse()->getStatusCode());

        } else if ($response instanceof \Exception) {
            $this->setError(true);
            $this->setStatusCode(500);
            $this->setMessage("Something went wrong");
            $this->setReference(is_string($response->getMessage()) ? [$response->getMessage()] : $response->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param $reference
     */
    protected function setReference($reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    protected function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->error;
    }

    /**
     * @param bool $error
     */
    protected function setError(bool $error): void
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     */
    protected function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return string[]
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @param string[] $header
     */
    protected function setHeader(array $header): void
    {
        $this->header = $header;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    /**
     * @param int $status_code
     */
    protected function setStatusCode(int $status_code): void
    {
        $this->status_code = $status_code;
    }

    public function output()
    {
        $data = [
            'error' => $this->isError(),
            'status_code' => $this->getStatusCode(),
            'message' =>  $this->getMessage(),
            'body' =>  $this->getBody(),
        ];

        if (!$this->isError()) {
            return $data;
        } else {
            $data['references'] = $this->getReference();
            unset($data['body']);

            return $data;
        }
    }
}
