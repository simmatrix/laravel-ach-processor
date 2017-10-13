<?php

namespace Simmatrix\ACHProcessor\Factory\UOB\Header;

use Simmatrix\ACHProcessor\Line\Line;
use Simmatrix\ACHProcessor\Line\Header;
use Simmatrix\ACHProcessor\Beneficiary;
use Simmatrix\ACHProcessor\BeneficiaryLine;
use Simmatrix\ACHProcessor\Column\Date;
use Simmatrix\ACHProcessor\Column\Column;
use Simmatrix\ACHProcessor\Factory\Column\ConfigurableStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\EmptyColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedDecimalWithoutDelimiterColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\PresetStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\RightPaddedStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\VariableLengthStringColumnFactory;


class UOBBatchHeader extends Header
{
    const RECORD_TYPE = '1';
    const SERVICE_TYPE = 'IBGINORM'; // This is normal service. Another option is 'IBGIEXP' which is express service, of which all receiving accounts must belong to UOB
    const BLANK_SPACE = '';
    const DEFAULT_ZERO = '0';
    const HAS_INDICATOR = 2; // 2 Tto indicate to validate for new hash total in Batch trailer

    protected $columnDelimiter = "";

    /**
     * @return Line
     */
    public function getLine(){
        $line = new Line();
        $line -> setColumnDelimiter("");
        $columns = [
            'record_type'                   => PresetStringColumnFactory::create(self::RECORD_TYPE, $label = 'record_type'),
            'service_type'                  => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'service_type', $label = 'service_type', $default_value = SELF::SERVICE_TYPE, $max_length = 10),
            'originating_bank_code'         => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'originating_bank_code', $label = 'originating_bank_code', $default_value = SELF::DEFAULT_ZERO, $max_length = 4, $auto_trim = TRUE, $padding_type = Column::PADDING_ZEROFILL_LEFT),
            'originating_branch_code'       => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'originating_branch_code', $label = 'originating_branch_code', $default_value = SELF::DEFAULT_ZERO, $max_length = 3, $auto_trim = TRUE, $padding_type = Column::PADDING_ZEROFILL_LEFT),
            'originating_account_number'    => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'originating_account_number', $label = 'originating_account_number', $default_value = SELF::DEFAULT_ZERO, $max_length = 11, $auto_trim = TRUE, $padding_type = Column::PADDING_ZEROFILL_LEFT),
            'originating_account_name'      => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'originating_account_name', $label = 'originating_account_name', $default_value = SELF::BLANK_SPACE, $max_length = 20),
            'creation_date'                 => RightPaddedStringColumnFactory::create( date('Ymd'), 8, $label = 'creation_date'),
            'value_date'                    => RightPaddedStringColumnFactory::create( SELF::getValueDate(), 8, $label = 'value_date'),
            'filler_1'                      => RightPaddedStringColumnFactory::create(SELF::BLANK_SPACE, 5, $label = 'filler_1'),
            'hash_indicator'                => PresetStringColumnFactory::create(self::HAS_INDICATOR, $label = 'hash_indicator'),
            'filler_2'                      => RightPaddedStringColumnFactory::create(SELF::BLANK_SPACE, 9, $label = 'filler_2'),
        ];
        $line -> setColumns($columns);
        return $line;
    }

    /**
     * Return the next working day that is in the future.
     * @return Datetime
     */
    private function getValueDate()
    {
        $effective_working_days = intval(ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'effective_working_days', $label = 'effective_working_days', 1, $max_length = 2) -> getString());

        $invalid_day = array('sat','sun');

        $targeted_unix = mktime(0, 0, 0, date("m"), date("d")+$effective_working_days, date("y"));
        $targeted_day = strtolower(date('D',$targeted_unix));

        $datetime = new \DateTime();
        if(!in_array($targeted_day,$invalid_day)){
           $datetime -> setTimestamp($targeted_unix);
           return $datetime -> format('Ymd');
        }else{
           switch($targeted_day){
               case 'sat':
                   $targeted_unix = mktime(0, 0, 0, date("m"), date("d")+$effective_working_days+2, date("y"));
                   break;
               case 'sun':
                   $targeted_unix = mktime(0, 0, 0, date("m"), date("d")+$effective_working_days+1, date("y"));
                   break;
           }
           $datetime -> setTimestamp($targeted_unix);
           return $datetime -> format('Ymd');
        }
    }

}
