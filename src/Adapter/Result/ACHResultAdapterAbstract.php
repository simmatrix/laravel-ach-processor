<?php
namespace Simmatrix\ACHProcessor\Adapter\Result;

abstract class ACHResultAdapterAbstract
{
    /**
     * @var COSResult
     */
    protected $achResult;

    /**
     * @var String
     */
    protected $columnDelimiter = ',';

    /**
     * @var Array
     */
    protected $columns = [];

    /**
     * @var String
     */
    public function __construct($string){
        $this -> columns = explode( $this -> columnDelimiter, $string);
    }

    /**
     * @return COSResult
     */
    public function getAchResult(){
        return $this -> achResult;
    }
}
