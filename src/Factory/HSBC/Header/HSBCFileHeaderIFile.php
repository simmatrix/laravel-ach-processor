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

class HSBCFileHeaderIFile extends \Simmatrix\ACHProcessor\Line\Header
{
    /**
     * The authorization type:
     * A: Preauthorized
     * P: Instruction level authorization
     * F: File level authorization (summary)
     * V: File level authorization (detail)
     * @var string
     */
    const DEFAULT_AUTHORIZATION_TYPE = 'P'; 

    /**
     * How many lines make up a beneficiary entry
     * @var int
     */
    protected $beneficiaryLineHeight = 2;
    
    /**
     * @return Line
     */
    public function getLine(){
        $line = new Line();
        $line -> setColumnDelimiter(",");
        $helper = new CoreHelper( $this -> configKey );

        $columns = [
            'record_type'               => PresetStringColumnFactory::create('IFH', $label = 'record_type'),
            'file_format'               => PresetStringColumnFactory::create('IFILE', $label = 'file_format'),
            'file_type'                 => PresetStringColumnFactory::create('CSV', $label = 'file_type'),
            'hexagon_abc_customer_id'   => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'hexagon_abc_customer_id', $label = 'hexagon_abc_customer_id', $default_value = PARENT::DEFAULT_MISSING_VALUE, $max_length = 11, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'hsbcnet_id'                => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'hsbcnet_id', $label = 'hsbcnet_id', $default_value = PARENT::DEFAULT_MISSING_VALUE, $max_length = 18, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'file_reference'            => VariableLengthStringColumnFactory::create($helper -> getFileReference(), $length = 24, $label = 'file_reference'),
            'file_creation_date'        => VariableLengthStringColumnFactory::create(date('Y/m/d'), $length = 10, $label = 'file_creation_date'),
            'file_creation_time'        => VariableLengthStringColumnFactory::create(date('H:i:s'), $length = 8, $label = 'file_creation_time'),
            'authorization_type'        => ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'authorization_type', $label = 'authorization_type', $default_value = SELF::DEFAULT_AUTHORIZATION_TYPE, $max_length = 1, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE),
            'file_version'              => PresetStringColumnFactory::create('1.0', $label = 'file_version'),
            'record_count'              => VariableLengthStringColumnFactory::create($this -> getTotalLines(), 7 , $label = 'record_count'),
        ];
        $line -> setColumns($columns);
        return $line;
    }
}
