<?php


namespace Afiqiqmal\Rpclient\HttpClient;


use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

class ApiResponse
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
            $this->setBody($response->getResponse()->getBody()->getContents());
            $this->setStatusCode($response->getResponse()->getStatusCode());
            $this->setMessage("Something went wrong");
            $this->setReference($response->getMessage());
            $this->setError(true);

        } else if ($response instanceof \Exception) {
            $this->setError(true);
            $this->setStatusCode(500);
            $this->setMessage("Something went wrong");
            $this->setReference($response->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    protected function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getMessage(): string
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
     * @return array|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array|string $body
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

    public function fullOutput()
    {
        if (!$this->isError()) {
            return [
                'error' => $this->isError(),
                'status_code' => $this->getStatusCode(),
                'body' =>  $this->getBody(),
                'header' =>  $this->getHeader(),
            ];
        } else {
            return [
                'error' => $this->isError(),
                'status_code' => $this->getStatusCode(),
                'message' =>  $this->getMessage(),
                'references' =>  $this->getReference(),
            ];
        }
    }

    public function output()
    {
        if (!$this->isError()) {
            return [
                'error' => $this->isError(),
                'status_code' => $this->getStatusCode(),
                'body' =>  $this->getBody(),
            ];
        } else {
            return [
                'error' => $this->isError(),
                'status_code' => $this->getStatusCode(),
                'message' =>  $this->getMessage(),
            ];
        }
    }
}
