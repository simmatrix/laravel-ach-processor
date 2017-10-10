<?php

namespace Simmatrix\ACHProcessor;
use Simmatrix\ACHProcessor\Stringable;

/**
 * A beneficiary entry usually comprises multiple lines.
 */
class BeneficiaryLine implements Stringable
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
    public function setLine(Stringable $line)
    {
        $this -> line = $line;
    }

    /**
     * @return Array of Line
     */
    public function getLine()
    {
        return $this -> line;
    }

    /**
     * @return String A string representation of the model
     */
    public function getString()
    {
        return $this -> line -> getString();
    }
}
