<?php
namespace Simmatrix\ACHProcessor\Adapter\Beneficiary;

use Simmatrix\ACHProcessor\Stringable;

abstract class BeneficiaryAdapterAbstract implements BeneficiaryAdapterInterface
{
    protected $model;

    // HUB MRI File + UOB
    protected $userID;
    protected $paymentAmount;
    protected $accountNumber;
    protected $bankCode;
    protected $bankBranchCode;
    protected $payeeName;

    // HSBC iFile
    protected $title;
    protected $email;
    protected $payeeIdentificationNumber;

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

    /**
     * [Advising Record] Recipient Title Flag
     * @return string $title
     */
    public function getTitle()
    {
        return $this -> title;
    }

    /**
     * [Advising Record] Email Address
     * @return string $email
     */
    public function getEmail()
    {
        return $this -> email;
    }

    /**
     * [Second Party Details] Second party Identifier (For Malaysian it would be NRIC number)
     * @return string $payeeIdentificationNumber
     */
    public function getPayeeIdentificationNumber()
    {
        return $this -> payeeIdentificationNumber;
    }

    /**
     * For HSBC
     * "M" - Mr
     * "R" - Mrs
     * "S" - Ms
     * "O" - Other
     * @return String
     */
    public function getRecipientTitleFlag()
    {
        switch( strtolower($this -> sanitizeString($this -> title))){
            case "mr":
                return "M";
            case "mrs":
                return "R";
            case "ms":
                return "S";
            default:
                return "O";
        }
    }

    /**
     * A description for title flag, if the value was O. Example : Dato / Datin / Tan Sri
     * @return String
     */
    public function getRecipientTitleDescription()
    {
        if ( $this -> getRecipientTitleFlag() != "O" ) return "";
        return $this -> title;
    }

    public function sanitizeString( $text )
    {
        $text = str_replace(' ', '', $text);
        return preg_replace('/[^A-Za-z]/', '', $text);
    }
}
