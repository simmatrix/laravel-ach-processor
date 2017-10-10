<?php

use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedDecimalWithoutDelimiterColumnFactory;

class TestLeftPaddedDecimalWithoutDelimiterColumnFactory extends Orchestra\Testbench\TestCase{

    public function setUp(){
        parent::setUp();
    }

    public function testLeftPaddedDecimalWithoutDelimiterColumn(){
        $column = LeftPaddedDecimalWithoutDelimiterColumnFactory::create(100, 6);
        $this -> assertEquals('010000', $column -> getString());

        $column = LeftPaddedDecimalWithoutDelimiterColumnFactory::create(97.53, 5);
        $this -> assertEquals('09753', $column -> getString());

        $column = LeftPaddedDecimalWithoutDelimiterColumnFactory::create(97.5312, 5);
        $this -> assertEquals('09753', $column -> getString());
    }

}
