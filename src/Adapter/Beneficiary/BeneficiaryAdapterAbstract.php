<?php
namespace Simmatrix\ACHProcessor\Adapter\Beneficiary;

use Simmatrix\ACHProcessor\Stringable;

abstract class BeneficiaryAdapterAbstract implements BeneficiaryAdapterInterface
{
    protected $model;

    protected $userID;
    protected $paymentAmount;
    protected $accountNumber;
    protected $bankCode;
    protected $bankBranchCode;
    protected $payeeName;

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
     * This is the code of the bank of the beneficiary (Maximum length: 4)
     * @return string $bankCode
     */
    public function getBankCode()
    {
        return $this -> bankCode;
    }

    /**
     * This is the code of the bank branch of the beneficiary (Maximum length: 4)
     * @return string $bankBranchCode
     */
    public function getBankBranchCode()
    {
        return $this -> bankBranchCode;
    }

    /**
     * This is the account name of the beneficiary (Maximum length: 20)
     * @return string
     */
    public function getPayeeName()
    {
        return $this -> payeeName;
    }
}
