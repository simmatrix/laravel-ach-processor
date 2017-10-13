<?php

namespace Simmatrix\ACHProcessor\Factory\UOB\Header;

use Simmatrix\ACHProcessor\Line\Line;
use Simmatrix\ACHProcessor\Line\Header;
use Simmatrix\ACHProcessor\Beneficiary;
use Simmatrix\ACHProcessor\BeneficiaryLine;
use Simmatrix\ACHProcessor\Column\Date;
use Simmatrix\ACHProcessor\Factory\Column\ConfigurableStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\EmptyColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedDecimalWithoutDelimiterColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedZerofillStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\PresetStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\RightPaddedStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\VariableLengthStringColumnFactory;

/**
 * For UOB, a batch trailer comes at the end of the file.
 */
class UOBBatchTrailer extends Header
{
    const RECORD_TYPE = "9";
    const DEFAULT_ZERO = "0";

    const TRANSACTION_CODE_MISC_CREDIT = '20';
    const TRANSACTION_CODE_SALARY_CREDIT = '22';
    const TRANSACTION_CODE_DIVIDEND_CREDIT = '23';
    const TRANSACTION_CODE_REMITTANCE_CREDIT = '24';
    const TRANSACTION_CODE_BILL_CREDIT = '25';
    const TRANSACTION_CODE_DIRECT_DEBIT = '30';

    protected $columnDelimiter = "";

    /**
     * @return Line
     */
    public function getLine(){
        $line = new Line();
        $line -> setColumnDelimiter("");

        $transaction_code = ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'transaction_code', $label = 'transaction_code', $default_value = SELF::TRANSACTION_CODE_MISC_CREDIT, $max_length = 2);
        $total_debit_amount = LeftPaddedDecimalWithoutDelimiterColumnFactory::create(SELF::DEFAULT_ZERO, $length = 13, $label = 'total_debit_amount');
        $total_credit_amount = LeftPaddedDecimalWithoutDelimiterColumnFactory::create(SELF::DEFAULT_ZERO, $length = 13, $label = 'total_credit_amount');
        $total_debit_count = LeftPaddedZerofillStringColumnFactory::create(SELF::DEFAULT_ZERO, $length = 7, $label = 'total_debit_count');
        $total_credit_count = LeftPaddedZerofillStringColumnFactory::create(SELF::DEFAULT_ZERO, $length = 7, $label = 'total_credit_count');

        switch( $transaction_code -> getString() ) {
            case SELF::TRANSACTION_CODE_DIRECT_DEBIT:
                $total_debit_amount = LeftPaddedDecimalWithoutDelimiterColumnFactory::create( $this -> getTotalPaymentAmount(), $length = 13 , $label = 'total_debit_amount');
                $total_debit_count = LeftPaddedZerofillStringColumnFactory::create( $this -> getBeneficiaryCount(), $length = 7, $label = 'total_debit_count');
                break;
            case SELF::TRANSACTION_CODE_MISC_CREDIT:
            case SELF::TRANSACTION_CODE_SALARY_CREDIT:
            case SELF::TRANSACTION_CODE_DIVIDEND_CREDIT:
            case SELF::TRANSACTION_CODE_REMITTANCE_CREDIT:
            case SELF::TRANSACTION_CODE_BILL_CREDIT:
                $total_credit_amount = LeftPaddedDecimalWithoutDelimiterColumnFactory::create( $this -> getTotalPaymentAmount(), $length = 13 , $label = 'total_credit_amount');
                $total_credit_count = LeftPaddedZerofillStringColumnFactory::create( $this -> getBeneficiaryCount(), $length = 7, $label = 'total_credit_count');
                break;
            default:
        }

