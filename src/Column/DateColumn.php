<?php
namespace Simmatrix\ACHProcessor\Column;

use Simmatrix\ACHProcessor\Stringable;

class DateColumn extends Column implements Stringable
{
    /**
     * @var Datetime
     */
    protected $date;

    /**
     * @var String representation of date, to be passed to DateTime -> format( ... )
     */
    protected $format;

    /**
     * @return mixed
     */
    public function getString(){
        return parent::getPaddedValue( $this -> date -> format($this -> format));
    }

    /**
     * @param String
     */
    public function setFormat($format){
        $this -> format = $format;
    }
    /**
     * @param Datetime
     */
    public function setDate(\Datetime $date){
        $this -> date = $date;
    }
}
