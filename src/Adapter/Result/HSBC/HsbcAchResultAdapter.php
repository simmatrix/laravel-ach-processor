<?php

namespace Simmatrix\ACHProcessor\Adapter\Result\HSBC;

use Simmatrix\ACHProcessor\Adapter\Result\ACHResultAdapterAbstract;
use Simmatrix\ACHProcessor\Result\ACHResult;

class HsbcAchResultAdapter extends ACHResultAdapterAbstract
{
    const OFFSET_FILE_ID = 0;
    const OFFSET_PAYMENT_ID = 1;
    const OFFSET_TRANSACTION_ID = 2;
    const OFFSET_INSTRUMENT_TYPE = 3;
    const OFFSET_INSTRUMENT_NUMBER = 4;
    const OFFSET_INSTRUMENT_DATE = 5;
    const OFFSET_CURRENCY = 6;
    const OFFSET_AMOUNT = 7;
    const OFFSET_DEBIT_DATE = 8;
    const OFFSET_STATUS = 9;
    const OFFSET_STATUS_DATE = 10;
    const OFFSET_BENEFICIARY_ID = 11;
    const OFFSET_BENEFICIARY_NAME = 12;

    /**
     * @param String The line to parse
     */
    public function __construct($string){
        parent::__construct($string);

        $result = new ACHResult();
        $result -> setFileIdentifier( trim($this -> columns[self::OFFSET_FILE_ID]));
        $result -> setPaymentId( trim($this -> columns[self::OFFSET_PAYMENT_ID]));
        $result -> setTransactionId( trim($this -> columns[self::OFFSET_TRANSACTION_ID]));
        $result -> setInstrumentType( trim($this -> columns[self::OFFSET_INSTRUMENT_TYPE]));
        $result -> setInstrumentNumber( trim($this -> columns[self::OFFSET_INSTRUMENT_NUMBER]));

        //datetime is now patched to return d/m/Y
        $datetime = \DateTime::createFromFormat('d/m/y', trim($this -> columns[self::OFFSET_INSTRUMENT_DATE]));
        if( !$datetime )
            $datetime = \DateTime::createFromFormat('d/m/Y', trim($this -> columns[self::OFFSET_INSTRUMENT_DATE]));
        $result -> setDateTime($datetime);

        $result -> setCurrency(trim($this -> columns[self::OFFSET_CURRENCY]));
        $result -> setAmount(trim($this -> columns[self::OFFSET_AMOUNT]));

        //debit_date is now patched to return d/m/Y
        $debit_date = \DateTime::createFromFormat('d/m/y', trim($this -> columns[self::OFFSET_DEBIT_DATE]));
        if( !$debit_date )
            $debit_date = \DateTime::createFromFormat('d/m/Y', trim($this -> columns[self::OFFSET_DEBIT_DATE]));
        $result -> setDebitDate($debit_date);

        $result -> setStatus(trim($this -> columns[self::OFFSET_STATUS]));

        //status_date is now patched to return d/m/Y
        $status_date = \DateTime::createFromFormat('d/m/y', trim($this -> columns[self::OFFSET_STATUS_DATE]));
        if( !$status_date )
            $status_date = \DateTime::createFromFormat('d/m/Y', trim($this -> columns[self::OFFSET_STATUS_DATE]));
        $result -> setStatusDate($status_date);

        $result -> setBeneficiaryId(trim($this -> columns[self::OFFSET_BENEFICIARY_ID]));
        $result -> setFullname(trim($this -> columns[self::OFFSET_BENEFICIARY_NAME]));

        $this -> achResult = $result;
    }
}
?>
