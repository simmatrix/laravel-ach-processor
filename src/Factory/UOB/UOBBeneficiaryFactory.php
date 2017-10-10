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
    /**
     * @var an Eloquent Model
     */
    protected $model;
    /**
     * @param Model
     * @param String the key to read the configuration from
     * @return BeneficiaryLine
     */
    public static function create($beneficiary, $config_key){
        $beneficiary_lines = new BeneficiaryLine($beneficiary);
        $beneficiary_lines -> addLine(static::cashiersOrderFormatPaymentInstructionLine($beneficiary, $config_key));
        return $beneficiary_lines;
    }

    /**
     * @param BeneficiaryAdapterInterface
     * @param String The key to read the config from
     */
    public static function cashiersOrderFormatPaymentInstructionLine(BeneficiaryAdapterInterface $beneficiary, $config_key){
        $line = new Line($config_key);
        $line -> setColumnDelimiter("");

        $date_column = new DateColumn();
        $date_column -> setDate(static::getNextWorkingDay());
        $date_column -> setFormat('Ymd');

        $settlement_ac_no = ConfigurableStringColumnFactory::create($config = $line -> config, 'settlement_ac_no') -> getString();

        $columns = [
            'record_type'                       => PresetStringColumnFactory::create('2', $label = 'record_type'),
            'payment_type'                      => PresetStringColumnFactory::create('CHQ', $label = 'payment_type'),
            'payment_currency'                  => ConfigurableStringColumnFactory::create($config = $line -> config, 'payment_currency', $label = 'payment_currency'),
            'payment_amt'                       => LeftPaddedDecimalWithoutDelimiterColumnFactory::create( $beneficiary -> getPaymentAmount(), $fixed_length = 15 , $label = 'payment_amt'),
            'value_date'                        => RightPaddedStringColumnFactory::create( $date_column -> getString(), 8, $label = 'value_date'),
            'beneficiary_name1'                 => RightPaddedStringColumnFactory::create( $beneficiary -> getName1(), $max_length = 35 , $label = 'beneficiary_name1'),
            'beneficiary_name2'                 => RightPaddedStringColumnFactory::create( $beneficiary -> getName2(), $max_length = 35 , $label = 'beneficiary_name2'),
            'beneficiary_name3'                 => RightPaddedStringColumnFactory::create( $beneficiary -> getName3(), $max_length = 35 , $label = 'beneficiary_name3'),
            'beneficiary_add1'                  => RightPaddedStringColumnFactory::create( $beneficiary -> getAddress1(), $max_length = 35 , $label = 'beneficiary_add1'),
            'beneficiary_add2'                  => RightPaddedStringColumnFactory::create( $beneficiary -> getAddress2(), $max_length = 35 , $label = 'beneficiary_add2'),
            'beneficiary_add3'                  => RightPaddedStringColumnFactory::create( $beneficiary -> getAddress3(), $max_length = 35 , $label = 'beneficiary_add3'),
            'beneficiary_postcode'              => RightPaddedStringColumnFactory::create( $beneficiary -> getPostcode(), 15 , $label = 'beneficiary_postcode'),
            'beneficiary_countrycode'           => RightPaddedStringColumnFactory::create( $line -> config -> get('beneficiary_countrycode'), $length = 3, $label = 'beneficiary_countrycode'),
            'filler'                            => RightPaddedStringColumnFactory::create('', 6, $label = 'filler'),
            'settlement_ac_no'                  => LeftPaddedZerofillStringColumnFactory::create( $settlement_ac_no, 20, $label = 'settlement_ac_no'),
            'currency'                          => ConfigurableStringColumnFactory::create($config = $line -> config, 'payment_currency', $label = 'currency'),
            'handling_opt'                      => PresetStringColumnFactory::create('M', $label = 'handling_opt'),//288
            'mail_to_party'                     => PresetStringColumnFactory::create('BEN', $label = 'mail_to_party'),//291
            'mail_name_add1'                    => RightPaddedStringColumnFactory::create('', 35, $label = 'mail_name_add1'),//326
            'mail_name_add2'                    => RightPaddedStringColumnFactory::create('', 35, $label = 'mail_name_add2'),//361
            'mail_name_add3'                    => RightPaddedStringColumnFactory::create('', 35, $label = 'mail_name_add3'),//396
            'mail_name_add4'                    => RightPaddedStringColumnFactory::create('', 35, $label = 'mail_name_add4'),//431
            'mail_postcode'                     => RightPaddedStringColumnFactory::create('', 15, $label = 'mail_postcode'),//446
            'mail_countrycode'                  => RightPaddedStringColumnFactory::create('', 3, $label = 'mail_countrycode'),//449
            'filler1'                           => RightPaddedStringColumnFactory::create('', 50, $label = 'filler1'),//499
            'payment_indicator'                 => RightPaddedStringColumnFactory::create('', 1, $label = 'payment_indicator'),
            'print_mode'                        => PresetStringColumnFactory::create('P', $label = 'print_mode'),
            'beneficiary_id'                    => RightPaddedStringColumnFactory::create('', 20, $label = 'beneficiary_id'),
            'print_advice_instruction'          => PresetStringColumnFactory::create('1', $label = 'print_advice_instruction'),
            'filler2'                           => RightPaddedStringColumnFactory::create('', 198, $label = 'filler2'),
            'payer_name1'                       => RightPaddedStringColumnFactory::create('', 35, $label = 'payer_name1'),
            'payer_name2'                       => RightPaddedStringColumnFactory::create('', 35, $label = 'payer_name2'),
            'payer_refno'                       => RightPaddedStringColumnFactory::create( $beneficiary -> getPaymentId(), 30, $label = 'payer_refno'),
            'email'                             => RightPaddedStringColumnFactory::create( $beneficiary -> getEmail(), 50 , $label = 'email'),
            'fax'                               => RightPaddedStringColumnFactory::create('', 20, $label = 'fax'),
            'filler3'                           => RightPaddedStringColumnFactory::create('', 10, $label = 'filler3'),
        ];
        $line -> setColumns($columns);
        return $line;
    }

    /**
     * Return the next working day that is in the future.
     * @return Datetime
     */
    public static function getNextWorkingDay(){
       $invalid_day = array('sat','sun');
       $tomorrow_unix = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
       $tomorrow_date = date('Ymd',$tomorrow_unix);
       $tomorrow_day = strtolower(date('D',$tomorrow_unix));

       $datetime = new \DateTime();
       if(!in_array($tomorrow_day,$invalid_day)){
           $datetime -> setTimestamp($tomorrow_unix);
           return $datetime;
       }else{
           switch($tomorrow_day){
               case 'sat':
                   $tomorrow_unix = mktime(0, 0, 0, date("m"), date("d")+3, date("y"));
                   break;
               case 'sun':
                   $tomorrow_unix = mktime(0, 0, 0, date("m"), date("d")+2, date("y"));
                   break;
           }
           $datetime -> setTimestamp($tomorrow_unix);
           return $datetime;
       }
    }


}
