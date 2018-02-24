<?php

namespace Simmatrix\ACHProcessor\Factory\HSBC\Header;

use Simmatrix\ACHProcessor\Line\Line;
use Simmatrix\ACHProcessor\Column\Column;
use Simmatrix\ACHProcessor\Services\CoreHelper;
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

class HSBCBatchHeaderIFile extends \Simmatrix\ACHProcessor\Line\Header implements Stringable
{
    /**
     * Instruction Type
     * "ACH-CR" - ACH Payment
     * "ACH-DR" - ACH Collection
     */
    const DEFAULT_INSTRUCTION_TYPE = 'ACH-CR';
    const DEFAULT_CURRENCY = 'MYR';
    const DEFAULT_FIRST_PARTY_ACCOUNT_COUNTRY_CODE = 'MY';
    const DEFAULT_FIRST_PARTY_ACCOUNT_INSTITUTION_CODE = 'HBMB';
    const DEFAULT_FIRST_PARTY_ACCOUNT_CURRENCY = 'MYR';
    const DEFAULT_PAYMENT_SET_NUMBER = 'C01';

    /**
     * @param Beneficiary
     * @return BeneficiaryLine
     */
    public function getLine()
    {
        $line = new Line();
        $line -> setColumnDelimiter(",");
        $helper = new CoreHelper( $this -> configKey );

        $columns = [
            'record_type'                           => PresetStringColumnFactory::create('BATHDR', $label = 'record_type'),
            'instruction_type'                      => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'instruction_type', $label = 'instruction_type', $default_value = SELF::DEFAULT_INSTRUCTION_TYPE, $max_length = 16, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'total_instructions'                    => VariableLengthStringColumnFactory::create( $this -> getBeneficiaryCount(), $max_length = 7, $label = 'total_instructions'),
            'batch_reference'                       => EmptyColumnFactory::create( $label = 'batch_reference'),
            'filter_1'                              => EmptyColumnFactory::create( $label = 'filter_1'),
            'filter_2'                              => EmptyColumnFactory::create( $label = 'filter_2'),
            'filter_3'                              => EmptyColumnFactory::create( $label = 'filter_3'),
            'filter_4'                              => EmptyColumnFactory::create( $label = 'filter_4'),
            'filter_5'                              => EmptyColumnFactory::create( $label = 'filter_5'),
            'constant_eye_catcher'                  => PresetStringColumnFactory::create('@1ST@', $label = 'constant_eye_catcher'),
            'value_date'                            => VariableLengthStringColumnFactory::create( $helper -> getEffectivePaymentDate(), $max_length = 8, $label = 'value_date'),
            'first_party_account'                   => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'first_party_account', $label = 'first_party_account', $default_value = PARENT::DEFAULT_MISSING_VALUE, $max_length = 12, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'transaction_currency'                  => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'transaction_currency', $label = 'transaction_currency', $default_value = SELF::DEFAULT_CURRENCY, $max_length = 3, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'transaction_amount'                    => EmptyColumnFactory::create( $label = 'transaction_amount'),
            'template_mode'                         => EmptyColumnFactory::create( $label = 'template_mode'),
            'batch_template_id'                     => EmptyColumnFactory::create( $label = 'batch_template_id'),
            'first_party_account_country_code'      => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'first_party_account_country_code', $label = 'first_party_account_country_code', $default_value = SELF::DEFAULT_FIRST_PARTY_ACCOUNT_COUNTRY_CODE, $max_length = 2, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'first_party_account_institution_code'  => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'first_party_account_institution_code', $label = 'first_party_account_institution_code', $default_value = SELF::DEFAULT_FIRST_PARTY_ACCOUNT_INSTITUTION_CODE, $max_length = 4, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'first_party_account_currency'          => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'first_party_account_currency', $label = 'first_party_account_currency', $default_value = SELF::DEFAULT_FIRST_PARTY_ACCOUNT_CURRENCY, $max_length = 3, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'payment_amount_debit_acc_currency'     => EmptyColumnFactory::create( $label = 'payment_amount_debit_acc_currency'),
            'first_party_name'                      => EmptyColumnFactory::create( $label = 'first_party_name'),
            'first_party_info_1'                    => EmptyColumnFactory::create( $label = 'first_party_info_1'),
            'first_party_info_2'                    => EmptyColumnFactory::create( $label = 'first_party_info_2'),
            'first_party_info_3'                    => EmptyColumnFactory::create( $label = 'first_party_info_3'),
            'first_party_info_4'                    => EmptyColumnFactory::create( $label = 'first_party_info_4'),
            'payment_code'                          => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'payment_set_number', $label = 'payment_set_number', $default_value = SELF::DEFAULT_PAYMENT_SET_NUMBER, $max_length = 3, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'reference_line_1'                      => VariableLengthStringColumnFactory::create($helper -> getFileReference(), $max_length = 24, $label = 'reference_line_1')
        ];
        $line -> setColumns($columns);
        return $line;
    }
}
