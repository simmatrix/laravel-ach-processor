<?php

namespace Simmatrix\ACHProcessor\Factory\Column;

use Simmatrix\ACHProcessor\Column\Column;

class LeftPaddedDecimalWithoutDelimiterColumnFactory
{
    /**
     * This column has a fixed length and contains a decimal value without a delimiter.
     * E.g. with a length of 6 and a value of 10, the final value would be "001000"
     * @param String. Any passed value will be cast to a string. E.g. 0 will be cast to "0"
     * @param int. The fixed length of the string
     * @param String An optional label for the column, used in error messages.
     * @return Column
     */
    public static function create($value, $length, $label = ''){
        //need to test this thoroughly.
        $value = (string)(round($value, 2)*100);
        $column = new Column();
        $column -> setFixedLength($length);
        $column -> setLabel($label);
        $column -> setValue($value);
        $column -> setPaddingType(Column::PADDING_ZEROFILL_LEFT);
        return $column;
    }
}
