<?php

namespace Simmatrix\ACHProcessor\Factory\UOB;

use Simmatrix\ACHProcessor\Line\Line;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;
use Simmatrix\ACHProcessor\BeneficiaryLine;
use Simmatrix\ACHProcessor\Column\DateColumn;
use Simmatrix\ACHProcessor\Factory\Column\ConfigurableStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\EmptyColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedDecimalWithoutDelimiterColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedZerofillStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\PresetStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\RightPaddedStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\VariableLengthStringColumnFactory;

class UOBBeneficiaryFactory
{
    const RECORD_TYPE = '2';
    const BLANK_SPACE = '';

    const TRANSACTION_CODE_MISC_CREDIT = '20';
    const TRANSACTION_CODE_SALARY_CREDIT = '22';
    const TRANSACTION_CODE_DIVIDEND_CREDIT = '23';
    const TRANSACTION_CODE_REMITTANCE_CREDIT = '24';
    const TRANSACTION_CODE_BILL_CREDIT = '25';
    const TRANSACTION_CODE_DIRECT_DEBIT = '30';

    /**
     * @var an Eloquent Model
     */
    protected $model;
    /**
     * @param Model
     * @param String the key to read the configuration from
     * @return BeneficiaryLine
     */
    public static function create($beneficiary, $config_key, $payment_description)
    {
        $beneficiary_lines = new BeneficiaryLine($beneficiary);
        $beneficiary_lines -> setLine(static::getLine($beneficiary, $config_key, $payment_description));
        return $beneficiary_lines;
    }

    /**
     * @param BeneficiaryAdapterInterface
     * @param String The key to read the config from
     */
    public static function getLine(BeneficiaryAdapterInterface $beneficiary, $config_key, $payment_description)
    {
        $line = new Line($config_key);
        $line -> setColumnDelimiter("");

        $transaction_code = ConfigurableStringColumnFactory::create($config = $line -> config, $config_key = 'transaction_code', $label = 'transaction_code', $default_value = SELF::TRANSACTION_CODE_MISC_CREDIT, $max_length = 2);
        $reference = RightPaddedStringColumnFactory::create( SELF::BLANK_SPACE, $length = 12, $label = 'reference' );

        if ( $transaction_code -> getString() == SELF::TRANSACTION_CODE_DIRECT_DEBIT ) {
            $reference = ConfigurableStringColumnFactory::create($config = $line -> config, $config_key = 'reference', $label = 'reference', $default_value = SELF::BLANK_SPACE, $max_length = 12);
        }

        $columns = [
            'record_type'                 => PresetStringColumnFactory::create(SELF::RECORD_TYPE, $label = 'record_type'),
            'receiving_bank_code'         => LeftPaddedZerofillStringColumnFactory::create( $beneficiary -> getBankCode(), $length = 4, $label = 'receiving_bank_code' ),
            'receiving_branch_code'       => LeftPaddedZerofillStringColumnFactory::create( $beneficiary -> getBankBranchCode(), $length = 3, $label = 'receiving_branch_code' ),
            'receiving_account_number'    => RightPaddedStringColumnFactory::create( static::removeSpecialCharacters($beneficiary -> getAccountNumber()), $length = 11, $label = 'receiving_account_number' ),
            'receiving_account_name'      => RightPaddedStringColumnFactory::create( $beneficiary -> getPayeeName(), $length = 20, $label = 'receiving_account_name' ),
            'transaction_code'            => ConfigurableStringColumnFactory::create($config = $line -> config, $config_key = 'transaction_code', $label = 'transaction_code', $default_value = SELF::TRANSACTION_CODE_MISC_CREDIT, $max_length = 2),
            'amount'                      => LeftPaddedDecimalWithoutDelimiterColumnFactory::create( $beneficiary -> getPaymentAmount(), $fixed_length = 11 , $label = 'amount'),
            'particulars'                 => RightPaddedStringColumnFactory::create( strtoupper($payment_description), $length = 12, $label = 'particulars' ),
            'reference'                   => $reference,
            'filler'                      => RightPaddedStringColumnFactory::create(SELF::BLANK_SPACE, 4, $label = 'filler'),
        ];
        $line -> setColumns($columns);
        return $line;
    }

    private static function removeSpecialCharacters($value)
    {
        $value = str_replace(' ', '-', $value);
        return preg_replace('/[^A-Za-z0-9]/', '', $value);
    }

}
