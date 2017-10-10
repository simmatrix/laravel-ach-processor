<?php

namespace Simmatrix\ACHProcessor;
use Simmatrix\ACHProcessor\Stringable;

/**
 * A beneficiary object representing the payee
 */
class Beneficiary
{
    /**
     * @var String
     */
    protected $address1 = '';
    /**
     * @var String
     */
    protected $address2 = '';
    /**
     * @var String
     */
    protected $address3 = '';
    /**
     * @var String
     */
    protected $address4 = '';
    /**
     * @var String
     */
    protected $address5 = '';
    /**
     * @var String
     */
    protected $fullname = '';
    /**
     * @var String
     */
    protected $name1 = '';
    /**
     * @var String
     */
    protected $name2 = '';
    /**
     * @var String
     */
    protected $name3 = '';

    /**
     * @var String. Any transaction fees should be deducted
     */
    protected $paymentAmount = '';
    /**
     * @var DateTime
     */
    protected $paymentDateTime = '';
    /**
     * @var String
     */
    protected $paymentId = '';
    /**
     * @var String
     */
    protected $postcode = '';
    /**
     * @var String
     */
    protected $userId = '';

    /**
     * @param String the property name
     * @return mixed
     */
    public function __get($property_name){
        if( property_exists($this, $property_name)){
            return $this -> $property_name;
        }
    }


}
