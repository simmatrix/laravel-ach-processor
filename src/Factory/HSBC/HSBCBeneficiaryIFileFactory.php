<?php

namespace Simmatrix\ACHProcessor\Factory\HSBC;

use Simmatrix\ACHProcessor\Line\Line;
use Simmatrix\ACHProcessor\Column\Column;
use Simmatrix\ACHProcessor\Services\CoreHelper;
use Simmatrix\ACHProcessor\Beneficiary;
use Simmatrix\ACHProcessor\BeneficiaryLines;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;
use Simmatrix\ACHProcessor\Factory\Column\RightPaddedStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedZerofillStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\PresetStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\ConfigurableStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\VariableLengthStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\EmptyColumnFactory;

class HSBCBeneficiaryIFileFactory
{
    const DEFAULT_DOMICILE_OF_EMAIL_RECIPIENT = 'MY';

    /**
     * @var an Eloquent Model
     */
    protected $model;
    /**
     * @param BeneficiaryAdapterInterface
     * @param String The key to read the config from
     * @return BeneficiaryLine
     */
    public static function create(BeneficiaryAdapterInterface $beneficiary, $config_key, $payment_description){
        $beneficiary_lines = new BeneficiaryLines($beneficiary);
        $beneficiary_lines -> addLine(static::createBeneficiaryRecordLine($beneficiary, $config_key, $payment_description));
        $beneficiary_lines -> addLine(static::createAdvisingRecordLine($beneficiary, $config_key));
        return $beneficiary_lines;
    }

    /**
     * @param BeneficiaryAdapterInterface
     * @param String The key to read the config from
     */
    public static function createBeneficiaryRecordLine( BeneficiaryAdapterInterface $beneficiary, $config_key, $payment_description ) 
    {
        $line = new Line( $config_key );
        $line -> setColumnDelimiter(",");
        $helper = new CoreHelper( $config_key );

        $columns = [
            'record_type'                       => PresetStringColumnFactory::create('SECPTY', $label = 'record_type'),
            'second_party_account_number'       => VariableLengthStringColumnFactory::create( $beneficiary -> getAccountNumber(), $max_length = 20, $label = 'second_party_account_number'),
            'second_party_name'                 => VariableLengthStringColumnFactory::create( $beneficiary -> getPayeeName(), $max_length = 20, $label = 'second_party_name'),
            'second_party_identifier'           => VariableLengthStringColumnFactory::create( $beneficiary -> getSecondPartyIdentifier(), $max_length = 12, $label = 'second_party_identifier'),
            'beneficiary_bank_number'           => VariableLengthStringColumnFactory::create( $beneficiary -> getBankCode(), $max_length = 8, $label = 'beneficiary_bank_number'),
            'beneficiary_bank_branch_number'    => VariableLengthStringColumnFactory::create( $beneficiary -> getBankBranchCode(), $max_length = 4, $label = 'beneficiary_bank_branch_number'),
            'transaction_code'                  => EmptyColumnFactory::create( $label = 'transaction_code'),
            'payment_amount'                    => VariableLengthStringColumnFactory::create( number_format($beneficiary -> getPaymentAmount(), 2, '.', ''), $max_length = 16, $label = 'payment_amount'),
            'entry_value_date'                  => VariableLengthStringColumnFactory::create( $helper -> getEffectivePaymentDate(), $max_length = 8, $label = 'entry_value_date'),
            'second_party_reference'            => VariableLengthStringColumnFactory::create( $beneficiary -> getSecondPartyReference(), $max_length = 12, $label = 'second_party_reference'),
            'information_line_1'                => EmptyColumnFactory::create( $label = 'information_line_1'),
            'information_line_2'                => EmptyColumnFactory::create( $label = 'information_line_2'),
            'information_line_3'                => EmptyColumnFactory::create( $label = 'information_line_3'),
            'information_line_4'                => EmptyColumnFactory::create( $label = 'information_line_4'),
            'advice_indicator'                  => PresetStringColumnFactory::create('Y', $label = 'advice_indicator'),
            'wht_indicator'                     => PresetStringColumnFactory::create('N', $label = 'wht_indicator'),
            'filler_1'                          => EmptyColumnFactory::create( $label = 'filler_1'),
            'filler_2'                          => EmptyColumnFactory::create( $label = 'filler_2'),
            'filler_3'                          => EmptyColumnFactory::create( $label = 'filler_3'),
            'filler_4'                          => EmptyColumnFactory::create( $label = 'filler_4'),
            'filler_5'                          => EmptyColumnFactory::create( $label = 'filler_5'),
        ];

        $line -> setColumns($columns);
        return $line;
    }

