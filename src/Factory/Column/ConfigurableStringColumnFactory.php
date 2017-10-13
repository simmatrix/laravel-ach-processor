<?php

namespace Simmatrix\ACHProcessor\Factory\Column;

use Simmatrix\ACHProcessor\Exceptions\ACHProcessorColumnException;
use Simmatrix\ACHProcessor\Column\Column;
use Simmatrix\ACHProcessor\Line\Line;
use Config;

class ConfigurableStringColumnFactory
{
    /**
     * Creates a column with a value from config file.
     * Length will be set to the length of the configured value.
     * It would be a string value that is right padded to the length. E.g. "Timothy" with a length of 10 would become "Timothy   "
     * @param Line the parent line
     * @param String    $config_value   A required config key. The resolved value will be cast to a string
     * @param String    $label          An optional label for the column, used in error messages
     * @param String    $default_value  An optional default value
     * @param Integer   $max_length     An optional maximum length to be applied to the value
     * @param Boolean   $auto_trim      An optional flag to determine whether to trim off extra characters should it has exceeded the maximum allowable length
     * @return Column
     */
    public static function create($config, $config_key, $label = '', $default_value = '', $max_length = NULL, $auto_trim = TRUE, $padding_type = Column::PADDING_RIGHT)
    {
        if( !$config -> has($config_key)){
            throw new ACHProcessorColumnException('Could not find the config option ' . $config_key);
        }

        $value = (string)$config -> get($config_key);

        $column = new Column();
        $column -> setLabel($label);
        $column -> setValue($value);
        $column -> setMaxLength($max_length);
        $column -> setFixedLength($max_length);
        $column -> setAutoTrim($auto_trim);
        $column -> setDefaultValue($default_value);
        $column -> setPaddingType($padding_type);
        return $column;
    }
}