        $columns = [
            'record_type'           => PresetStringColumnFactory::create(SELF::RECORD_TYPE, $label = 'record_type'),
            'total_debit_amount'    => $total_debit_amount,
            'total_credit_amount'   => $total_credit_amount,
            'total_debit_count'     => $total_debit_count,
            'total_credit_count'    => $total_credit_count,
            'old_hash_total'        => LeftPaddedZerofillStringColumnFactory::create(SELF::DEFAULT_ZERO, $length = 13, $label = 'old_hash_total'),
            'new_hash_total'        => LeftPaddedZerofillStringColumnFactory::create(SELF::getCheckSum(), $length = 13, $label = 'new_hash_total'),
            'filler'                => RightPaddedStringColumnFactory::create('', $length = 13 , $label = 'filler'),
        ];
        $line -> setColumns($columns);
        return $line;
    }

    private function getCheckSum()
    {
        return (int) SELF::getHeaderRecordCheckSum() + SELF::getDetailRecordsCheckSum();
    }

    private function getHeaderRecordCheckSum($B, $R, $A)
    {
        $B = SELF::getOriginatingBankCodeArray();
        $R = SELF::getOriginatingBranchCodeArray();
        $A = SELF::getOriginatingAccountNumberArray();

        $sum1 = (int) intval($B[0].$B[1]) * 2 +
                intval($R[0].$R[1]) * 3 +
                intval($A[0].$A[1]) * 4 +
                intval($A[4].$A[5]) * 5 +
                intval($A[8].$A[9]) * 6;

        $sum2 = (int) intval($B[2].$B[3]) * 9 +
                intval($R[2]) * 8 +
                intval($A[2].$A[3]) * 7 +
                intval($A[6].$A[7]) * 6 +
                intval($A[10]) * 5;

        return (int) $sum1 * $sum2;
    }

    private function getDetailRecordsCheckSum($)
    {
        $T = SELF::getTransactionCode();
        $sum4 = 0;

        foreach ($this -> beneficiaries as $beneficiary){

            $B = str_split(RightPaddedStringColumnFactory::create($beneficiary -> getBankCode(), 4, $label = 'receiving_bank_code') -> getString());
            $R = str_split(RightPaddedStringColumnFactory::create($beneficiary -> getBankBranchCode(), 3, $label = 'receiving_branch_code') -> getString());
            $A = str_split( SELF::replaceSpacesWithZeros( RightPaddedStringColumnFactory::create($beneficiary -> getAccountNumber(), 11, $label = 'receiving_account_number') -> getString() ) );
            $M = str_split( LeftPaddedDecimalWithoutDelimiterColumnFactory::create( $beneficiary -> getPaymentAmount(), $length = 11 , $label = 'receiving_amount') );

            $sum1 = (int) intval($B[0].$B[1]) * 1 +
                    intval($R[0].$R[1]) * 2 +
                    intval($A[0].$A[1]) * 3 +
                    intval($A[4].$A[5]) * 4 +
                    intval($A[8].$A[9]) * 5 +
                    intval($T[0]) * 6 +
                    intval($M[0].$M[1]) * 7 +
                    intval($M[4].$M[5]) * 8 +
                    intval($M[8].$M[9]) * 9;

            $sum2 = (int) intval($B[2].$B[3]) * 9 +
                    intval($R[2]) * 8 +
                    intval($A[2].$A[3]) * 7 +
                    intval($A[6].$A[7]) * 6 +
                    intval($A[10]) * 5 +
                    intval($T[1]) * 4 +
                    intval($M[2].$M[3]) * 3 +
                    intval($M[6].$M[7]) * 2 +
                    intval($M[10]) * 1;

            $sum3 = $sum1 * $sum2;
            $sum4 = $sum4 + $sum3;

        }
    }

    private function getOriginatingBankCodeArray()
    {
        $originating_bank_code = ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'originating_bank_code', $label = 'originating_bank_code', $default_value = SELF::DEFAULT_ZERO, $max_length = 4, $auto_trim = TRUE, $padding_type = Column::PADDING_ZEROFILL_LEFT) -> getString();
        $B = str_split($originating_bank_code);

        if ( count( $B ) != 4 ) throw new ACHProcessorColumnException('Error calculating hash total algorithm. Please make sure that the length of originating bank code is 4.');

        return $B;
    }

    private function getOriginatingBranchCodeArray()
    {
        $originating_branch_code = ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'originating_branch_code', $label = 'originating_branch_code', $default_value = SELF::DEFAULT_ZERO, $max_length = 3, $auto_trim = TRUE, $padding_type = Column::PADDING_ZEROFILL_LEFT) -> getString();
        $R = str_split($originating_branch_code);

        if ( count( $R ) != 3 ) throw new ACHProcessorColumnException('Error calculating hash total algorithm. Please make sure that the length of originating branch code is 3.');

        return $R;
    }

    private function getOriginatingAccountNumberArray()
    {
        $originating_account_number = ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'originating_account_number', $label = 'originating_account_number', $default_value = SELF::DEFAULT_ZERO, $max_length = 11, $auto_trim = TRUE, $padding_type = Column::PADDING_ZEROFILL_LEFT) -> getString();
        $A = str_split( SELF::replaceSpacesWithZeros($originating_account_number) );

        if ( count( $A ) != 11 ) throw new ACHProcessorColumnException('Error calculating hash total algorithm. Please make sure that the length of originating account number is 11.');

        return $A;
    }

    private function getTransactionCode()
    {
        $transaction_code = ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'transaction_code', $label = 'transaction_code', $default_value = SELF::TRANSACTION_CODE_MISC_CREDIT, $max_length = 2) -> getString();
        $T = str_split($transaction_code);

        if ( count( $T ) != 2 ) throw new ACHProcessorColumnException('Error calculating hash total algorithm. Please make sure that the length of transaction code is 2.');

        return $T;
    }

    private function replaceSpacesWithZeros( $value )
    {
        return str_replace(' ', '0', $value);
    }

}
