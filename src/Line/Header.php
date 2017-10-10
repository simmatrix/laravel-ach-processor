<?php

namespace Simmatrix\ACHProcessor\Line;

use Simmatrix\ACHProcessor\Stringable;
use Simmatrix\ACHProcessor\BeneficiaryLine;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;

abstract class Header extends Line implements Stringable
{
    /**
     * @var Array of BeneficiaryAdapterInterface
     */
    protected $beneficiaries;

    /**
     * @var String The payment description
     */
    protected $paymentDescription;

    protected $batchHeaderHeight = 1;

    protected $beneficiaryLineHeight = 1;

    protected $fileHeaderHeight = 1;

    /**
     * @param Array of BeneficiaryAdapterInterface
     * @param String The key to read the config from
     * @param String The payment description
     */
    public function __construct( array $beneficiaries, string $config_key, string $payment_description )
    {
        $this -> beneficiaries = $beneficiaries;
        $this -> paymentDescription = $payment_description;
        parent::__construct($config_key);
    }

    /**
     * Get the payment description
     * @return String
     */
    public function getPaymentDescription()
    {
        return $this -> paymentDescription;
    }

    /**
     * Get the total number of beneficiaries
     * @return int
     */
    public function getBeneficiaryCount()
    {
        return count($this -> beneficiaries);
    }

    /**
     * Get the number of lines that make up a BeneficiaryLine entry.
     * @return int
     */
    public function getBeneficiaryLineHeight()
    {
        return $this -> beneficiaryLineHeight;
    }

    /**
     * @return String
     */
    public function getString()
    {
        $line = $this -> getLine();
        return $line -> getString();
    }
    /**
     * @return int
     */
    public function getTotalLines()
    {
        //assumes
        return $this -> fileHeaderHeight
        + $this -> batchHeaderHeight
        + ($this -> getBeneficiaryCount() * $this -> getBeneficiaryLineHeight() );
    }

    /**
     * @return float
     */
    public function getTotalPaymentAmount()
    {
        return (float)collect($this -> beneficiaries) -> reduce( function($carry,  BeneficiaryAdapterInterface $beneficiary){
            return $carry += $beneficiary -> getPaymentAmount();
        }, 0);
    }
}
