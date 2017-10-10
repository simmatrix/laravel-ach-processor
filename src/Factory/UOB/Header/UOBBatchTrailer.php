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
    protected $columnDelimiter = "";

    /**
     * @return Line
     */
    public function getLine(){
        $line = new Line();
        $line -> setColumnDelimiter("");
        $columns = [
            'record_type'       => PresetStringColumnFactory::create('9', $label = 'record_type'),
            'no_trans'          => LeftPaddedZerofillStringColumnFactory::create( $this -> getBeneficiaryCount(), $length = 8, $label = 'no_trans'),
            'ttl_payment_amt'   => LeftPaddedDecimalWithoutDelimiterColumnFactory::create( $this -> getTotalPaymentAmount(), $length = 15 , $label = 'ttl_payment_amt'),
            'filler'            => RightPaddedStringColumnFactory::create('', $length = 876 , $label = 'filler')
        ];
        $line -> setColumns($columns);
        return $line;
    }

}
