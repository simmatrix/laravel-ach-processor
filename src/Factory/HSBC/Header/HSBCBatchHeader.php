<?php

namespace Simmatrix\ACHProcessor\Factory\HSBC\Header;

use Simmatrix\ACHProcessor\Line\Line;
use Simmatrix\ACHProcessor\Stringable;
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


class HSBCBatchHeader extends \Simmatrix\ACHProcessor\Line\Header implements Stringable
{
    const FIRST_PARTY_RECORD_TYPE = 1; // 1 stands for first party
    const COUNTRY_CODE = 'MY';
    const GROUP_MEMBER = 'HSBC';
    const PAYMENT_SET_NUMBER = 'C01';
    const PAYMENT_TYPE = 'APO'; // Auto Pay Out, means debit 1st party, credit 2nd party
    const PAYMENT_SET_MAINTENANCE_MODE = ''; // to be left blank, indicating no checking would be done
    const HEXAGON_CUSTOMER_ID = ''; // For EBS Payment
    const HEXAGON_ACCOUNT_ID = '';
    const RESERVED = '';
    const AUTOPLAN_TYPE = 1;

    /**
     * @param Beneficiary
     * @return BeneficiaryLine
     */
    public function getLine()
    {
        $line = new Line();
        $columns = [
            'record_type'                       => PresetStringColumnFactory::create(SELF::FIRST_PARTY_RECORD_TYPE, $label = 'record_type'),
            'country_code'                      => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'country_code', $label = 'country_code', $default_value = SELF::COUNTRY_CODE, $max_length = 2),
            'group_member'                      => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'group_member', $label = 'group_member', $default_value = SELF::GROUP_MEMBER, $max_length = 4),
            'first_party_account_branch'        => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'first_party_account_branch', $label = 'first_party_account_branch', $default_value = '', $max_length = 3),
            'first_party_account_serial'        => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'first_party_account_serial', $label = 'first_party_account_serial', $default_value = '', $max_length = 6),
            'first_party_account_suffix'        => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'first_party_account_suffix', $label = 'first_party_account_suffix', $default_value = '', $max_length = 3),
            'payment_set_number'                => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'payment_set_number', $label = 'payment_set_number', $default_value = SELF::PAYMENT_SET_NUMBER, $max_length = 3),
            'batch_count_total'                 => LeftPaddedZerofillStringColumnFactory::create($this -> getBeneficiaryCount(), $max_length = 6, $label = 'batch_count_total'),
            'batch_amount_hash_total'           => LeftPaddedZerofillStringColumnFactory::create(number_format($this -> getTotalPaymentAmount(), 2, '.', ''), $max_length = 17, $label = 'batch_amount_hash_total'),
            'next_payment_date'                 => PresetStringColumnFactory::create(date('Ymd'), $label = 'next_payment_date'),
            'payment_type'                      => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'payment_type', $label = 'payment_type', $default_value = SELF::PAYMENT_TYPE, $max_length = 3),
            'payment_description'               => RightPaddedStringColumnFactory::create($this -> getPaymentDescription(), $length = 24, $label = 'payment_description'),
            'payment_set_maintenance_mode'      => PresetStringColumnFactory::create(SELF::PAYMENT_SET_MAINTENANCE_MODE, $label = 'payment_set_maintenance_mode'),
            'hexagon_customer_id'               => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'hexagon_customer_id', $label = 'hexagon_customer_id', $default_value = SELF::HEXAGON_CUSTOMER_ID, $max_length = 12),
            'hexagon_account_id'                => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'hexagon_account_id', $label = 'hexagon_customer_id', $default_value = SELF::HEXAGON_CUSTOMER_ID, $max_length = 12),
            'reserved'                          => RightPaddedStringColumnFactory::create(SELF::RESERVED, $length = 37, $label = 'reserved'),
            'autoplan_type'                     => PresetStringColumnFactory::create(SELF::AUTOPLAN_TYPE, $label = 'autoplan_type'),
        ];
        $line -> setColumns($columns);
        return $line;
    }
}
