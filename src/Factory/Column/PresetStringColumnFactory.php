<?php

namespace Simmatrix\ACHProcessor\Factory\Column;

use Simmatrix\ACHProcessor\Column\Column;

class PresetStringColumnFactory
{
    /**
     * Creates a column with a preset value.
     * Length will be set to the length of the preset value.
     * @param String. Any passed value will be cast to a string. E.g. 0 will be cast to "0"
     * @param String An optional label for the column, used in error messages.
     * @return Column
     */
    public static function create($value, $label = ''){
        $value = (string)$value;
        $column = new Column();
        $column -> setDefaultValue($value);
        $column -> setLabel($label);
        return $column;
    }
}
