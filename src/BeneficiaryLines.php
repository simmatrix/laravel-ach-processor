<?php

namespace Simmatrix\ACHProcessor;
use Simmatrix\ACHProcessor\Stringable;

/**
 * A beneficiary entry usually comprises multiple lines.
 */
class BeneficiaryLines implements Stringable
{
    /**
     * @var Array of Line
     */
    protected $lines;

    /**
     * @var an Eloquent Model
     */
    protected $model;

    /**
     * @param Eloquent model
     */
    public function __construct($model)
    {
        $this -> model = $model;
    }

    /**
     * @var Line
     */
    public function addLine(Stringable $line)
    {
        $this -> lines[]= $line;
    }

    /**
     * @return Array of Line
     */
    public function getLines()
    {
        return $this -> lines;
    }

    /**
     * @return String A string representation of the model
     */
    public function getString()
    {
        $line_strings = collect($this -> lines) -> map(function($line){
            return $line -> getString();
        }) -> toArray();
        return implode("\r\n", $line_strings);
    }
}
