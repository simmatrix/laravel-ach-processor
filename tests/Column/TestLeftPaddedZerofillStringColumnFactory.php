<?php

use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedZerofillStringColumnFactory;

class TestLeftPaddedZerofillStringColumnFactory extends Orchestra\Testbench\TestCase{

    public function setUp(){
        parent::setUp();
    }

    public function testLeftPaddedZerofillStringColumn(){
        $column = LeftPaddedZerofillStringColumnFactory::create('abc123', 10);
        $this -> assertEquals('0000abc123', $column -> getString());

        $column = LeftPaddedZerofillStringColumnFactory::create(9753, 5);
        $this -> assertEquals('09753', $column -> getString());
    }

}