    /**
     * @param BeneficiaryAdapterInterface
     * @param String The key to read the config from
     * @return Line a line
     */
    public static function createAdvisingRecordLine(BeneficiaryAdapterInterface $beneficiary, $config_key)
    {
        $line = new Line($config_key);
        $line -> setColumnDelimiter(",");

        $columns = [
            'record_type'                           => PresetStringColumnFactory::create('ADV', $label = 'record_type'),
            'advice_recepient_id'                   => EmptyColumnFactory::create( $label = 'advice_recepient_id'),
            'action_flag'                           => EmptyColumnFactory::create( $label = 'action_flag'),
            'recipient_template_desc'               => EmptyColumnFactory::create( $label = 'recipient_template_desc'),
            'user_id'                               => EmptyColumnFactory::create( $label = 'user_id'),
            'user_first_name'                       => EmptyColumnFactory::create( $label = 'user_first_name'),
            'user_last_name'                        => EmptyColumnFactory::create( $label = 'user_last_name'),
            'number_of_recipient'                   => PresetStringColumnFactory::create('1', $label = 'number_of_recipient'),
            'recipient_item_no'                     => PresetStringColumnFactory::create('1', $label = 'recipient_item_no'),
            'recipient_name'                        => VariableLengthStringColumnFactory::create( $beneficiary -> getPayeeName(), $max_length = 600 , $label = 'recipient_name'),
            'recipient_title'                       => VariableLengthStringColumnFactory::create( $beneficiary -> getRecipientTitleFlag(), $max_length = 1, $label = 'recipient_title'),
            'recipient_title_desc'                  => VariableLengthStringColumnFactory::create( $beneficiary -> getRecipientTitleDescription(), $max_length = 240, $label = 'recipient_title_desc'),
            'action_code'                           => EmptyColumnFactory::create( $label = 'action_code'),
            'template_id'                           => EmptyColumnFactory::create( $label = 'template_id'),
            'template_status'                       => EmptyColumnFactory::create( $label = 'template_status'),
            'template_timetamp'                     => EmptyColumnFactory::create( $label = 'template_timetamp'),
            'advice_format'                         => PresetStringColumnFactory::create('F', $label = 'advice_format'),
            'email_channel_select_flag'             => PresetStringColumnFactory::create('Y', $label = 'email_channel_select_flag'),
            'email_format'                          => PresetStringColumnFactory::create('1', $label = 'email_format'),
            'email_address'                         => VariableLengthStringColumnFactory::create( $beneficiary -> getEmail(), $max_length = 70 , $label = 'email_address'),
            'alternate_email_address'               => EmptyColumnFactory::create( $label = 'alternate_email_address'),
            'domicile_of_email_recipient'           => ConfigurableStringColumnFactory::create($config = $line -> config, $config_key = 'domicile_of_email_recipient', $label = 'domicile_of_email_recipient', $default_value = SELF::DEFAULT_DOMICILE_OF_EMAIL_RECIPIENT, $max_length = 2, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'email_threshold_currency'              => EmptyColumnFactory::create( $label = 'email_threshold_currency'),
            'email_threshold_amount'                => EmptyColumnFactory::create( $label = 'email_threshold_amount'),
        ];
        $line -> setColumns($columns);
        return $line;
    }
}
