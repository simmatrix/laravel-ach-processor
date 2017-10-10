<?php
namespace Simmatrix\ACHProcessor\Adapter\Beneficiary;

interface BeneficiaryAdapterInterface
{
    /**
     * The data that you can refer back to your own database, example, the employee number
     * @return string
     */
    public function getUserID();

    /**
     * This is the amount to credit into beneficiary account (Maximum: 16)
     * Will return with zero-padding (e.g. 0000000000002.00)
     * @return string
     */
    public function getPaymentAmount();

    /**
     * This is the account number of the beneficiary (Maximum length: 20)
     * @return string
     */
    public function getAccountNumber();

    /**
     * This is the code of the bank of the beneficiary (Maximum length: 4)
     * @return string
     */
    public function getBankCode();

    /**
     * This is the code of the bank branch of the beneficiary (Maximum length: 4)
     * @return string
     */
    public function getBankBranchCode();

    /**
     * This is the account name of the beneficiary (Maximum length: 20)
     * @return string
     */
    public function getPayeeName();
}
