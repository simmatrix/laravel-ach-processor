<?php

namespace Simmatrix\ACHProcessor\Result;

class ACHResult
{
    /**
     * @var float The amount of payment
     */
    protected $amount;

    /**
     * @var DateTime The beneficiary ID
     */
    protected $beneficiaryId;

    /**
     * @var String Three-letter currency code
     */
    protected $currency;

    /**
     * @var DateTime The instrument date
     */
    protected $dateTime;

    /**
     * @var DateTime The debit date
     */
    protected $debitDate;

    /**
    * @var String The file identifier, from a previously generated COS csv file. ( batch reference )
    */
    protected $fileIdentifier;

    /**
     * @var String The fullname of the beneficiary
     */
    protected $fullname;

    /**
     * @var int The instrument number
     */
    protected $instrumentNumber;

    /**
     * @var int The instrument type
     * "ICO" - In-country cashier's order
     * "DD" - Demand Draft
     * "CC" - Company cheque
     * "CCO" - Cross-border cashier's order
     */
    protected $instrumentType;

    /**
     * @var int The payment id, usually from a model ( customer reference )
     */
    protected $paymentId;

    /**
     * @var DateTime The debit date
     */
    protected $status;

    /**
     * @var DateTime The status date
     */
    protected $statusDate;

    /**
     * @var int The bank transaction id ( instruction reference )
     */
    protected $transactionId;

    /**
     * @param float
     */
    public function setAmount( $amount ){
        $this -> amount = $amount;
    }

    /**
     * @param String
     */
    public function setBeneficiaryId( $beneficiary_id ){
        $this -> beneficiaryId = $beneficiary_id;
    }

    /**
     * @param String
     */
    public function setFullname( $fullname ){
        $this -> fullname = $fullname;
    }

    /**
     * @param String
     */
    public function setCurrency( $currency ){
        $this -> currency = $currency;
    }

    /**
     * @param DateTime
     */
    public function setDebitDate( \DateTime $debit_date ){
        $this -> debitDate = $debit_date;
    }

    /**
    * @param String
    */
    public function setFileIdentifier( $identifier ){
        $this -> fileIdentifier = $identifier;
    }

    /**
    * @param String
    */
    public function setInstrumentNumber( $instrument_number ){
        $this -> instrumentNumber = $instrument_number;
    }

    /**
    * @param String
    */
    public function setDateTime( \DateTime $dateTime ){
        $this -> dateTime = $dateTime;
    }

    /**
     * @param String
     */
    public function setInstrumentType( $instrument_type ){
        $this -> instrumentType = $instrument_type;
    }

    /**
     * @param int
     */
    public function setPaymentId( $payment_id ){
        $this -> paymentId = $payment_id;
    }

    /**
    * @param String
    */
    public function setStatus( $status ){
        $this -> status = $status;
    }

    /**
     * @param DateTime
     */
    public function setStatusDate( \DateTime $status_date ){
        $this -> statusDate = $status_date;
    }

    /**
     * @param String
     */
    public function setTransactionId($transaction_id){
        $this -> transactionId = $transaction_id;
    }

    public function __get($property){
        if( property_exists( $this, $property )){
            return $this -> $property;
        }
    }
}
?>
