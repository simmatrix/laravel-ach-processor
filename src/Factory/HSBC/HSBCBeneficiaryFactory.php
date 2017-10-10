<?php

namespace Simmatrix\ACHProcessor\Factory\HSBC;

use Simmatrix\ACHProcessor\Line\Line;
use Simmatrix\ACHProcessor\Beneficiary;
use Simmatrix\ACHProcessor\BeneficiaryLine;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;
use Simmatrix\ACHProcessor\Factory\Column\RightPaddedStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedZerofillStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\PresetStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\ConfigurableStringColumnFactory;

class HSBCBeneficiaryFactory
{
    const SECOND_PARTY_RECORD_TYPE = 2; // 2 stands for second party
    const MAINTENANCE_CODE = '';
    const AUTOPAY_CURRENCY = 'MYR';
    const PAYMENT_METHOD = '';
    const CHEQUE_TYPE = '';
    const PAYMENT_CURRENCY = 'MYR';
    const CORRESPONDING_BANK = 22; // 22 FOR HSBC
    const CORRESPONDING_BRANCH = '';
    const NEXT_PAYMENT_DATE = ''; // Leave blank for AutoPlan 1
    const RESERVED = '';

    /**
     * @var an Eloquent Model
     */
    protected $model;
    /**
     * @param BeneficiaryAdapterInterface
     * @param String The key to read the config from
     * @return BeneficiaryLine
     */
    public static function create(BeneficiaryAdapterInterface $beneficiary, $config_key)
    {
        $beneficiary_line = new BeneficiaryLine($beneficiary);
        $beneficiary_line -> setLine(static::getLine($beneficiary, $config_key));
        return $beneficiary_line;
    }

    /**
     * @param BeneficiaryAdapterInterface
     * @param String The key to read the config from
     */
    public static function getLine(BeneficiaryAdapterInterface $beneficiary, $config_key )
    {
        $line = new Line($config_key);

        $columns = [
            'record_type'                       => PresetStringColumnFactory::create(SELF::SECOND_PARTY_RECORD_TYPE, $label = 'record_type'),
            'second_party_id'                   => RightPaddedStringColumnFactory::create($beneficiary -> getUserId(), $length = 12, $label = 'second_party_id'),
            'maintenance_code'                  => PresetStringColumnFactory::create(SELF::MAINTENANCE_CODE, $label = 'maintenance_code'),
            'autopay_currency'                  => ConfigurableStringColumnFactory::create($config = $line -> config, $config_key = 'autopay_currency', $label = 'autopay_currency', $default_value = SELF::AUTOPAY_CURRENCY, $max_length = 3),
            'payment_amount'                    => LeftPaddedZerofillStringColumnFactory::create(number_format($beneficiary -> getPaymentAmount(), 2, '.', ''), $max_length = 16, $label = 'payment_amount'),
            'payment_method'                    => RightPaddedStringColumnFactory::create(SELF::PAYMENT_METHOD, $length = 2, $label = 'payment_method'),
            'cheque_type'                       => RightPaddedStringColumnFactory::create(SELF::CHEQUE_TYPE, $length = 3, $label = 'cheque_type'),
            'payment_currency'                  => ConfigurableStringColumnFactory::create($config = $line -> config, $config_key = 'payment_currency', $label = 'payment_currency', $default_value = SELF::PAYMENT_CURRENCY, $max_length = 3),
            'corresponding_bank'                => RightPaddedStringColumnFactory::create(SELF::CORRESPONDING_BANK, $length = 4, $label = 'corresponding_bank'),
            'corresponding_branch'              => RightPaddedStringColumnFactory::create(SELF::CORRESPONDING_BRANCH, $length = 4, $label = 'corresponding_branch'),
            'second_party_account_number'       => RightPaddedStringColumnFactory::create($beneficiary -> getAccountNumber(), $length = 20, $label = 'second_party_account_number'),
            'next_payment_date'                 => RightPaddedStringColumnFactory::create(SELF::NEXT_PAYMENT_DATE, $length = 8, $label = 'next_payment_date'),
            'second_party_description'          => RightPaddedStringColumnFactory::create($beneficiary -> getPayeeName(), $length = 20, $label = 'second_party_description'),
            'second_party_reference'            => RightPaddedStringColumnFactory::create($beneficiary -> getSecondPartyReference(), $length = 12, $label = 'second_party_reference'),
            'reserved'                          => RightPaddedStringColumnFactory::create(SELF::RESERVED, $length = 26, $label = 'reserved'),
        ];

        $line -> setColumns($columns);
        return $line;
    }
}
