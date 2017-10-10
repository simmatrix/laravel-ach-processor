<?php

namespace Simmatrix\ACHProcessor\Factory\Column;

use Simmatrix\ACHProcessor\Column\Column;

class LeftPaddedZerofillStringColumnFactory
{
    /**
     * Creates a column with a string value that is left padded with zeros.
     * A string "100" with a length 7 will result in "0000100"
     * @param String. Any passed value will be cast to a string. E.g. 0 will be cast to "0"
     * @param int. The fixed length of the string
     * @param String An optional label for the column, used in error messages.
     * @return Column
     */
    public static function create($value, $length, $label = ''){
        $value = (string)$value;
        $column = new Column();
        $column -> setFixedLength($length);
        $column -> setLabel($label);
        $column -> setValue($value);
        $column -> setPaddingType(Column::PADDING_ZEROFILL_LEFT);
        return $column;
    }
}
