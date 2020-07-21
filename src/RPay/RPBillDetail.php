<?php


namespace Afiqiqmal\Rpclient\RPay;


use Afiqiqmal\Rpclient\HttpClient\PayResponse;

class RPBillDetail
{
    /**
     * @var RPBill
     */
    protected $rpBill;

    protected $firstName;
    protected $lastName;
    protected $address;
    protected $email;
    protected $phoneNumber;

    protected $dueDate = null;

    protected $currencyCode = "MYR";

    protected $ref1 = null;
    protected $ref2 = null;

    protected $products = [];

    protected $path = "collections";

    public function __construct(RPBill $rpBill)
    {
        $this->rpBill = $rpBill;
    }

    /**
     * @param int $timestamp
     * @return RPBillDetail
     */
    public function setDueDate(int $timestamp): RPBillDetail
    {
        $this->dueDate = date('Y-m-d', $timestamp);
        return $this;
    }

    public function setCustomer($firstName, $lastName, $email, $phoneNumber, $address): RPBillDetail
    {
        $this->firstName = trim($firstName);
        $this->lastName = trim($lastName);
        $this->email = trim($email);
        $this->phoneNumber = trim($phoneNumber);
        $this->address = trim($address);

        return $this;
    }

    public function setProduct(string $title, float $price = null, int $quantity = null): RPBillDetail
    {
        $this->products[] = [
            'title' => $title,
            'price' => $price,
            'quantity' => $quantity,
        ];
        return $this;
    }

    public function setProducts(array $products): RPBillDetail
    {
        $this->products = array_merge($this->products, $products);
        return $this;
    }

    public function setCurrencyCode(string $code): RPBillDetail
    {
        $this->currencyCode = $code;
        return $this;
    }

    public function setReference($ref1, $ref2 = null): RPBillDetail
    {
        $this->ref1 = $ref1;
        $this->ref2 = $ref2;
        return $this;
    }

    /**
     * @throws \RuntimeException
     * @param string $include
     * @return PayResponse
     */
    public function create(string $include = 'product-collections.product'): PayResponse
    {
        $validate = $this->isPayloadValid();
        if ($validate instanceof \Exception) {
            return new PayResponse($validate);
        }

        return $this->rpBill->getClient()
            ->urlSegment($this->path."/{$this->rpBill->getCollectionId()}/bills", [
                'include' => $include
            ])
            ->postMethod()
            ->setRequestBody($this->buildPaymentDetail())
            ->fetch();
    }

    /**
     * @return bool|\Exception
     */
    private function isPayloadValid()
    {
        if (count($this->products) > 0) {
            foreach ($this->products as $product) {
                if (array_key_exists('title', $product) && array_key_exists('price', $product) && array_key_exists('quantity', $product)) {
                    continue;
                } else {
                    return new \Exception("Invalid Product");
                }
            }
        } else {
            return new \Exception("Product cant be empty");
        }

        if ($this->firstName == null || $this->lastName == null || $this->email == null  || $this->phoneNumber == null || $this->address == null ) {
            return new \Exception("Customer First Name, Last Name, Email, Address and Phone Number is required");
        }

        return true;
    }

    /**
     * @return array
     */
    private function buildPaymentDetail() : array
    {
        return [
            'due' => $this->dueDate ?? date("Y-m-d", strtotime('tomorrow')),
            'currency' => $this->currencyCode,
            'ref1' => $this->ref1,
            'ref2' => $this->ref2,
            'customer' => [
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'address' => $this->address,
                'email' => $this->email,
                'mobile' => $this->phoneNumber,
            ],
            'product' => $this->products
        ];
    }
}
