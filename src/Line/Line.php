<?php

namespace Simmatrix\ACHProcessor\Line;
use Simmatrix\ACHProcessor\Stringable;
use Illuminate\Config\Repository;

class Line implements Stringable
{
    /**
     * @var Array of Column
     */
    protected $columns = [];

    /**
     * @var Illuminate\Config\Repository The config array to use.
     */
    public $config;

    /**
     * @var String
     */
    protected $configKey;

    /**
     * @var String
     */
    protected $columnDelimiter = "";

    /**
     * @param String The key to read the config from
     */
    public function __construct($config_key = null)
    {
        if( $config_key ){
            $this -> config = new Repository(config($config_key));
            $this -> configKey = $config_key;
        }
    }

    /**
     * @param Column
     */
    public function addColumn(Stringable $column)
    {
        $this -> columns[]= $column;
    }

    /**
     * @return int
     */
    public function getColumnCount()
    {
        return count($this -> columns);
    }

    /**
     * @param String
     */
    public function setColumnDelimiter($string)
    {
        $this -> columnDelimiter = $string;
    }

    /**
     * @param Array of Column
     */
    public function setColumns($columns)
    {
        $this -> columns = $columns;
    }

    /**
     * @return mixed
     */
    public function getString()
    {
        $line_outputs = [];

        foreach($this -> columns as $column){
            $line_outputs[]= $column -> getString();
        }

        return implode($this -> columnDelimiter, $line_outputs);
    }
}
