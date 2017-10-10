<?php

namespace Simmatrix\ACHProcessor\Factory\Column;

use Simmatrix\ACHProcessor\Column\Column;

class VariableLengthStringColumnFactory
{
    /**
     * Creates a column with a string value. Must have a maximum length
     * @param String. Any passed value will be cast to a string. E.g. 0 will be cast to "0"
     * @param int. The maximum length of the string
     * @param String An optional label for the column, used in error messages.
     * @return Column
     */
    public static function create($value, $length, $label = ''){
        $value = (string)$value;
        $column = new Column();
        $column -> setLabel($label);
        $column -> setMaxLength($length);
        $column -> setValue($value);
        $column -> setPaddingType(Column::PADDING_NONE);
        return $column;
    }
}
