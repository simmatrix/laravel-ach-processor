<?php
namespace Simmatrix\ACHProcessor\Column;

use Simmatrix\ACHProcessor\Stringable;
use Simmatrix\ACHProcessor\Exceptions\ACHProcessorColumnException;

class Column implements Stringable
{
    const PADDING_NONE = 'padding_none';

    // Pads with zeros from the left. E.g. the value "2" with a length of 3 will result in "002"
    const PADDING_ZEROFILL_LEFT = 'padding_zerofill_left';

    // Pads with spaces to the right. E.g. the value "2" with a length of 3 will result in "2  "
    const PADDING_RIGHT = 'padding_spaces_right';

    /**
     * @var mixed The value for this column
     */
    protected $value;

    /**
     * @var mixed The default value for this column.
     */
    protected $defaultValue;

    /**
     * @var int The fixed length of the value
     */
    protected $fixedLength = null;

    /**
     * @var int The maximum length of the value
     */
    protected $maxLength = null;

    /**
     * @var boolean A flag whether to chop off extra characters automatically without throwing errors
     */
    protected $autoTrim = FALSE;

    /**
     * @var String An optional label that is used for error messages.
     */
    protected $label = null;

    /**
     * @var String
     */
    protected $paddingType = self::PADDING_NONE;

    /**
     * Get the column's value in a padded form
     * @return mixed
     */
    public function getPaddedValue( $value )
    {
        if( $this -> fixedLength == null && $this -> paddingType !== self::PADDING_NONE ) {
            throw new ACHProcessorColumnException(sprintf("Padding %s was set for the column %s, but no fixedLength was set.", $this -> paddingType, __CLASS__));
        }
        switch($this -> paddingType) {
            case self::PADDING_ZEROFILL_LEFT:
                return str_pad($value, $this -> fixedLength, "0", STR_PAD_LEFT);
            case self::PADDING_RIGHT:
                return str_pad($value, $this -> fixedLength);
            default:
                return $value;
        }
    }

    /**
     * Setting a default value
     * @var mixed
     */
    public function setDefaultValue($default_value)
    {
        $this -> defaultValue = $default_value;
    }

    /**
     * If the value is empty, then get the default value, then pad it before returning
     * @return mixed
     */
    public function getString()
    {
        $value = null;
        if( $this -> value === null || strlen($this -> value) == 0 ) {
            if( $this -> defaultValue !== null ) {
                $value = $this -> defaultValue;
            }
        } else {
            $value = $this -> value;
        }
        return $this -> getPaddedValue($value);
    }

    /**
     * Setting a fixed length
     * @param int
     * @return void
     */
    public function setFixedLength( $length = 0 )
    {
        $this -> fixedLength = $length;
    }

    /**
     * Setting a maximum length
     * @param int
     * @return void
     */
    public function setMaxLength( $length = 0 )
    {
        $this -> maxLength = $length;
    }

    /**
     * Set to auto trim off extra characters if it exceeds the maximum character length
     * @param int
     * @return void
     */
    public function setAutoTrim( $auto_trim = TRUE )
    {
        $this -> autoTrim = $auto_trim;
    }

    /**
     * Getting the label
     * @return String
     * @return void
     */
    public function getLabel()
    {
        return $this -> label;
    }
    /**
     * Setting a label
     * @param String
     * @return void
     */
    public function setLabel( $label )
    {
        $this -> label = $label;
    }

    /**
     * Sets the padding behaviour
     * @param string $padding_type
     * @return void
     */
    public function setPaddingType( $padding_type )
    {
        $padding_types = [self::PADDING_NONE, self::PADDING_ZEROFILL_LEFT, self::PADDING_RIGHT];
        if( !in_array($padding_type, $padding_types)){
            if( $this -> label )
                throw new ACHProcessorColumnException(sprintf('Invalid padding type for the column %s - choose from %s', $this -> label, implode(',', $padding_types)));
            else throw new ACHProcessorColumnException(sprintf('Invalid padding type - choose from %s', implode(',', $padding_types)));
        }
        $this -> paddingType = $padding_type;
    }

    /**
     * Setting the value of the column
     * @param mixed
     * @return void
     */
    public function setValue( $value )
    {
        // Set the max length to either maxLength (1st priority) or fixedLength (2nd priority)
        $max_length = ($this -> maxLength) ? $this -> maxLength : ($this -> fixedLength ? $this -> fixedLength : null );
        if( $max_length !== null && strlen((string)$value) > $max_length ){

            $value = substr( $value, 0, $max_length );

        }
        $this -> value = $value;
    }
}
