<?php
namespace Simmatrix\ACHProcessor\Adapter\Beneficiary;

use Simmatrix\ACHProcessor\Stringable;

abstract class BeneficiaryAdapterAbstract implements BeneficiaryAdapterInterface
{
    protected $model;

    protected $userID;
    protected $paymentAmount;
    protected $accountNumber;
    protected $payeeName;
    protected $secondPartyReference;

    /**
     * @param model
     */
    abstract public function __construct($model);

    /**
     * The data that you can refer back to your own database, example, the employee number
     * @return string $userID
     */
    public function getUserID()
    {
        return $this -> userID;
    }

    /**
     * This is the amount to credit into beneficiary account (Maximum: 16)
     * Will return with zero-padding (e.g. 0000000000002.00)
     * @return string $paymentAmount
     */
    public function getPaymentAmount()
    {
        return $this -> paymentAmount;
    }

    /**
     * This is the account number of the beneficiary (Maximum length: 20)
     * @return string $accountNumber
     */
    public function getAccountNumber()
    {
        return $this -> accountNumber;
    }

    /**
     * This is the account name of the beneficiary (Maximum length: 20)
     * @return string
     */
    public function getPayeeName()
    {
        return $this -> payeeName;
    }

    /**
     * This can either be the IC number or the company registration number (Maximum length: 12)
     * @return string
     */
    public function getSecondPartyReference()
    {
        return $this -> secondPartyReference;
    }
}
