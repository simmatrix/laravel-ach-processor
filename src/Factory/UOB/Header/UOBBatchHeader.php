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
use Simmatrix\ACHProcessor\Factory\Column\PresetStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\RightPaddedStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\VariableLengthStringColumnFactory;


class UOBBatchHeader extends Header
{
    protected $columnDelimiter = "";

    /**
     * @return Line
     */
    public function getLine(){
        $line = new Line();
        $line -> setColumnDelimiter("");
        $columns = [
            'record_type'                   => PresetStringColumnFactory::create('1', $label = 'record_type'),
            'batch_no'                      => RightPaddedStringColumnFactory::create('', 20, $label = 'batch_no'),
            'payment_advice_header_line1'   => RightPaddedStringColumnFactory::create( '', 105, $label = 'payment_advice_header_line1'),
            'payment_advice_header_line2'   => RightPaddedStringColumnFactory::create( '', 105, $label = 'payment_advice_header_line2'),
            'filler'                        => RightPaddedStringColumnFactory::create('', 669, $label = 'filler')
        ];
        $line -> setColumns($columns);
        return $line;
    }

}
